<?php

class CRM_CiviMobileAPI_Utils_Profile {
  
  const PROFILE_CONFIG = [
    "country" => [
      "apiEntity" => "Country",
      "labelKey" => "name",
      "valueKey" => "name"
    ],
    "state_province" => [
      "apiEntity" => "StateProvince",
      "filter_params" => [
        [
          'fieldName' => "country",
          'param_name' => "country_id.name",
        ]
      ],
      "labelKey" => "name",
      "valueKey" => "name"
    ],
    "participant_status" => [
      "apiEntity" => "ParticipantStatusType",
      "labelKey" => "name",
      "valueKey" => "id"
    ],
  ];
  
  public static function prepareFields(&$fields) {
    $customFieldsIds = [];
    
    foreach ($fields as $key => $field) {
      if (preg_match('/^custom_\d+$/', $field['name'])) {
        array_push($customFieldsIds, (int) trim($field['name'], 'custom_'));
      }
    }
    
    $customFields = [];
    
    if (!empty($customFieldsIds)) {
      $customFields = civicrm_api3('CustomField', 'get', [
        'id' => ['IN' => $customFieldsIds],
      ])['values'];
    }
    
    foreach ($fields as &$field) {
      $customField = [];
      
      if (preg_match('/^custom_\d+$/', $field['name'])) {
        $customField = $customFields[(int) trim($field['name'], 'custom_')];
      }
      
      $fieldParams = [
        'name' => $field['name'],
        'title' => $field['title'],
        'html_type' => empty($customField) ? $field['html_type'] : $customField['html_type'],
        'data_type' => empty($customField) ? $field['data_type'] : $customField['data_type'],
        'attributes' => $field['attributes'],
        'group_id' => $field['group_id'],
        'field_id' => $field['field_id'],
        "is_required" => (!empty($field['is_required'])) ? $field['is_required'] : 0,
        "is_view" => (!empty($field['is_view'])) ? $field['is_view'] : 0,
        "date_format" => (!empty($field['date_format'])) ? $field['date_format'] : "",
        "time_format" => (!empty($field['time_format'])) ? $field['time_format'] : "",
        "start_date_years" => (!empty($field['start_date_years'])) ? $field['start_date_years'] : "",
        "end_date_years" => (!empty($field['end_date_years'])) ? $field['end_date_years'] : "",
        "default_currency" => CRM_Core_Config::singleton()->defaultCurrency,
        "default_currency_symbol" => CRM_Core_Config::singleton()->defaultCurrencySymbol,
        'default_value' => (!empty($customField['default_value'])) ? $customField['default_value'] : ""
      ];
      
      if (!empty($customField["option_group_id"])) {
        $fieldParams['options'] = CRM_CiviMobileAPI_Utils_OptionValue::getGroupValues($customField["option_group_id"], ['is_active' => 1]);
      } else if (!empty($field['pseudoconstant']['optionGroupName'])) {
        $fieldParams['options'] = CRM_CiviMobileAPI_Utils_OptionValue::getGroupValues($field['pseudoconstant']['optionGroupName'], ['is_active' => 1]);
      }
      if ($customField['html_type'] == 'Radio' && $customField['data_type'] == "Boolean") {
        $fieldParams['options'] = ['1','0'];
      }
      
      if ($field['name'] == 'participant_role') {
        $fieldParams['options'] = civicrm_api4('OptionValue', 'get', [
          'where' => [['option_group_id:name', '=', 'participant_role']],
          'checkPermissions' => FALSE,
        ])->getArrayCopy();
      }
      
      $mainFieldName = explode('-', $field['name'])[0];
      
      if (isset(self::PROFILE_CONFIG[$mainFieldName])) {
        $fieldParams['entity_options'] = self::PROFILE_CONFIG[$mainFieldName];
      }
      
      $field = $fieldParams;
    }
  }
}
