<?php
// This file declares a managed database record of type "ReportTemplate".
// The record will be automatically inserted, updated, or deleted from the
// database as appropriate. For more details, see "hook_civicrm_managed" at:
// https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
return [
  [
    'name' => 'CRM_Obiaareport_Form_Report_ObiaaCommercialSpaceReport',
    'entity' => 'ReportTemplate',
    'params' => [
      'version' => 3,
      'label' => 'ObiaaCommercialSpaceReport',
      'description' => 'ObiaaCommercialSpaceReport (obiaareport)',
      'class_name' => 'CRM_Obiaareport_Form_Report_ObiaaCommercialSpaceReport',
      'report_url' => 'obiaareport/obiaacommercialspacereport',
      'component' => '',
    ],
  ],
];
