<?php
// phpcs:disable
use CRM_Timeseriesreporting_ExtensionUtil as E;
// phpcs:enable

/**
 * Collection of upgrade steps.
 */
class CRM_Timeseriesreporting_Upgrader extends CRM_Extension_Upgrader_Base {

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
   */
  // public function postInstall(): void {
  //  $customFieldId = civicrm_api3('CustomField', 'getvalue', array(
  //    'return' => array("id"),
  //    'name' => "customFieldCreatedViaManagedHook",
  //  ));
  //  civicrm_api3('Setting', 'create', array(
  //    'myWeirdFieldSetting' => array('id' => $customFieldId, 'weirdness' => 1),
  //  ));
  // }

  /**
   * Example: Run an external SQL script when the module is uninstalled.
   *
   * Note that if a file is present sql\auto_uninstall that will run regardless of this hook.
   */
  // public function uninstall(): void {
  //   $this->executeSqlFile('sql/my_uninstall.sql');
  // }

  /**
   * Add custom field sets for newly created activity types upon extension installation
   */
//   public function enable(): void {
//   }

  /**
   * Example: Run a simple query when a module is disabled.
   * @throws Exception
   */
  // public function disable(): void {
  //   CRM_Core_DAO::executeQuery('UPDATE foo SET is_active = 0 WHERE bar = "whiz"');
  // }

  /*
   * Attach custom fields
   */
  public function upgrade_4100(): bool {
    $this->ctx->log->info("Applying update 4100: Attach custom fields to Option Values");
    $entities = [
      'OptionValue_Contact Changed' => 'Contact Changed',
//       'OptionValue_Property Changed' => 'Property Changed',
//       'OptionValue_Unit Changed' => 'Unit Changed',
    ];
    // SQL QUERY: SELECT * FROM 'civicrm_custom_group' WHERE 'extends' LIKE 'Individual' OR 'extends' LIKE 'Organization'
    $customGroups = \Civi\Api4\CustomGroup::get(checkPermissions: FALSE)
      ->addClause('OR', ['extends', '=', 'Individual'], ['extends', '=', 'Organization'])
      ->execute();
    // for loop here so easily extendable, just add Activity to array
    foreach ($customGroups as $customGroup) {
      foreach ($entities as $managedEntityName => $optionValueName) {
        $currentEntity = \Civi\Api4\OptionValue::get(checkPermissions: FALSE)
          ->addWhere('option_group_id:name', '=', 'activity_type')
          ->addWhere('name', '=', $optionValueName)
          ->execute()->first();
        if (!empty($currentEntity)) {
          // we need to do this because name and table_name must be unique
          $newGroupName = $customGroup['name'] . "_Report";
          $newGroupTableName = $customGroup['table_name'] . "_Report";
          // Insert custom field set (group) and extend it to the current activity
          $newGroup = \Civi\Api4\CustomGroup::create(checkPermissions: FALSE)
            ->addValue('name', $newGroupName)
            ->addValue('title', $customGroup['title'])
            ->addValue('extends', 'Activity')
            ->addValue('extends_entity_column_value', $currentEntity['value']) // attach to Activity
            ->addValue('style', $customGroup['style'])
            ->addValue('collapse_display', $customGroup['collapse_display'])
            ->addValue('help_pre', $customGroup['help_pre'])
            ->addValue('help_post', $customGroup['help_post'])
            ->addValue('is_multiple', $customGroup['is_multiple'])
            ->addValue('min_multiple', $customGroup['min_multiple'])
            ->addValue('max_multiple', $customGroup['max_multiple'])
            ->addValue('collapse_adv_display', $customGroup['collapse_adv_display'])
            ->addValue('is_reserved', $customGroup['is_reserved'])
            ->addValue('is_public', $customGroup['is_public'])
            ->addValue('is_active', $customGroup['is_active'])
            ->execute()->first();
          // create TsrGroupRefs to link $customGroup and $newGroup
          \Civi\Api4\TsrGroupRefs::create(FALSE)
            ->addValue('original_custom_group', $customGroup['id'])
            ->addValue('tsr_custom_group', $newGroup['id'])
            ->execute();
          // Retrieve custom fields originally linked to $customGroup and create a copy to link to $newGroup
          $customFields = \Civi\Api4\CustomField::get()
            ->addWhere('custom_group_id', '=', $customGroup['id'])
            ->execute();
          foreach ($customFields as $customField) {
            // create copy of custom field to link to this Activity
            $results = \Civi\Api4\CustomField::create()
              ->addValue('custom_group_id', $newGroup['id']) // attach to group
              ->addValue('name', $customField['name'])
              ->addValue('label', $customField['label'])
              ->addValue('data_type', $customField['data_type'])
              ->addValue('html_type', $customField['html_type'])
              ->addValue('default_value', $customField['default_value'])
              ->addValue('is_required', $customField['is_required'])
              ->addValue('is_search_range', $customField['is_search_range'])
              ->addValue('help_pre', $customField['help_pre'])
              ->addValue('help_post', $customField['help_post'])
              ->addValue('attributes', $customField['attributes'])
              ->addValue('is_active', $customField['is_active'])
              ->addValue('is_view', $customField['is_view'])
              ->addValue('options_per_line', $customField['options_per_line'])
              ->addValue('text_length', $customField['text_length'])
              ->addValue('start_date_years', $customField['start_date_years'])
              ->addValue('end_date_years', $customField['end_date_years'])
              ->addValue('date_format', $customField['date_format'])
              ->addValue('time_format', $customField['time_format'])
              ->addValue('note_columns', $customField['note_columns'])
              ->addValue('note_rows', $customField['note_rows'])
              ->addValue('column_name', $customField['column_name'])
              ->addValue('option_group_id', $currentEntity['option_group_id'])
              ->addValue('serialize', $customField['serialize'])
              ->addValue('filter', $customField['filter'])
              ->addValue('in_selector', $customField['in_selector'])
              ->execute();
          }
        }
      }
    }
    CRM_Core_Invoke::rebuildMenuAndCaches(TRUE);
    return TRUE;
  }
  /**
   * Example: Run a couple simple queries.
   *
   * @return TRUE on success
   * @throws CRM_Core_Exception
   */
  // public function upgrade_4200(): bool {
  //   $this->ctx->log->info('Applying update 4200');
  //   CRM_Core_DAO::executeQuery('UPDATE foo SET bar = "whiz"');
  //   CRM_Core_DAO::executeQuery('DELETE FROM bang WHERE willy = wonka(2)');
  //   return TRUE;
  // }

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
