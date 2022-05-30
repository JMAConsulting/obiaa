<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

/**
 * @param array $params
 *
 * @return array
 */
function civicrm_api3_civi_mobile_survey_interviewer_get($params) {
  $result = (new CRM_CiviMobileAPI_Api_CiviMobileSurveyInterviewer_Get($params))->getResult();

  return civicrm_api3_create_success($result);
}

function _civicrm_api3_civi_mobile_survey_interviewer_get_spec(&$params) {
  $params['survey_id'] = [
    'title' => 'Survey ID',
    'description' => E::ts('Survey ID'),
    'type' => CRM_Utils_Type::T_INT,
    'api.required' => 1,
  ];
}
