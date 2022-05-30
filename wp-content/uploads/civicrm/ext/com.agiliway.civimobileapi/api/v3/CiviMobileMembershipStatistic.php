<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

/**
 * Gets statistic for Contact's Membership
 *
 * @param $params
 * @return array
 */
function civicrm_api3_civi_mobile_membership_statistic_get($params) {
  $params['is_membership'] = 1;
  $listOfContactId = CRM_CiviMobileAPI_Utils_Statistic_Utils::getListOfMembershipContactIds();

  if ($params['contact_display_name'] || $params['contact_type']) {
    $listOfContactId = (new CRM_CiviMobileAPI_Utils_ContactFieldsFilter)->filterContacts($params);
  } elseif ($params['contact_tags'] || $params['contact_groups']) {
    $listOfContactId = (new CRM_CiviMobileAPI_Utils_ContactFieldsFilter)->filterContacts($params);
  }

  $newMembershipsCount = CRM_CiviMobileAPI_Utils_Statistic_ContactsMembership::getMembershipsCount($listOfContactId, $params, "New");
  $currentMembershipsCount =  CRM_CiviMobileAPI_Utils_Statistic_ContactsMembership::getMembershipsCount($listOfContactId, $params, "Current");
  $renewalMembershipsCount = CRM_CiviMobileAPI_Utils_Statistic_ContactsMembership::getMembershipsCount($listOfContactId, $params, NULL, TRUE);
  $preparedReceiveDate = (new CRM_CiviMobileAPI_Utils_Statistic_ChartBar())->getPrepareReceiveDate($params);

  $statistics = [
    'new' => $newMembershipsCount,
    'current' => $currentMembershipsCount,
    'renew' => $renewalMembershipsCount,
    'period' => (new CRM_CiviMobileAPI_Utils_Statistic_ChartBar())->findPeriod($preparedReceiveDate['start_date'], $preparedReceiveDate['end_date']),
    'chart_bar' => (new CRM_CiviMobileAPI_Utils_Statistic_ChartBar)->periodDivide($listOfContactId, $params)
  ];

  return civicrm_api3_create_success([$statistics], $params);
}

/**
 * Adjust Metadata for get action
 *
 * The metadata is used for setting defaults, documentation & validation
 * @param array $params array or parameters determined by getfields
 */
function _civicrm_api3_civi_mobile_membership_statistic_get_spec(&$params) {
  $params['contact_display_name'] = [
    'title' => 'Contact display name',
    'description' => E::ts('Contact display name'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_STRING
  ];
  $params['contact_type'] = [
    'title' => 'Contact type',
    'description' => E::ts('Contact type'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_STRING
  ];
  $params['contact_tags'] = [
    'title' => 'Contact tags',
    'description' => E::ts('Contact tags'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_INT
  ];
  $params['contact_groups'] = [
    'title' => 'Contact groups',
    'description' => E::ts('Contact groups'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_INT
  ];
  $params['receive_date'] = [
    'title' => 'Receive date',
    'description' => E::ts('Receive date'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_STRING
  ];
  $params['membership_type_id'] = [
    'title' => 'Membership type',
    'description' => E::ts('Membership type'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_STRING
  ];
}
