<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

/**
 * Gets summary for cases
 *
 * @param $params
 * @return array
 */
function civicrm_api3_civi_mobile_case_get($params) {
  $allStatuses = civicrm_api3('OptionValue', 'get', [
    'sequential' => 1,
    'option_group_id' => 'case_status',
  ]);

  $id = 1;
  foreach ($allStatuses['values'] as $value) {
    $summary[] = ['status' => $value['label'], 'amount' => (new CRM_CiviMobileAPI_Utils_CaseSummary)->getCountOfCases($value['value'], $params), 'id' => $id++];
  }

  return civicrm_api3_create_success($summary, $params);
}

/**
 * Create new case
 *
 * @param $params
 * @return array
 */
function civicrm_api3_civi_mobile_case_create($params) {
  $params['id'] = $params['case_id'];

  if (empty($params['id'])) {
    $caseObj = CRM_Case_BAO_Case::create($params);

    civicrm_api3('Case', 'create', [
      'id' => $caseObj->id,
      'status_id' => $params['status_id'],
    ]);

    $form = new CRM_Case_Form_Case();
    $form->_context = 'standalone';
    $form->_activityTypeFile = 'OpenCase';
    $form->_currentUserId = CRM_Core_Session::singleton()->get('userID');
    $form->_currentlyViewedContactId = $params['contact_id'];

    $params['reset_date_time'] = $params['start_date'];

    $form->_caseId = $params['case_id'] = $caseObj->id;
    $form->case_type = $params['case_type'] = CRM_Core_DAO::getFieldValue('CRM_Case_DAO_CaseType', $params['case_type_id'], 'name', 'id');;
    $form->_allowMultiClient = true;

    CRM_CiviMobileAPI_Utils_CreateCase::runCreateCase($form, $params);

    return ['message' => $params['statusMsg']];
  } else {
    $caseId = $params['id'];

    if ($params['reassign_contact_id']) {
      $reassign_id = CRM_Case_BAO_Case::mergeCases($params['reassign_contact_id'], $params['id'], $params['contact_id'], NULL, TRUE);
    }
    civicrm_api3('Case', 'create', [
      'contact_id' => $params['contact_id'],
      'id' => $params['id'],
      'subject' => $params['subject'],
      'case_type_id' => $params['case_type_id'],
      'status_id' => $params['status_id'],
      'details' => $params['details'],
    ]);

    if (!empty($params['case_type_id'])) {
      $contactId = CRM_Case_BAO_Case::getCaseClients($caseId)[0];
      unset($params['status_id']);

      $activities = CRM_Case_BAO_Case::getCaseActivity($caseId, $params, $contactId);

      $activitiesId = [];
      $activity = [];
      foreach ($activities['data'] as $activity) {
        $activitiesId[$activity['DT_RowId']] = $activity['status_id'];
        $activity[] = $activity['value'];
      }

      $form = new CRM_Core_Form();
      $form->_caseId[0] = $caseId;
      $form->_currentUserId = CRM_Core_Session::singleton()->get('userID');
      $form->_currentlyViewedContactId = $params['contact_id'];
      $params['is_reset_timeline'] = 1;
      $params['reset_date_time'] = $params['start_date'];
      CRM_Case_Form_Activity_ChangeCaseType::beginPostProcess($form, $params);
      CRM_Case_Form_Activity_ChangeCaseType::endPostProcess($form, $params, $activity);
    }

    if ($params['reassign_contact_id']) {
      $newReassign = implode(',', $reassign_id);
      return ['updated_case_id' => $newReassign];
    }

    return ['updated_case_id' => $params['id']];
  }
}

/**
 * Adjust Metadata for create action
 *
 * The metadata is used for setting defaults, documentation & validation
 * @param array $params array or parameters determined by getfields
 */
function _civicrm_api3_civi_mobile_case_create_spec(&$params) {
  $params['id'] = [
    'title' => 'Case Id',
    'description' => E::ts('Case Id'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_INT
  ];
  $params['reassign_contact_id'] = [
    'title' => 'Reassign Contact Id',
    'description' => E::ts('Reassign Contact Id'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_INT
  ];
  $params['status_id'] = [
    'title' => 'Case status',
    'description' => E::ts('Case status'),
    'api.required' => 0,
  ];
  $params['case_type_id'] = [
    'title' => 'Case type',
    'description' => E::ts('Case type'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_INT
  ];
  $params['contact_id'] = [
    'title' => 'Case client',
    'description' => E::ts('Case client'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_INT
  ];
  $params['subject'] = [
    'title' => 'Case subject',
    'description' => E::ts('Case subject'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_STRING
  ];
  $params['start_date'] = [
    'title' => 'Start date',
    'description' => E::ts('Start date'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_STRING
  ];
  $params['end_date'] = [
    'title' => 'End date',
    'description' => E::ts('End date'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_STRING
  ];
  $params['duration'] = [
    'title' => 'Duration',
    'description' => E::ts('Duration'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_INT
  ];
  $params['medium_id'] = [
    'title' => 'Activity medium',
    'description' => E::ts('Activity medium'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_INT
  ];
  $params['creator_id'] = [
    'title' => 'Creator',
    'description' => E::ts('Creator'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_INT
  ];
  $params['details'] = [
    'title' => 'Details',
    'description' => E::ts('Details'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_STRING
  ];
}

/**
 * Adjust Metadata for get action
 *
 * The metadata is used for setting defaults, documentation & validation
 * @param array $params array or parameters determined by getfields
 */
function _civicrm_api3_civi_mobile_case_get_spec(&$params) {
  $params['activity_type'] = [
    'title' => 'Activity type',
    'description' => E::ts('Activity type'),
    'api.required' => 1,
    'type' => CRM_Utils_Type::T_INT
  ];
  $params['id'] = [
    'title' => 'Case Id',
    'description' => E::ts('Case Id'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_INT
  ];
  $params['contact_display_name'] = [
    'title' => 'Contact display name',
    'description' => E::ts('Contact display name'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_STRING
  ];
  $params['status_id'] = [
    'title' => 'Case status',
    'description' => E::ts('Case status'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_INT
  ];
  $params['case_type_id'] = [
    'title' => 'Case type',
    'description' => E::ts('Case type'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_INT
  ];
  $params['subject'] = [
    'title' => 'Case subject',
    'description' => E::ts('Case subject'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_STRING
  ];
  $params['start_date'] = [
    'title' => 'Start date',
    'description' => E::ts('Start date'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_STRING
  ];
  $params['end_date'] = [
    'title' => 'End date',
    'description' => E::ts('End date'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_STRING
  ];
}
