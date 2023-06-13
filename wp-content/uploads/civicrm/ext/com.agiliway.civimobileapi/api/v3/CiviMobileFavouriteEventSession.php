<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

/**
 * @param $params
 * @return array
 * @throws api_Exception
 */
function civicrm_api3_civi_mobile_favourite_event_session_create($params) {
  if (!CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionToSetFavouriteEventSession()) {
    throw new api_Exception('You don`t have enough permissions.', 'do_not_have_enough_permissions');
  }

  try {
    $eventSession = civicrm_api3('CiviMobileEventSession', 'getsingle', [
      'id' => $params['event_session_id'],
    ]);
  } catch (Exception $e) {
    throw new api_Exception('The Event Session doesn`t exists.', 'event_session_does_not_exists');
  }

  $favourite = new CRM_CiviMobileAPI_BAO_FavouriteEventSession();
  $favourite->contact_id = (int) CRM_Core_Session::getLoggedInContactID();
  $favourite->event_session_id = $params['event_session_id'];
  $favourite->find();

  if (!$params['is_favourite']) {
    $favourite->delete();
  } elseif (!$eventSession['is_favourite']) {
    $favourite->save();
  }

  return civicrm_api3_create_success(['is_favourite' => $params['is_favourite']]);
}

/**
 * Adjust Metadata for create action
 *
 * The metadata is used for setting defaults, documentation & validation
 * @param array $params array or parameters determined by getfields
 */
function _civicrm_api3_civi_mobile_favourite_event_session_create_spec(&$params) {
  $params['event_session_id'] = [
    'title' => 'EventSessionID',
    'description' => E::ts('EventSessionID'),
    'api.required' => 1,
    'type' => CRM_Utils_Type::T_INT
  ];
  $params['is_favourite'] = [
    'title' => 'Is favourite?',
    'description' => E::ts('Is favourite?'),
    'api.required' => 1,
    'type' => CRM_Utils_Type::T_BOOLEAN
  ];
}
