<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

/**
 * @param $params
 * @return array
 * @throws api_Exception
 */
function civicrm_api3_civi_mobile_agenda_config_create($params) {
  $result = (new CRM_CiviMobileAPI_Api_CiviMobileAgendaConfig_Create($params))->getResult();

  return civicrm_api3_create_success($result);
}

/**
 * @param $params
 * @return array
 * @throws api_Exception
 */
function civicrm_api3_civi_mobile_agenda_config_get($params) {
  $result = (new CRM_CiviMobileAPI_Api_CiviMobileAgendaConfig_Get($params))->getResult();
  return civicrm_api3_create_success($result);
}

/**
 * Adjust Metadata for create action
 *
 * The metadata is used for setting defaults, documentation & validation
 * @param array $params array or parameters determined by getfields
 */
function _civicrm_api3_civi_mobile_agenda_config_create_spec(&$params) {
  $params['event_id'] = [
    'title' => 'Event id',
    'description' => E::ts('Event id'),
    'api.required' => 1,
    'type' => CRM_Utils_Type::T_INT
  ];
  $params['is_active'] = [
    'title' => 'Is active?',
    'description' => E::ts('Is active?'),
    'api.required' => 1,
    'type' => CRM_Utils_Type::T_BOOLEAN
  ];
}

/**
 * Adjust Metadata for get action
 *
 * The metadata is used for setting defaults, documentation & validation
 * @param array $params array or parameters determined by getfields
 */
function _civicrm_api3_civi_mobile_agenda_config_get_spec(&$params) {
  $params['event_id'] = [
    'title' => 'Event id',
    'description' => E::ts('Event id'),
    'api.required' => 1,
    'type' => CRM_Utils_Type::T_INT
  ];
}
