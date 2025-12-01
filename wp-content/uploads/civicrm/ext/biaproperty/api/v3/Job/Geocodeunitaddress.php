<?php
use CRM_Biaproperty_ExtensionUtil as E;

/**
 * Job.Geocodeunitaddress API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_job_Geocodeunitaddress_spec(&$spec) {
}

/**
 * Job.Geocodeunitaddress API
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @see civicrm_api3_create_success
 *
 * @throws CRM_Core_Exception
 */
function civicrm_api3_job_Geocodeunitaddress($params) {
  $gc = new CRM_Biaproperty_GeocodeAddress($params);

  $result = $gc->run();
  if ($result['is_error'] == 0) {
    return civicrm_api3_create_success($result['messages']);
  }
  else {
    return civicrm_api3_create_error($result['messages']);
  }
}
