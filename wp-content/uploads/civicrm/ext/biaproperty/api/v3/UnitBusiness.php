<?php

/**
 * Create a unit
 * 
 * @param array $params
 * 
 * @return array
 */
function civicrm_api3_unit_business_create($params) {
  return _civicrm_api3_basic_create(_civicrm_api3_get_BAO(__FUNCTION__), $params, 'UnitBusiness');
}

/**
 * Returns array of Units.
 *
 * @param array $params
 *
 * @return array
 *   Array of matching unit_businesses
 */
function civicrm_api3_unit_business_get($params) {
  return _civicrm_api3_basic_get(_civicrm_api3_get_BAO(__FUNCTION__), $params);
}