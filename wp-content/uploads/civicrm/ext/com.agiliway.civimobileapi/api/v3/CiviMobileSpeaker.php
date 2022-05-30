<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

/**
 * Get speakers
 *
 * @param array $params
 *
 * @return array
 */
function civicrm_api3_civi_mobile_speaker_get($params) {
  $result = (new CRM_CiviMobileAPI_Api_CiviMobileSpeaker_Get($params))->getResult();

  return civicrm_api3_create_success($result);
}

/**
 * Adjust Metadata for speaker_get action
 *
 * The metadata is used for setting defaults, documentation & validation
 *
 * @param array $params array or parameters determined by getfields
 */
function _civicrm_api3_civi_mobile_speaker_get_spec(&$params) {
  $params['event_id'] = [
    'title' => 'Event id',
    'description' => E::ts('Event id'),
    'type' => CRM_Utils_Type::T_INT,
    'api.required' => 1,
  ];
  $params['participant_id'] = [
    'title' => 'Participant ID',
    'description' => E::ts('Participant ID'),
    'type' => CRM_Utils_Type::T_INT,
  ];
}
