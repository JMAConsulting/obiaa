<?php

class CRM_CiviMobileAPI_Api_CiviMobileSurvey_GetStructure extends CRM_CiviMobileAPI_Api_CiviMobileBase {

  /**
   * Returns results to api
   *
   * @return array
   * @throws api_Exception
   */
  public function getResult() {
    try {
      $surveyInfo = civicrm_api3('Survey', 'getsingle', [
        'id' => $this->validParams['id'],
      ]);
    } catch (Exception $e) {
      throw new api_Exception('Survey does not exists','survey_does_not_exists');
    }

    $isPetition = true;

    try {
      $petitionOptionValue = civicrm_api3('OptionValue', 'getsingle', [
        'sequential' => 1,
        'option_group_id' => "activity_type",
        'component_id' => "CiviCampaign",
        'name' => "Petition",
      ]);

      if ($surveyInfo['activity_type_id'] != $petitionOptionValue['value']) {
        $isPetition = false;
      }
    } catch (Exception $e) {
      $isPetition = false;
    }

    if ($isPetition) {
      if (!CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionToViewPetition()) {
        throw new API_Exception(ts('Permission is required.'));
      }
    } else {
      if (!CRM_Core_Session::getLoggedInContactID()) {
        throw new API_Exception(ts('Not authorized.'));
      }
      if (!CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionToGetSurveysList()) {
        throw new API_Exception(ts('Permission is required.'));
      }
    }

    $survey = [
      'id' => $surveyInfo['id'],
      'title' => $surveyInfo['title'],
      'is_active' => $surveyInfo['is_active'],
      'is_petition' => $isPetition ? 1 : 0,
      'activity_type_id' => $surveyInfo['activity_type_id'],
      'instructions' => $surveyInfo['instructions'],
      'default_number_of_contacts' => $surveyInfo['default_number_of_contacts'],
      'max_number_of_contacts' => $surveyInfo['max_number_of_contacts'],
      'short_instructions' => $surveyInfo['instructions'] ? html_entity_decode(mb_substr(strip_tags(preg_replace('/\s\s+/', ' ', $surveyInfo['instructions'])), 0, 200), ENT_QUOTES | ENT_HTML401) : ''
    ];

    if (!empty($surveyInfo['result_id'])) {
      $resultOptions = civicrm_api3('OptionValue', 'get', [
        'sequential' => 1,
        'option_group_id' => $surveyInfo['result_id'],
        'options' => ['limit' => 0],
      ])['values'];

      $preparedResultOptions = [];

      foreach ($resultOptions as $option) {
        if ($option['is_active']) {
          $preparedResultOptions[] = [
            'id' => $option['id'],
            'label' => $option['label'],
            'name' => $option['name'],
            'is_default' => $option['is_default'],
            'value' => $option['value'],
          ];
        }
      }

      $survey['result_id'] = $surveyInfo['result_id'];
      $survey['result_set'] = $preparedResultOptions;
    } else {
      $survey['result_id'] = '';
      $survey['result_set'] = [];
    }

    $joinedProfiles = civicrm_api3('UFJoin', 'get', [
      'entity_table' => "civicrm_survey",
      'entity_id' => $this->validParams['id'],
    ])['values'];

    $survey['profiles']['activity_profile'] = [];
    $survey['profiles']['contact_profile'] = [];

    foreach ($joinedProfiles as $profile) {
      $fields = CRM_Core_BAO_UFGroup::getFields($profile['uf_group_id']);
      $this->prepareFields($fields);
      $profile_name = 'activity_profile';

      if (($profile['weight'] == 2 && $isPetition) || ($profile['weight'] == 1 && !$isPetition)) {
        $profile_name = 'contact_profile';
      }

      $survey['profiles'][$profile_name] = [
        'id' => $profile['uf_group_id'],
        'fields' => $fields
      ];
    }

    return [$survey];
  }

  /**
   * Returns validated params
   *
   * @param $params
   *
   * @return array
   * @throws api_Exception
   */
  protected function getValidParams($params) {
    return [
      'id' => $params['id'],
      'activity_type_id' => $params['activity_type_id']
    ];
  }

  /**
   * Prepare fields
   *
   * @param $fields
   */
  private function prepareFields(&$fields) {
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

      $field = $fieldParams;
    }
  }

}
