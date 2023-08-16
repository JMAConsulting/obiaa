<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

/**
 * @param $params
 * @return array
 * @throws api_Exception
 */
function civicrm_api3_civi_mobile_event_session_create($params) {
  $result = (new CRM_CiviMobileAPI_Api_CiviMobileEventSession_Create($params))->getResult();

  return civicrm_api3_create_success($result);
}

/**
 * @param $params
 * @return array
 * @throws api_Exception
 */
function civicrm_api3_civi_mobile_event_session_get($params) {
  $result = (new CRM_CiviMobileAPI_Api_CiviMobileEventSession_Get($params))->getResult();
  return civicrm_api3_create_success($result);
}

/**
 * Adjust Metadata for create action
 *
 * The metadata is used for setting defaults, documentation & validation
 * @param array $params array or parameters determined by getfields
 */
function _civicrm_api3_civi_mobile_event_session_create_spec(&$params) {
  $params['id'] = [
    'title' => 'Id',
    'description' => E::ts('EventSession id'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_INT
  ];
  $params['event_id'] = [
    'title' => 'Event id',
    'description' => E::ts('Event id'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_INT
  ];
  $params['title'] = [
    'title' => 'Title',
    'description' => E::ts('Title'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_STRING
  ];
  $params['speakers'] = [
    'title' => 'Speakers',
    'description' => E::ts('Speakers'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_STRING
  ];
  $params['venue_id'] = [
    'title' => 'Venue',
    'description' => E::ts('Venue'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_STRING
  ];
  $params['description'] = [
    'title' => 'Description',
    'description' => E::ts('Description'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_STRING
  ];
  $params['start_time'] = [
    'title' => 'Start time',
    'description' => E::ts('Start time'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME
  ];
  $params['end_time'] = [
    'title' => 'End time',
    'description' => E::ts('End time'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME
  ];
}

/**
 * Adjust Metadata for get action
 *
 * The metadata is used for setting defaults, documentation & validation
 * @param array $params array or parameters determined by getfields
 */
function _civicrm_api3_civi_mobile_event_session_get_spec(&$params) {
  $params['id'] = [
    'title' => 'Id',
    'description' => E::ts('EventSession id'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_INT
  ];
  $params['event_id'] = [
    'title' => 'Event id',
    'description' => E::ts('Event id'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_INT
  ];
  $params['title'] = [
    'title' => 'Title',
    'description' => E::ts('Title'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_STRING
  ];
  $params['speaker'] = [
    'title' => 'Speaker',
    'description' => E::ts('Speaker'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_INT
  ];
  $params['venue_id'] = [
    'title' => 'Venue',
    'description' => E::ts('Venue'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_INT
  ];
  $params['description'] = [
    'title' => 'Description',
    'description' => E::ts('Description'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_STRING
  ];
  $params['start_time'] = [
    'title' => 'Start time',
    'description' => E::ts('Start time'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_TIMESTAMP
  ];
  $params['end_time'] = [
    'title' => 'End time',
    'description' => E::ts('End time'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME
  ];
  $params['is_display'] = [
    'title' => 'Is display?',
    'description' => E::ts('Is display?'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_BOOLEAN
  ];
}

/**
 * @param $params
 * @return array
 * @throws api_Exception
 */
function civicrm_api3_civi_mobile_event_session_delete($params) {
  if (!CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionForDeleteEventSession()) {
    throw new api_Exception('You don`t have enough permissions.', 'do_not_have_enough_permissions');
  }

  try {
    $eventSession = CRM_CiviMobileAPI_BAO_EventSession::findById($params['id']);
  } catch (Exception $e) {
    throw new api_Exception('This session does not exists.', 'session_does_not_exists');
  }
  $eventSession->delete();
  return civicrm_api3_create_success([
    'message' => 'The Event Session was deleted.'
  ]);
}

/**
 * Adjust Metadata for delete action
 *
 * The metadata is used for setting defaults, documentation & validation
 * @param array $params array or parameters determined by getfields
 */
function _civicrm_api3_civi_mobile_event_session_delete_spec(&$params) {
  $params['id'] = [
    'title' => 'Id',
    'description' => E::ts('EventSession id'),
    'api.required' => 1,
    'type' => CRM_Utils_Type::T_INT
  ];
}
