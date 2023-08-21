<?php
/**
 * CiviRuleCondition.Cleanup API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_civi_rule_condition_cleanup($params) {
  \Civi::log()->debug('params: ' . print_r($params,TRUE));

  // Get all the civirule conditions
  $civiruleconditions = civicrm_api3('CiviRuleCondition', 'get', [
    'options' => ['limit' => 0, 'sort' => 'id ASC'],
  ]);
  $listOfConditionsByName = [];
  foreach ($civiruleconditions['values'] as $conditionID => $conditionDetail) {
    if (!isset($listOfConditionsByName[$conditionDetail['name']])) {
      $listOfConditionsByName[$conditionDetail['name']] = $conditionDetail;
    }
    else {
      $listOfConditionsByName[$conditionDetail['name']]['duplicateID'] = $conditionID;
    }
  }
  $listToMerge = [];
  foreach ($listOfConditionsByName as $name => $detail) {
    if (!isset($detail['duplicateID'])) {
      continue;
    }
    $listToMerge[$detail['id']] = $detail['duplicateID'];
  }
  foreach ($listToMerge as $originalID => $duplicateID) {
    if (!$params['dry_run']) {
      $query = 'UPDATE civirule_rule_condition SET condition_id = %1 WHERE condition_id = %2';
      CRM_Core_DAO::executeQuery($query, [1 => [$originalID, 'Positive'], 2 => [$duplicateID, 'Positive']]);
      $deleteQuery = 'DELETE FROM civirule_condition WHERE id = %1';
      CRM_Core_DAO::executeQuery($deleteQuery, [1 => [$duplicateID, 'Positive']]);
    }
  }

  if ($params['dry_run']) {
    \Civi::log()->debug('CiviRulecondition.cleanup dry_run: would have merged: ' . print_r($listToMerge, TRUE));
  }

  return civicrm_api3_create_success($listToMerge, $params);
}

function _civicrm_api3_civi_rule_condition_cleanup_spec(&$spec) {
  $spec['dry_run'] = [
    'title' => 'Dry run (do not actually make any changes)',
    'type' => CRM_Utils_Type::T_BOOLEAN,
    'api.default' => TRUE
  ];
}

