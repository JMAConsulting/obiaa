<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

/**
 * Returns participants
 *
 * @param array $params
 *
 * @return array
 */
function civicrm_api3_civi_mobile_participant_get($params) {
  if (!empty($params['name'])) {
    $newParams = $params;
    $newParams['options']['limit'] = 0;

    $allParticipants = civicrm_api3('Participant', 'get', $newParams)['values'];

    $contactsId = [];
    foreach ($allParticipants as $participant) {
      if (preg_match('/' . $params['name'] . '/i', $participant['display_name'])) {
        $contactsId[] = $participant['contact_id'];
      }
    }

    if (empty($contactsId)) {
      return civicrm_api3_create_success([]);
    }

    $params['contact_id'] = ['IN' => $contactsId];
  }

  return civicrm_api3_create_success(civicrm_api3('Participant', 'get', $params)['values']);
}

/**
 * Specify Metadata for get action.
 *
 * @param array $params
 */
function _civicrm_api3_civi_mobile_participant_get_spec(&$params) {
  $params['event_id'] = [
    'title' => 'Event id',
    'description' => E::ts('Event id'),
    'type' => CRM_Utils_Type::T_INT,
    'api.required' => 0,
  ];
  $params['contact_id'] = [
    'title' => 'Contact id',
    'description' => E::ts('Contact id'),
    'type' => CRM_Utils_Type::T_INT,
    'api.required' => 0,
  ];
  $params['name'] = [
    'title' => 'Display name',
    'description' => E::ts('Display name'),
    'type' => CRM_Utils_Type::T_STRING,
    'api.required' => 0,
  ];
}

/**
 * Updates Participant status to 'Registered' or 'Attended'
 *
 * @param array $params
 *
 * @return array
 * @throws API_Exception
 */
function civicrm_api3_civi_mobile_participant_create($params) {
  if (!CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionForChangingParticipantStatuses()) {
    throw new api_Exception('Permission required.', 'permission_required');
  }
  $participantId = (int) $params['participant_id'];
  $participant = new CRM_Event_DAO_Participant();
  $participant->id = $participantId;

  if (!$participant->find(TRUE)) {
    throw new api_Exception('Participant(id = '. $participantId .') doesn\'t exist.' , 'participant_doesnt_exist');
  }

  $participantEventId = $participant->event_id;
  $qrCodeInfo = CRM_CiviMobileAPI_Utils_ParticipantQrCode::getQrCodeInfo($participantId);

  if ($participantEventId != $params['event_id']) {
    throw new api_Exception('Event id does not exist for this participant id.' , 'uncorrect_event_id');
  }

  $allowStatuses = [1, 2];
  if (!in_array($params['status_id'], $allowStatuses)) {
    throw new api_Exception('Field "status_id" is not allowed status. Allow statuses: ' . implode(', ', $allowStatuses) . ' (Registered, Attended).', 'not_allowed_status');
  }

  if (!in_array($participant->status_id, $allowStatuses)) {
    throw new api_Exception('Participant does not have status Registered.', 'participant_status_is_not_registered');
  }

  if (!empty($qrCodeInfo['qr_code_hash']) && $qrCodeInfo['qr_code_hash'] != $params['qr_token']) {
    throw new api_Exception('QR token does not exist for this participant id. Please fill correct token.' , 'uncorrect_token');
  }

  $participantStatusId = (int) $params['status_id'];
  $participant = new CRM_Event_DAO_Participant();
  $participant->id = $participantId;
  $participant->find(TRUE);

  if ($participant->status_id == $participantStatusId) {
    throw new api_Exception('Participant(id = '. $participantId .') already has this status.' , 'error_same_status');
  }

  $participant = new CRM_Event_DAO_Participant();
  $participant->id = $participantId;
  $participant->status_id = $participantStatusId;
  $participant->save();

  $result = ['message' => E::ts("Participant status successfully updated.")];

  return civicrm_api3_create_success($result);
}

/**
 * Specify Metadata for create action.
 *
 * @param array $params
 */
function _civicrm_api3_civi_mobile_participant_create_spec(&$params) {
  $params['participant_id'] = [
    'title' => 'Participant id',
    'description' => E::ts('Participant id'),
    'type' => CRM_Utils_Type::T_INT,
    'api.required' => 1,
  ];
  $params['status_id'] = [
    'title' => 'Participant status id',
    'description' => E::ts('Participant status id. Allow statuses: "Registered" or "Attended"'),
    'type' => CRM_Utils_Type::T_INT,
    'api.required' => 1,
  ];
  $params['event_id'] = [
    'title' => 'Event id',
    'description' => E::ts('Event id'),
    'type' => CRM_Utils_Type::T_INT,
    'api.required' => 1,
  ];
  $params['qr_token'] = [
    'title' => 'QR token',
    'description' => E::ts('Qr token'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
}

/**
 * Params for CiviMobileParticipant getlist
 *
 * @param $request
 */
function _civicrm_api3_civi_mobile_participant_getlist_params(&$request) {
  unset($request['params']['options']);
  $request['params']['options']['limit'] = 0;
  $request['params']['name'] = $request['input'];
}

/**
 * Get output for CiviMobileParticipant list.
 *
 * @param array $result
 * @param array $request
 *
 * @param $entity
 * @param $fields
 * @return array
 * @see _civicrm_api3_generic_getlist_output
 *
 */
function _civicrm_api3_civi_mobile_participant_getlist_output($result, $request, $entity, $fields) {
  $output = [];
  foreach ($result['values'] as $participant) {
    $output[] = [
      'id' => $participant['participant_id'],
      'label' => $participant['display_name'],
      'description' => []
    ];
  }

  return $output;
}

/**
 * Used to disable checkPermissions on getList action
 *
 * @param $params
 * @return mixed
 */
function civicrm_api3_civi_mobile_participant_getList($params) {
  require_once Civi::paths()->getPath("[civicrm.root]/api/v3/Generic/Getlist.php");
  $params['check_permissions'] = false;
  $apiRequest = [
    'version' => 3,
    'entity' => 'CiviMobileParticipant',
    'action' => 'getlist',
    'params' => $params,
  ];
  return civicrm_api3_generic_getList($apiRequest);
}
