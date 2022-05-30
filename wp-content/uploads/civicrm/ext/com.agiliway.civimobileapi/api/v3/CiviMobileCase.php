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
    $summary[] = ['status' => $value['label'],'amount' => (new CRM_CiviMobileAPI_Utils_CaseSummary)->getCountOfCases($value['value'], $params), 'id' => $id++];
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
  try {
    $case = civicrm_api3('Case', 'create', $params);
  } catch (CiviCRM_API3_Exception $e) {
    $case = [];
  }

  if (!empty($case)) {
    $caseId = $case['values'][$case['id']]['id'];

    $contactId = CRM_Case_BAO_Case::getCaseClients($caseId)[0];
    unset($params['status_id']);

    $activities = CRM_Case_BAO_Case::getCaseActivity($caseId, $params, $contactId);

    $activitiesId = [];
    foreach ($activities['data'] as $activity) {
      $activitiesId[$activity['DT_RowId']] = $activity['status_id'];
    }

    $mainActivityId = NULL;
    foreach ($activitiesId as $activityId => $activityStatus) {
      try {
        civicrm_api3('Activity', 'create', [
          'source_contact_id' => $contactId,
          'id' => $activityId,
          'activity_date_time' => $params['start_date'],
        ]);
      } catch (CiviCRM_API3_Exception $e) {
        continue;
      }

      if ($activityStatus == 'Completed') {
        $mainActivityId = $activityId;
      }
    }
  }

  if (isset($_FILES['file'])) {
    try {
      civicrm_api3('Attachment', 'create', [
        'name' => $_FILES['file']["name"],
        'mime_type' => $_FILES['file']["type"],
        'entity_id' => $mainActivityId,
        'entity_table' => "civicrm_activity",
        'url' => $_FILES['file']["tmp_name"],
        'path' => $_FILES['file']["tmp_name"],
        'upload_date' => date('Y-m-d H:i:s'),
        'options' => [
          'move-file' => $_FILES['file']['tmp_name']
        ]
      ]);
    } catch (CiviCRM_API3_Exception $e) {
      Civi::log()->warning("File not uploaded.");
    }
  }

  return civicrm_api3_create_success($case['values']);
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
  $params['status_id'] = [
    'title' => 'Case status',
    'description' => E::ts('Case status'),
    'api.required' => 0,
    'type' => CRM_Utils_Type::T_STRING
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
