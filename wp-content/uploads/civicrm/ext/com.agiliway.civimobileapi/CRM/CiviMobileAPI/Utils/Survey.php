<?php

class CRM_CiviMobileAPI_Utils_Survey {

  /**
   * @return array
   */
  public static function getSurveyActivityTypesIds() {
    $surveyActivityTypes = civicrm_api3('OptionValue', 'get', [
      'return' => "value",
      'option_group_id' => "activity_type",
      'component_id' => "CiviCampaign",
      'options' => ['limit' => 0],
    ]);

    $surveyActivityTypesIds = [];

    foreach ($surveyActivityTypes['values'] as $type) {
      $surveyActivityTypesIds[] = $type['value'];
    }

    return $surveyActivityTypesIds;
  }
}
