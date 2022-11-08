<?php
use CRM_Biasync_ExtensionUtil as E;

/**
 * Job.Syncbia API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_job_Syncbia_spec(&$spec) {
}

/**
 * Job.Syncbia API
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @see civicrm_api3_create_success
 *
 * @throws API_Exception
 */
function civicrm_api3_job_Syncbia($params) {
  $sync = CRM_Biasync_Utils::syncToBIA();
  if (!empty($sync)) {
    return civicrm_api3_create_success($sync, $params, 'Job', 'Syncbia');
  }
  else {
    throw new API_Exception(/*error_message*/ 'Failed to sync to BIA', /*error_code*/ 'sync_error');
  }
}
