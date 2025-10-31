<?php

require_once 'obiaareport.civix.php';
// phpcs:disable
use CRM_Obiaareport_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function obiaareport_civicrm_config(&$config) {
  _obiaareport_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function obiaareport_civicrm_install(): void {
  _obiaareport_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function obiaareport_civicrm_enable(): void {
  _obiaareport_civix_civicrm_enable();
}

function obiaareport_civicrm_alterReportVar($varType, &$var, $reportForm) {
  if (get_class($reportForm) == 'CRM_Report_Form_Contact_Detail') {
   $result = \Civi\Api4\CustomField::get(FALSE)
     ->addSelect('custom_group_id.table_name', 'column_name')
     ->addWhere('custom_group_id:name', '=', 'Business_Details')
     ->addWhere('name', '=', 'Open_Date')
     ->execute()->first();
   $tableName = $result['custom_group_id.table_name'];
   $columnName = $result['column_name'];
   if ($varType == 'columns' && !empty($var[$tableName])) {
     $var[$tableName]['fields']['bia_anniversary_date'] = [
       'name' => 'bia_anniversary_date',
       'title' => ts('Anniversary'),
       'type' => CRM_Utils_Type::T_INT,
       'operatoType' => CRM_Report_Form::OP_INT,
       'dbAlias' => "TIMESTAMPDIFF(YEAR, $columnName, DATE_ADD(CURDATE(), INTERVAL 1 MONTH))",
     ];
   }
  }

  if ($varType == 'sql' && !empty($var->getVar('_params')['fields']['bia_anniversary_date'])) {
    $var->_where .= " AND MONTH($columnName) = MONTH(DATE_ADD(CURDATE(), INTERVAL 1 MONTH))";
  }
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function obiaareport_civicrm_preProcess($formName, &$form): void {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
//function obiaareport_civicrm_navigationMenu(&$menu): void {
//  _obiaareport_civix_insert_navigation_menu($menu, 'Mailings', [
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ]);
//  _obiaareport_civix_navigationMenu($menu);
//}
