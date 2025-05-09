<?php
// phpcs:disable
use CRM_Biasync_ExtensionUtil as E;
// phpcs:enable

/**
 * Collection of upgrade steps.
 */
class CRM_Biasync_Upgrader extends CRM_Extension_Upgrader_Base {

  // By convention, functions that look like "function upgrade_NNNN()" are
  // upgrade tasks. They are executed in order (like Drupal's hook_update_N).

  /**
   * Example: Run an external SQL script when the module is installed.
   *
   * Note that if a file is present sql\auto_install that will run regardless of this hook.
   */

  public function postInstall(): void {
    // $contactIds = \Civi\Api4\Contact::get(TRUE)
    // ->addSelect('id')
    // ->addWhere('Is_Synced_Contacts.is_synced', 'IS NULL')
    // ->execute();

    // $results = \Civi\Api4\Contact::update(TRUE)
    //   ->addValue('Is_Synced_Contacts.is_synced', 0)
    //   ->addWhere('id', 'IN', $contactIds)
    //   ->execute();
    $contactSync = \Civi\Api4\CustomGroup::get(TRUE)
      ->addWhere('name', '=', 'Is_Synced_Contacts')
      ->addSelect('table_name')
      ->execute();
    $contactSyncTable = $contactSync[0]['table_name'];

    $activitySync = \Civi\Api4\CustomGroup::get(TRUE)
      ->addWhere('name', '=', 'Is_Synced_Activities')
      ->addSelect('table_name')
      ->execute();
    $activitySyncTable = $activitySync[0]['table_name'];

    // Create temporary tables for contacts and activities
    CRM_Core_DAO::executeQuery("CREATE TEMPORARY TABLE contacts_to_insert_update SELECT id from civicrm_contact");
    CRM_Core_DAO::executeQuery("CREATE TEMPORARY TABLE activities_to_insert_update SELECT id from civicrm_activity");

    // Set is_synced to false for contacts
    CRM_Core_DAO::executeQuery("INSERT IGNORE INTO $contactSyncTable (entity_id, is_synced) SELECT id, 0 FROM contacts_to_insert_update");
    CRM_Core_DAO::executeQuery("UPDATE $contactSyncTable ct INNER JOIN contacts_to_insert_update cu ON cu.id = ct.entity_id SET ct.is_synced = 0");

    // Set is_synced to false for activities
    CRM_Core_DAO::executeQuery("INSERT IGNORE INTO $activitySyncTable (entity_id, is_synced) SELECT id, 0 FROM activities_to_insert_update");
    CRM_Core_DAO::executeQuery("UPDATE $activitySyncTable ct INNER JOIN activities_to_insert_update cu ON cu.id = ct.entity_id SET ct.is_synced = 0");

  }

  public function upgrade_1001(): bool {
    $tableCheck = CRM_Core_DAO::singleValueQuery("SHOW TABLES LIKE 'civicrm_property_log'");
    if (!$tableCheck) {
      CRM_Core_DAO::executeQuery("CREATE TABLE `civicrm_property_log` (
        `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Unique PropertyLog ID',
        `property_id` int unsigned NOT NULL COMMENT 'Unique Property ID',
        `is_synced` tinyint NOT NULL DEFAULT 0 COMMENT 'Has property been synced?',
        PRIMARY KEY (`id`),
        CONSTRAINT FK_civicrm_property_log_property_id FOREIGN KEY (`property_id`) REFERENCES `civicrm_property`(`id`) ON DELETE CASCADE
      )
      ENGINE=InnoDB");
    }
    return TRUE;
  }

  public function upgrade_1002(): bool {
    $contactSync = \Civi\Api4\CustomGroup::get(TRUE)
      ->addWhere('name', '=', 'Is_Synced_Contacts')
      ->addSelect('table_name')
      ->execute();
    $contactSyncTable = $contactSync[0]['table_name'];

    $activitySync = \Civi\Api4\CustomGroup::get(TRUE)
      ->addWhere('name', '=', 'Is_Synced_Activities')
      ->addSelect('table_name')
      ->execute();
    $activitySyncTable = $activitySync[0]['table_name'];

    // Create temporary tables for contacts and activities
    CRM_Core_DAO::executeQuery("CREATE TEMPORARY TABLE contacts_to_insert_update SELECT id from civicrm_contact");
    CRM_Core_DAO::executeQuery("CREATE TEMPORARY TABLE activities_to_insert_update SELECT id from civicrm_activity");

    // Set is_synced to false for contacts
    CRM_Core_DAO::executeQuery("INSERT IGNORE INTO $contactSyncTable (entity_id, is_synced) SELECT id, 0 FROM contacts_to_insert_update");
    CRM_Core_DAO::executeQuery("UPDATE $contactSyncTable ct INNER JOIN contacts_to_insert_update cu ON cu.id = ct.entity_id SET ct.is_synced = 0");

    // Set is_synced to false for activities
    CRM_Core_DAO::executeQuery("INSERT IGNORE INTO $activitySyncTable (entity_id, is_synced) SELECT id, 0 FROM activities_to_insert_update");
    CRM_Core_DAO::executeQuery("UPDATE $activitySyncTable ct INNER JOIN activities_to_insert_update cu ON cu.id = ct.entity_id SET ct.is_synced = 0");

    return TRUE;
  }

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
