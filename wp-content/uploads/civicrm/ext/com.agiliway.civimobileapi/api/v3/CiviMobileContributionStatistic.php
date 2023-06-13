<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;
/**
 * Gets statistic for Contact's Contribution
 *
 * @param $params
 *
 * @return array
 */
function civicrm_api3_civi_mobile_contribution_statistic_get($params) {
  $listOfContactId = (new CRM_CiviMobileAPI_Utils_ContactFieldsFilter)->getListOfContributionContactsId();

  if (!empty($params['contact_id'])) {
    $statistic = (new CRM_CiviMobileAPI_Utils_Statistic_ContactsContribution)->getSingleContactContributionStatistic($params);
  } else {
    if ($params['contact_display_name'] || $params['contact_type'] || $params['contact_tags'] || $params['contact_groups']) {
      $listOfContactId = (new CRM_CiviMobileAPI_Utils_ContactFieldsFilter)->filterContacts($params);
    }

    $statistic = (new CRM_CiviMobileAPI_Utils_Statistic_ContactsContribution)->getSelectedContactsContributionStatistic($params, $listOfContactId);
  }

  if (!empty($params['contact_id'])) {
    return civicrm_api3_create_success([$params['contact_id'] => $statistic], $params);
  } else {
    return civicrm_api3_create_success([$statistic], $params);
  }
}

/**
 * Adjust Metadata for get action
 *
 * The metadata is used for setting defaults, documentation & validation
 * @param array $params array or parameters determined by getfields
 */
function _civicrm_api3_civi_mobile_contribution_statistic_get_spec(&$params) {
  $params['contact_id'] = [
    'title' => 'Contact ID',
    'description' => E::ts('Contact ID'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_INT,
  ];
  $params['receive_date'] = [
    'title' => 'Receive date',
    'description' => E::ts('Receive date'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_DATE,
  ];
}
