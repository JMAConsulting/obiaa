<?php

/**
 * Class handles CiviMobileCustomFields api
 */
class CRM_CiviMobileAPI_Api_CiviMobileCustomFields_Get extends CRM_CiviMobileAPI_Api_CiviMobileBase {

  /**
   * This sets if custom field don't have any value
   */
  const EMPTY_VALUE_SYMBOL = 'NULL_VALUE';

  /**
   * Entity map
   */
  private static $entityMap = [
    'Individual' => [
      'find_for' => ['Contact', 'Contacts', 'Individual'],
    ],
    'Organization' => [
      'find_for' => ['Contact', 'Contacts', 'Organization'],
    ],
    'Household' => [
      'find_for' => ['Contact', 'Contacts', 'Household'],
    ],
    'Activity' => [
      'find_for' => ['Activity', 'Activities'],
    ],
    'Event' => [
      'find_for' => ['Events', 'Event'],
    ],
  ];

  /**
   * Returns validated params
   *
   * @param $params
   *
   * @return array
   * @throws \api_Exception
   */
  protected function getValidParams($params) {
    if (!in_array($params['entity'], self::getAvailableEntities())) {
      throw new api_Exception('Invalid entity. Available values: (' . implode(', ', self::getAvailableEntities()) . ')', 'used_for_invalid_value');
    }

    return [
      'find_for' => self::$entityMap[$params['entity']]['find_for'],
      'entity_id' => $params['entity_id'],
      'is_searchable' => $params['is_searchable'],
      'extends_entity_column_value' => !empty($params['extends_entity_column_value']) ? $params['extends_entity_column_value'] : NULL
    ];
  }

  /**
   * Returns results to api
   *
   * @return array
   */
  public function getResult() {
    $result = [];

    $customGroupsWhereParams = [
      ['extends:name', 'IN', $this->validParams['find_for']],
      ['is_active', '=', TRUE],
    ];

    if($this->validParams['find_for'][0] != "Activities" && $this->validParams['find_for'][0] != "Events" && !empty($this->validParams['extends_entity_column_value'])) {
      $customGroupsWhereParams[] = ['extends_entity_column_value', '=', $this->validParams['extends_entity_column_value']];
    }

    $customGroups = civicrm_api4('CustomGroup', 'get', [
      'where' => $customGroupsWhereParams,
      'checkPermissions' => FALSE,
    ])->getArrayCopy();

    if (empty($customGroups)) {
      return [];
    }

    if (!CRM_Core_Permission::check('administer CiviCRM') && !CRM_Core_Permission::check('access all custom data')) {
      $accessibleCustomGroupsToView = CRM_Core_Permission::customGroup(CRM_Core_Permission::VIEW);

      foreach ($customGroups as $customGroup) {
        if (in_array($customGroup['id'], $accessibleCustomGroupsToView)) {
          $result[] = $this->prepareCustomGroup($customGroup);
        }
      }
    } else {
      foreach ($customGroups as $customGroup) {
        $result[] = $this->prepareCustomGroup($customGroup);
      }
    }

    return $result;
  }

  /**
   * Returns prepared CustomGroup
   *
   * @param $customGroup
   *
   * @return array
   */
  private function prepareCustomGroup($customGroup) {
    $customGroupData = [
      'id' => $customGroup['id'],
      'name' => $customGroup['name'],
      'title' => $customGroup['title'],
      'style' => $customGroup['style'],
      'weight' => (int)$customGroup['weight'],
      'is_multiple' => $customGroup['is_multiple'] ? '1' : '0',
      'custom_fields' => []
    ];

    $customFieldsWhereParams = [
      ['name', '!=', CRM_CiviMobileAPI_Install_Entity_CustomField::SURVEY_GOTV_STATUS],
      ['custom_group_id', '=', $customGroup['id']],
      ['is_active', '=', TRUE],
    ];

    if (!empty($this->validParams['is_searchable'])) {
      $customFieldsWhereParams[] = ['is_searchable', '=', $this->validParams['is_searchable']];
    }

    $customFields = civicrm_api4('CustomField', 'get', [
      'where' => $customFieldsWhereParams,
      'checkPermissions' => FALSE,
    ])->getArrayCopy();

    if (empty($customFields)) {
      return $customGroupData;
    }

    foreach ($customFields as $customField) {
      $customGroupData['custom_fields'][] = $this->prepareCustomField($customField);
    }

    return $customGroupData;
  }

  /**
   * Returns prepared CustomField
   *
   * @param $customField
   *
   * @return array
   */
  private function prepareCustomField($customField) {
    $availableValues = [];

    if (!empty($customField['option_group_id'])) {
      $availableValues = CRM_CiviMobileAPI_Utils_OptionValue::getGroupValues($customField['option_group_id'], ['is_active' => 1]);
    }

    foreach ($availableValues as $key => $value) {
      $availableValues[$key]['weight'] = (int)$availableValues[$key]['weight'];
    }

    if ($customField['html_type'] == 'Radio' && $customField['data_type'] == "Boolean") {
      $availableValues = ['1', '0'];
    }

    $prepareCustomField = [
      "id" => $customField['id'],
      "name" => $customField['name'],
      "default_value" => $customField['default_value'],
      "text_length" => (!empty($customField['text_length'])) ? (int)$customField['text_length'] : "NULL",
      "is_view" => $customField['is_view'] ? '1' : '0',
      "label" => $customField['label'],
      "weight" => (int)$customField['weight'],
      "data_type" => $customField['data_type'],
      "html_type" => $customField['html_type'],
      "is_required" => $customField['is_required'] ? '1' : '0',
      "is_searchable" => $customField['is_searchable'],
      "current_value" => (!empty($this->validParams['entity_id'])) ? $this->getCurrentValue($customField['id']) : Null,
      "note_columns" => (!empty($customField['note_columns'])) ? (int)$customField['note_columns'] : "",
      "note_rows" => (!empty($customField['note_rows'])) ? (int)$customField['note_rows'] : "",
      "date_format" => (!empty($customField['date_format'])) ? $customField['date_format'] : "",
      "time_format" => (!empty($customField['time_format'])) ? $customField['time_format'] : "",
      "start_date_years" => (!empty($customField['start_date_years'])) ? $customField['start_date_years'] : "",
      "end_date_years" => (!empty($customField['end_date_years'])) ? $customField['end_date_years'] : "",
      "default_currency" => CRM_Core_Config::singleton()->defaultCurrency,
      "default_currency_symbol" => CRM_Core_Config::singleton()->defaultCurrencySymbol,
      "available_values" => array_values($availableValues),
    ];

    if ($prepareCustomField['data_type'] == 'Money' && ($prepareCustomField['html_type'] == 'Radio' || $prepareCustomField['html_type'] == 'Select')) {
      $prepareCustomField['current_value'] = preg_replace("/.00$/", "", $prepareCustomField['current_value']);
    }

    return $prepareCustomField;
  }

  /**
   * Gets available entities for that api
   *
   * @return array
   */
  public static function getAvailableEntities() {
    return array_keys(self::$entityMap);
  }

  /**
   * Gets current values
   *
   * @param $customFieldId
   *
   * @return string
   */
  private function getCurrentValue($customFieldId) {
    try {
      $dbData = CRM_Core_BAO_CustomField::getTableColumnGroup($customFieldId);
    } catch (Exception $e) {
      return self::EMPTY_VALUE_SYMBOL;
    }

    $table = $dbData[0];
    $column = $dbData[1];
    $query = "SELECT {$table}.{$column} as current_value FROM {$table} WHERE {$table}.entity_id = {$this->validParams['entity_id']}";
    $result = CRM_Core_DAO::executeQuery($query);

    if ($result->fetch()) {
      return ($result->current_value === NULL) ? self::EMPTY_VALUE_SYMBOL : $result->current_value;
    }

    return self::EMPTY_VALUE_SYMBOL;
  }

}
