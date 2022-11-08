<?php

/**
 * Create a PropertyOwner Record
 * 
 * @param array $params
 * 
 * @return array
 */
function civicrm_api3_property_owner_create($params) {
  return _civicrm_api3_basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params, 'PropertyOwner');
}

/**
 * Returns array of PropertyOwners.
 *
 * @param array $params
 *
 * @return array
 *   Array of matching property_owneres
 */
function civicrm_api3_property_owner_get($params) {
  return _civicrm_api3_basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}
