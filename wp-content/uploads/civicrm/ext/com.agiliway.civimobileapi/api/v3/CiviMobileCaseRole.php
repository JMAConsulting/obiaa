<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

/**
 * Get get list of available case roles for case based on case type
 *
 * @param array $params
 *
 * @return array
 */
function civicrm_api3_civi_mobile_case_role_get($params) {
  $caseRoleManager = new CRM_CiviMobileAPI_Utils_CaseRole($params['case_id'], $params['contact_id']);
  $listOfRolesForCurrentCase = $caseRoleManager->getListOfRolesForCurrentCase();
  $convertedListOfRolesForCurrentCase = $caseRoleManager->convertListOfRoles($listOfRolesForCurrentCase,$params['contact_id']);

  return civicrm_api3_create_success($convertedListOfRolesForCurrentCase);
}

/**
 * Adjust Metadata for get action
 *
 * The metadata is used for setting defaults, documentation & validation
 * @param array $params array or parameters determined by getfields
 */
function _civicrm_api3_civi_mobile_case_role_get_spec(&$params) {
  $params['case_id'] = [
    'title' => 'Case ID',
    'description' => E::ts('Case ID'),
    'api.required' => 1,
    'type' => CRM_Utils_Type::T_INT,
  ];
  $params['contact_id'] = [
    'title' => 'Contact ID',
    'description' => E::ts('Contact ID'),
    'api.required' => 1,
    'type' => CRM_Utils_Type::T_INT,
  ];
}
