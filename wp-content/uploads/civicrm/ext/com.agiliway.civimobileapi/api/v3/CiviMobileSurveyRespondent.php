<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

/**
 * @param array $params
 *
 * @return array
 */
function civicrm_api3_civi_mobile_survey_respondent_reserve($params) {
  $result = (new CRM_CiviMobileAPI_Api_CiviMobileSurveyRespondent_Reserve($params))->getResult();

  return civicrm_api3_create_success($result);
}

/**
 * @param array $params array or parameters determined by getfields
 */
function _civicrm_api3_civi_mobile_survey_respondent_reserve_spec(&$params) {
  $params['survey_id'] = [
    'title' => 'Survey ID',
    'description' => E::ts('Survey ID'),
    'type' => CRM_Utils_Type::T_INT,
    'api.required' => 1,
  ];
  $params['contact_ids'] = [
    'title' => 'Contact IDs',
    'description' => E::ts('Contact IDs'),
    'type' => CRM_Utils_Type::T_STRING,
    'api.required' => 1,
  ];
  $params['interviewer_id'] = [
    'title' => 'Interviewer ID',
    'description' => E::ts('Interviewer ID'),
    'type' => CRM_Utils_Type::T_INT,
  ];
}

/**
 * @param array $params
 *
 * @return array
 */
function civicrm_api3_civi_mobile_survey_respondent_get($params) {
  $result = (new CRM_CiviMobileAPI_Api_CiviMobileSurveyRespondent_Get($params))->getResult();

  return civicrm_api3_create_success($result);
}

/**
 * @param array $params array or parameters determined by getfields
 */
function _civicrm_api3_civi_mobile_survey_respondent_get_spec(&$params) {
  $params['survey_id'] = [
    'title' => 'Survey ID',
    'description' => E::ts('Survey ID'),
    'type' => CRM_Utils_Type::T_INT,
    'api.required' => 1,
  ];
  $params['interviewer_id'] = [
    'title' => 'Interviewer ID',
    'description' => E::ts('Interviewer ID'),
    'type' => CRM_Utils_Type::T_INT,
    'api.required' => 0,
  ];
  $params['survey_status'] = [
    'title' => 'Status',
    'description' => E::ts('Status'),
    'type' => CRM_Utils_Type::T_STRING,
    'options' => [
      'Reserved' => E::ts('Reserved'),
      'Interviewed' => E::ts('Interviewed'),
      'GOTV' => E::ts('GOTV'),
    ],
  ];
  $params['group'] = [
    'title' => 'Group',
    'description' => E::ts('Group'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $params['contact_type'] = [
    'title' => 'Contact type',
    'description' => E::ts('Contact type'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $params['display_name'] = [
    'title' => 'Display name',
    'description' => E::ts('Display name'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $params['city'] = [
    'title' => 'Primary Address City',
    'description' => E::ts('Primary Address City'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $params['street_address'] = [
    'title' => 'Primary Address Street Address',
    'description' => E::ts('Primary Address Street Address'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
}

/**
 * @param array $params
 *
 * @return array
 */
function civicrm_api3_civi_mobile_survey_respondent_release($params) {
  $result = (new CRM_CiviMobileAPI_Api_CiviMobileSurveyRespondent_Release($params))->getResult();

  return civicrm_api3_create_success($result);
}

/**
 * @param array $params array or parameters determined by getfields
 */
function _civicrm_api3_civi_mobile_survey_respondent_release_spec(&$params) {
  $params['survey_id'] = [
    'title' => 'Survey ID',
    'description' => E::ts('Survey ID'),
    'type' => CRM_Utils_Type::T_INT,
    'api.required' => 1,
  ];
  $params['contact_ids'] = [
    'title' => 'Contact IDs',
    'description' => E::ts('Contact IDs'),
    'type' => CRM_Utils_Type::T_STRING,
    'api.required' => 1,
  ];
  $params['interviewer_id'] = [
    'title' => 'Interviewer ID',
    'description' => E::ts('Interviewer ID'),
    'type' => CRM_Utils_Type::T_INT,
  ];
}

/**
 * @param array $params
 *
 * @return array
 */
function civicrm_api3_civi_mobile_survey_respondent_gotv($params) {
  $result = (new CRM_CiviMobileAPI_Api_CiviMobileSurveyRespondent_Gotv($params))->getResult();

  return civicrm_api3_create_success($result);
}

/**
 * @param array $params array or parameters determined by getfields
 */
function _civicrm_api3_civi_mobile_survey_respondent_gotv_spec(&$params) {
  $params['survey_id'] = [
    'title' => 'Survey ID',
    'description' => E::ts('Survey ID'),
    'type' => CRM_Utils_Type::T_INT,
    'api.required' => 1,
  ];
  $params['contact_ids'] = [
    'title' => 'Contact IDs',
    'description' => E::ts('Contact IDs'),
    'type' => CRM_Utils_Type::T_STRING,
    'api.required' => 1,
  ];
  $params['interviewer_id'] = [
    'title' => 'Interviewer ID',
    'description' => E::ts('Interviewer ID'),
    'type' => CRM_Utils_Type::T_INT,
  ];
}

/**
 * @param array $params
 *
 * @return array
 */
function civicrm_api3_civi_mobile_survey_respondent_get_to_reserve($params) {
  $result = (new CRM_CiviMobileAPI_Api_CiviMobileSurveyRespondent_GetToReserve($params))->getResult();

  return civicrm_api3_create_success($result);
}

/**
 * @param array $params array or parameters determined by getfields
 */
function _civicrm_api3_civi_mobile_survey_respondent_get_to_reserve_spec(&$params) {
  $params['survey_id'] = [
    'title' => 'Survey ID',
    'description' => E::ts('Survey ID'),
    'type' => CRM_Utils_Type::T_INT,
    'api.required' => 1,
  ];
  $params['group'] = [
    'title' => 'Group',
    'description' => E::ts('Group'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $params['contact_type'] = [
    'title' => 'Contact type',
    'description' => E::ts('Contact type'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $params['city'] = [
    'title' => 'Primary Address City',
    'description' => E::ts('Primary Address City'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $params['street_address'] = [
    'title' => 'Primary Address Street Address',
    'description' => E::ts('Primary Address Street Address'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $params['display_name'] = [
    'title' => 'Display name',
    'description' => E::ts('Display name'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
}
