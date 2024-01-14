<?php

function civicrm_api3_property_log_create($params) {
  return _civicrm_api3_basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params, 'PropertyLog');
}

/**
 * Returns array of PropertyLogs.
 *
 * @param array $params
 *
 * @return array
 *   Array of matching property_logs
 */
function civicrm_api3_property_log_get($params) {
  return _civicrm_api3_basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}
