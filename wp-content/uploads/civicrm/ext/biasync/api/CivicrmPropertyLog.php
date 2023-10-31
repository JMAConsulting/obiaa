<?php

function civicrm_api3_civicrm_property_log_create($params) {
  return _civicrm_api3_basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params, 'CivicrmPropertyLog');
}

/**
 * Returns array of PropertyLogs.
 *
 * @param array $params
 *
 * @return array
 *   Array of matching property_owneres
 */
function civicrm_api3_civicrm_property_log_get($params) {
  return _civicrm_api3_basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}
