<?php
/**
 * CiviRuleAction.Cleanup API
 *
 * @param array $params
 * @return array API result descriptor
 * @see civicrm_api3_create_success
 * @see civicrm_api3_create_error
 * @throws API_Exception
 */
function civicrm_api3_civi_rule_action_cleanup($params) {
  \Civi::log()->debug('params: ' . print_r($params,TRUE));

  // Get all the civirule actions
  $civiruleactions = civicrm_api3('CiviRuleAction', 'get', [
    'options' => ['limit' => 0, 'sort' => 'id ASC'],
  ]);
  $listOfActionsByName = [];
  foreach ($civiruleactions['values'] as $actionID => $actionDetail) {
    if (!isset($listOfActionsByName[$actionDetail['name']])) {
      $listOfActionsByName[$actionDetail['name']] = $actionDetail;
    }
    else {
      $listOfActionsByName[$actionDetail['name']]['duplicateID'] = $actionID;
    }
  }
  $listToMerge = [];
  foreach ($listOfActionsByName as $name => $detail) {
    if (!isset($detail['duplicateID'])) {
      continue;
    }
    $listToMerge[$detail['id']] = $detail['duplicateID'];
  }
  foreach ($listToMerge as $originalID => $duplicateID) {
    if (!$params['dry_run']) {
      $query = 'UPDATE civirule_rule_action SET action_id = %1 WHERE action_id = %2';
      CRM_Core_DAO::executeQuery($query, [1 => [$originalID, 'Positive'], 2 => [$duplicateID, 'Positive']]);
      $deleteQuery = 'DELETE FROM civirule_action WHERE id = %1';
      CRM_Core_DAO::executeQuery($deleteQuery, [1 => [$duplicateID, 'Positive']]);
    }
  }

  if ($params['dry_run']) {
    \Civi::log()->debug('CiviRuleaction.cleanup dry_run: would have merged: ' . print_r($listToMerge, TRUE));
  }

  return civicrm_api3_create_success($listToMerge, $params);
}

function _civicrm_api3_civi_rule_action_cleanup_spec(&$spec) {
  $spec['dry_run'] = [
    'title' => 'Dry run (do not actually make any changes)',
    'type' => CRM_Utils_Type::T_BOOLEAN,
    'api.default' => TRUE
  ];
}

