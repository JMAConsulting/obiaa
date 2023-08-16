<?php

/**
 * Returns petitions for user
 *
 * @param $params
 * @return array
 * @throws api_Exception
 */
function civicrm_api3_civi_mobile_survey_get_contact_surveys($params) {
  $result = (new CRM_CiviMobileAPI_Api_CiviMobileSurvey_GetContactSurveys($params))->getResult();
  return civicrm_api3_create_success($result);
}

/**
 * @param $params
 */
function _civicrm_api3_civi_mobile_survey_get_contact_surveys_spec(&$params) {
  $params['survey_id'] = [
    'title' => 'Survey ID',
    'description' => ts('Survey ID'),
    'type' => CRM_Utils_Type::T_INT,
    'api.required' => 0,
  ];
  $params['title'] = [
    'title' => 'Title',
    'description' => ts('Title'),
    'type' => CRM_Utils_Type::T_STRING,
    'api.required' => 0,
  ];
  $params['contact_id'] = [
    'title' => 'Contact ID',
    'description' => ts('Contact ID'),
    'type' => CRM_Utils_Type::T_INT,
    'api.required' => 0,
  ];
  $params['activity_type_id'] = [
    'title' => 'Activity type ID',
    'description' => ts('Activity type ID'),
    'type' => CRM_Utils_Type::T_STRING,
    'api.required' => 0,
  ];
  $params['is_signed'] = [
    'title' => 'Is signed?',
    'description' => ts('Is signed?'),
    'type' => CRM_Utils_Type::T_BOOLEAN,
    'api.required' => 0,
  ];
  $params['is_active'] = [
    'title' => 'Is active?',
    'description' => ts('Is active?'),
    'type' => CRM_Utils_Type::T_BOOLEAN,
    'api.required' => 0,
  ];
}

/**
 * Returns info about fields for petition
 *
 * @param $params
 * @return array
 * @throws api_Exception
 */
function civicrm_api3_civi_mobile_survey_get_structure($params) {
  $result = (new CRM_CiviMobileAPI_Api_CiviMobileSurvey_GetStructure($params))->getResult();
  return civicrm_api3_create_success($result);
}

/**
 * @param $params
 */
function _civicrm_api3_civi_mobile_survey_get_structure_spec(&$params) {
  $params['id'] = [
    'title' => 'Survey ID',
    'description' => ts('Survey ID'),
    'type' => CRM_Utils_Type::T_INT,
    'api.required' => 1,
  ];
}

/**
 * Signs petition
 *
 * @param $params
 * @return array
 * @throws api_Exception
 */
function civicrm_api3_civi_mobile_survey_sign($params) {
  $result = (new CRM_CiviMobileAPI_Api_CiviMobileSurvey_Sign($params))->getResult();
  return civicrm_api3_create_success($result);
}

/**
 * @param $params
 */
function _civicrm_api3_civi_mobile_survey_sign_spec(&$params) {
  $params['survey_id'] = [
    'title' => 'Survey ID',
    'description' => ts('Survey ID'),
    'type' => CRM_Utils_Type::T_INT,
    'api.required' => 1,
  ];
  $params['contact_id'] = [
    'title' => 'Contact ID',
    'description' => ts('Contact ID'),
    'type' => CRM_Utils_Type::T_INT,
    'api.required' => 0,
  ];
  $params['values'] = [
    'title' => 'Values',
    'description' => ts('Values'),
    'type' => CRM_Utils_Type::T_STRING,
    'api.required' => 1,
  ];
  $params['note'] = [
    'title' => 'Note',
    'description' => ts('Note'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $params['result'] = [
    'title' => 'Result',
    'description' => ts('Result'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
}

/**
 * Signs petition
 *
 * @param $params
 * @return array
 * @throws api_Exception
 */
function civicrm_api3_civi_mobile_survey_get_signed_values($params) {
  $result = (new CRM_CiviMobileAPI_Api_CiviMobileSurvey_GetSignedValues($params))->getResult();
  return civicrm_api3_create_success($result);
}

/**
 * @param $params
 */
function _civicrm_api3_civi_mobile_survey_get_signed_values_spec(&$params) {
  $params['survey_id'] = [
    'title' => 'Survey ID',
    'description' => ts('Survey ID'),
    'type' => CRM_Utils_Type::T_INT,
    'api.required' => 1,
  ];
  $params['contact_id'] = [
    'title' => 'Contact ID',
    'description' => ts('Contact ID'),
    'type' => CRM_Utils_Type::T_INT,
    'api.required' => 1,
  ];
}
