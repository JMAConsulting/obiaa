<?php

class CRM_CiviMobileAPI_Api_CiviMobileSurvey_GetStructure extends CRM_CiviMobileAPI_Api_CiviMobileBase {

  /**
   * Returns results to api
   *
   * @return array
   * @throws CRM_Core_Exception
   */
  public function getResult() {
    try {
      $surveyInfo = civicrm_api3('Survey', 'getsingle', [
        'id' => $this->validParams['id'],
      ]);
    } catch (Exception $e) {
      throw new CRM_Core_Exception('Survey does not exists', 'survey_does_not_exists');
    }

    $isPetition = TRUE;

    try {
      $petitionOptionValue = civicrm_api3('OptionValue', 'getsingle', [
        'sequential' => 1,
        'option_group_id' => "activity_type",
        'component_id' => "CiviCampaign",
        'name' => "Petition",
      ]);

      if ($surveyInfo['activity_type_id'] != $petitionOptionValue['value']) {
        $isPetition = FALSE;
      }
    } catch (Exception $e) {
      $isPetition = FALSE;
    }

    if ($isPetition) {
      if (!CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionToViewPetition()) {
        throw new CRM_Core_Exception(ts('Permission is required.'));
      }
    } else {
      if (!CRM_Core_Session::getLoggedInContactID()) {
        throw new CRM_Core_Exception(ts('Not authorized.'));
      }
      if (!CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionToGetSurveysList()) {
        throw new CRM_Core_Exception(ts('Permission is required.'));
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
      'short_instructions' => $surveyInfo['instructions'] ? html_entity_decode(mb_substr(strip_tags(preg_replace('/\s\s+/', ' ', $surveyInfo['instructions'])), 0, 200), ENT_QUOTES | ENT_HTML401) : '',
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
      CRM_CiviMobileAPI_Utils_Profile::prepareFields($fields);
      $profile_name = 'activity_profile';

      if (($profile['weight'] == 2 && $isPetition) || ($profile['weight'] == 1 && !$isPetition)) {
        $profile_name = 'contact_profile';
      }

      $survey['profiles'][$profile_name] = [
        'id' => $profile['uf_group_id'],
        'fields' => $fields,
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
   * @throws CRM_Core_Exception
   */
  protected function getValidParams($params) {
    return [
      'id' => $params['id'],
      'activity_type_id' => $params['activity_type_id'],
    ];
  }

}
