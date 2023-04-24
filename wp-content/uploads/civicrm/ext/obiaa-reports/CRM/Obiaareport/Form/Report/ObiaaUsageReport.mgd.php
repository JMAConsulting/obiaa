<?php
// This file declares a managed database record of type "ReportTemplate".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
return [
  [
    'name' => 'CRM_Obiaareport_Form_Report_ObiaaUsageReport',
    'entity' => 'ReportTemplate',
    'params' => [
      'version' => 3,
      'label' => 'Obiaa Usage Report Template',
      'description' => 'ObiaaUsageReport (obiaareport)',
      'class_name' => 'CRM_Obiaareport_Form_Report_ObiaaUsageReport',
      'report_url' => 'obiaareport/obiaausagereport',
      'component' => '',
    ],
  ],
];
