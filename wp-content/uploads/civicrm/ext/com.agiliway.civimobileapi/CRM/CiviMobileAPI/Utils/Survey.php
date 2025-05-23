<?php

class CRM_CiviMobileAPI_Utils_Survey {

  /**
   * @return array
   */
  public static function getSurveyActivityTypesIds() {
    $surveyActivityTypes = civicrm_api4('OptionValue', 'get', [
      'select' => [
        'value',
      ],
      'where' => [
        ['option_group_id:name', '=', 'activity_type'],
        ['component_id:name', '=', 'CiviCampaign'],
      ],
      'checkPermissions' => FALSE,
    ])->getArrayCopy();

    $surveyActivityTypesIds = [];

    foreach ($surveyActivityTypes as $type) {
      $surveyActivityTypesIds[] = $type['value'];
    }

    return $surveyActivityTypesIds;
  }
}
