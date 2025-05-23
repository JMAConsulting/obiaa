<?php
// phpcs:disable
use CRM_Gcpstats_ExtensionUtil as E;
// phpcs:enable

/**
 * Collection of upgrade steps.
 */
class CRM_Gcpstats_Upgrader extends CRM_Extension_Upgrader_Base {

  // By convention, functions that look like "function upgrade_NNNN()" are
  // upgrade tasks. They are executed in order (like Drupal's hook_update_N).

  /**
   * Example: Run an external SQL script when the module is installed.
   *
   * Note that if a file is present sql\auto_install that will run regardless of this hook.
   */
  // public function install(): void {
  //   $this->executeSqlFile('sql/my_install.sql');
  // }

  /**
   * Example: Work with entities usually not available during the install step.
   *
   * This method can be used for any post-install tasks. For example, if a step
   * of your installation depends on accessing an entity that is itself
   * created during the installation (e.g., a setting or a managed entity), do
   * so here to avoid order of operation problems.
   * @throws CRM_Core_Exception
   */
   public function postInstall(): void {
    $this->upgrade_1000();
   }

  /**
   * Example: Run an external SQL script when the module is uninstalled.
   *
   * Note that if a file is present sql\auto_uninstall that will run regardless of this hook.
   */
  // public function uninstall(): void {
  //   $this->executeSqlFile('sql/my_uninstall.sql');
  // }

  /**
   * Example: Run a simple query when a module is enabled.
   */
  // public function enable(): void {
  //  CRM_Core_DAO::executeQuery('UPDATE foo SET is_active = 1 WHERE bar = "whiz"');
  // }

  /**
   * Example: Run a simple query when a module is disabled.
   */
  // public function disable(): void {
  //   CRM_Core_DAO::executeQuery('UPDATE foo SET is_active = 0 WHERE bar = "whiz"');
  // }

  /**
   * Example: Creates custom group/fields and attaches to Google Billing Information
   *
   * @return TRUE on success
   * @throws CRM_Core_Exception
   */
   public function upgrade_1000(): bool {
     $this->ctx->log->info('Applying update 1000');
     $googleBillingInfoOptionValue = \Civi\Api4\OptionValue::get(FALSE)
       ->addWhere('option_group_id:name', '=', 'activity_type')
       ->addWhere('name', '=', 'Google Billing Information')
       ->execute()->first();
     // create custom field group
     $billingInfoGroup = \Civi\Api4\CustomGroup::create(FALSE)
       ->addValue('name', 'GCP_Billing_Stats')
       ->addValue('title', 'GCP Billing Stats')
       ->addValue('extends', 'Activity')
       ->addValue('extends_entity_column_value', $googleBillingInfoOptionValue['value'])
       ->addValue('is_active', true)
       ->execute()->first();
     $billingStatsStringFields = ['Project_Id' => 'Project Id'];
     $billingStatsIntFields = ['Total_Usage' => 'Total Usage'];
     $billingStatsDateFields = ['Invoice_Month' => 'Invoice Month'];
     $billingStatsMoneyFields = ['Total_Cost' => 'Total Cost', 'Total_Credits' => 'Total Credits',
       'Avg_Cost_Per_Thousand' => 'Average Cost Per Thousand Uses'];
     foreach ($billingStatsStringFields as $billingStatsFieldName => $label) {
       \Civi\Api4\CustomField::create()
         ->addValue('custom_group_id', $billingInfoGroup['id'])
         ->addValue('name', $billingStatsFieldName)
         ->addValue('label', $label)
         ->addValue('data_type', 'String')
         ->addValue('html_type', 'Text')
         ->addValue('is_active', 1)
         ->addValue('option_group_id', $billingInfoGroup['option_group_id'])
         ->execute();
     }
     foreach ($billingStatsIntFields as $billingStatsIntFieldName => $label) {
       \Civi\Api4\CustomField::create()
         ->addValue('custom_group_id', $billingInfoGroup['id'])
         ->addValue('name', $billingStatsIntFieldName)
         ->addValue('label', $label)
         ->addValue('data_type', 'Int')
         ->addValue('html_type', 'Text')
         ->addValue('is_active', 1)
         ->addValue('option_group_id', $billingInfoGroup['option_group_id'])
         ->execute();
     }
     foreach ($billingStatsDateFields as $billingStatsDateFieldName => $label) {
       \Civi\Api4\CustomField::create()
         ->addValue('custom_group_id', $billingInfoGroup['id'])
         ->addValue('name', $billingStatsDateFieldName)
         ->addValue('label', $label)
         ->addValue('data_type', 'Date')
         ->addValue('html_type', 'Text')
         ->addValue('is_active', 1)
         ->addValue('option_group_id', $billingInfoGroup['option_group_id'])
         ->execute();
     }
     foreach ($billingStatsMoneyFields as $billingStatsMoneyFieldName => $label) {
       \Civi\Api4\CustomField::create()
         ->addValue('custom_group_id', $billingInfoGroup['id'])
         ->addValue('name', $billingStatsMoneyFieldName)
         ->addValue('label', $label)
         ->addValue('data_type', 'Money')
         ->addValue('html_type', 'Text')
         ->addValue('is_active', 1)
         ->addValue('option_group_id', $billingInfoGroup['option_group_id'])
         ->execute();
     }
     return TRUE;
   }

  /**
   * Example: Run an external SQL script.
   *
   * @return TRUE on success
   * @throws CRM_Core_Exception
   */
  // public function upgrade_4201(): bool {
  //   $this->ctx->log->info('Applying update 4201');
  //   // this path is relative to the extension base dir
  //   $this->executeSqlFile('sql/upgrade_4201.sql');
  //   return TRUE;
  // }

  /**
   * Example: Run a slow upgrade process by breaking it up into smaller chunk.
   *
   * @return TRUE on success
   * @throws CRM_Core_Exception
   */
  // public function upgrade_4202(): bool {
  //   $this->ctx->log->info('Planning update 4202'); // PEAR Log interface

  //   $this->addTask(E::ts('Process first step'), 'processPart1', $arg1, $arg2);
  //   $this->addTask(E::ts('Process second step'), 'processPart2', $arg3, $arg4);
  //   $this->addTask(E::ts('Process second step'), 'processPart3', $arg5);
  //   return TRUE;
  // }
  // public function processPart1($arg1, $arg2) { sleep(10); return TRUE; }
  // public function processPart2($arg3, $arg4) { sleep(10); return TRUE; }
  // public function processPart3($arg5) { sleep(10); return TRUE; }

  /**
   * Example: Run an upgrade with a query that touches many (potentially
   * millions) of records by breaking it up into smaller chunks.
   *
   * @return TRUE on success
   * @throws CRM_Core_Exception
   */
  // public function upgrade_4203(): bool {
  //   $this->ctx->log->info('Planning update 4203'); // PEAR Log interface

  //   $minId = CRM_Core_DAO::singleValueQuery('SELECT coalesce(min(id),0) FROM civicrm_contribution');
  //   $maxId = CRM_Core_DAO::singleValueQuery('SELECT coalesce(max(id),0) FROM civicrm_contribution');
  //   for ($startId = $minId; $startId <= $maxId; $startId += self::BATCH_SIZE) {
  //     $endId = $startId + self::BATCH_SIZE - 1;
  //     $title = E::ts('Upgrade Batch (%1 => %2)', array(
  //       1 => $startId,
  //       2 => $endId,
  //     ));
  //     $sql = '
  //       UPDATE civicrm_contribution SET foobar = apple(banana()+durian)
  //       WHERE id BETWEEN %1 and %2
  //     ';
  //     $params = array(
  //       1 => array($startId, 'Integer'),
  //       2 => array($endId, 'Integer'),
  //     );
  //     $this->addTask($title, 'executeSql', $sql, $params);
  //   }
  //   return TRUE;
  // }

}
