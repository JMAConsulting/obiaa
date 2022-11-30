<?php
use CRM_Biaproperty_ExtensionUtil as E;

/**
 * Collection of upgrade steps.
 */
class CRM_Biaproperty_Upgrader extends CRM_Biaproperty_Upgrader_Base {

  // By convention, functions that look like "function upgrade_NNNN()" are
  // upgrade tasks. They are executed in order (like Drupal's hook_update_N).

  /**
   * Example: Run an external SQL script when the module is installed.
   */
  //public function install() {
  //  $this->executeSqlFile('sql/myinstall.sql');
  //}

  /**
   * Example: Work with entities usually not available during the install step.
   *
   * This method can be used for any post-install tasks. For example, if a step
   * of your installation depends on accessing an entity that is itself
   * created during the installation (e.g., a setting or a managed entity), do
   * so here to avoid order of operation problems.
   */
  // public function postInstall() {
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
   */
  // public function uninstall() {
  //  $this->executeSqlFile('sql/myuninstall.sql');
  // }

  /**
   * Example: Run a simple query when a module is enabled.
   */
  // public function enable() {
  //  CRM_Core_DAO::executeQuery('UPDATE foo SET is_active = 1 WHERE bar = "whiz"');
  // }

  /**
   * Example: Run a simple query when a module is disabled.
   */
  // public function disable() {
  //   CRM_Core_DAO::executeQuery('UPDATE foo SET is_active = 0 WHERE bar = "whiz"');
  // }

  /**
   * Example: Run a couple simple queries.
   *
   * @return TRUE on success
   * @throws Exception
   */
  public function upgrade_1100(): bool {
    $this->ctx->log->info('Applying update 1100 modify schema as per revised discussion');
    CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_property ADD COLUMN property_address varchar(255) COMMENT 'Property Tax Roll Address'");
    CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_property ADD UNIQUE INDEX `UI_property_address`(`property_address`)");
    CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_property ADD COLUMN city varchar(64) COMMENT 'City this property is in'");
    CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_property ADD COLUMN postal_code varchar(64) COMMENT 'postal code this property is in'");
    CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_property ADD COLUMN name varchar(255) COMMENT 'Property Name'");
    CRM_Core_DAO::executeQuery("UPDATE civicrm_property cp
      INNER JOIN civicrm_address ca ON ca.id = cp.address_id
      SET cp.name = ca.name, cp.property_address = ca.street_address, cp.city = ca.city, cp.postal_code = ca.postal_code");
    CRM_Core_BAO_SchemaHandler::dropIndexIfExists('civicrm_property', 'UI_address_id');
    CRM_Core_BAO_SchemaHandler::dropIndexIfExists('civicrm_unit_business', 'UI_business_id');
    CRM_Core_BAO_SchemaHandler::dropIndexIfExists('civicrm_unit_business', 'UI_property_unit_key');
    CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_unit_business ADD UNIQUE INDEX `UI_property_unit_key` (`property_id`, `unit_id`, `business_id`)");
    CRM_Core_BAO_SchemaHandler::dropColumn('civicrm_property', 'address_id');
    CRM_Core_BAO_SchemaHandler::dropColumn('civicrm_unit', 'unit_no');
    CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_unit ADD COLUMN address_id int unsigned DEFAULT NULL");
    CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_unit ADD CONSTRAINT `FK_civicrm_unit_address_id` FOREIGN KEY `address_id` REFERENCES `civicrm_address`(`id`) ON DELETE CASCADE");
    CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_unit ADD UNIQUE INDEX `UI_address_id` (`adddress_id`)");
    return TRUE;
  }

  public function upgrade_1200(): bool {
    $this->ctx->log->info('Applying update 1200 : drop civicrm_unit_business.property_id and add civicrm_unit.property_id');
    CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_unit ADD COLUMN property_id int unsigned NOT NULL COMMENT 'Property ID'");
    CRM_Core_DAO::executeQuery("UPDATE civicrm_unit cu INNER JOIN civicrm_property_unit up ON up.unit_id = cu.id SET cu.property_id = up.property_id");
    CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_unit ADD CONSTRAINT `FK_civicrm_unit_property_id` FOREIGN KEY `property_id` REFERENCES `civicrm_property`(`id`) ON DELETE CASCADE");
    CRM_Core_DAO::executeQuery("SET FOREIGN_KEY_CHECKS = 0");
    CRM_Core_DAO::executeQuery("ALTER TABLE `civicrm_unit_business` DROP INDEX `UI_property_unit_key`");
    CRM_Core_DAO::executeQuery("ALTER TABLE `civicrm_unit_business` DROP `property_id`");
    CRM_Core_DAO::executeQuery("ALTER TABLE `civicrm_unit_business` ADD UNIQUE `UI_unit_business_key` (`unit_id`, `business_id`)");
    CRM_Core_DAO::executeQuery("DROP TABLE civicrm_property_unit");
    CRM_Core_DAO::executeQuery("SET FOREIGN_KEY_CHECKS = 1");
    return TRUE;
  }

  public function upgrade_1300(): bool {
    $this->ctx->log->info('Applying Update 1300 : Fixing is_voter on property owner field');
    CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_property_owner CHANGE `is_voter` `is_voter` tinyint DEFAULT 0 COMMENT 'Is Vote?'");
    return TRUE;
  }

  public function upgrade_1400(): bool {
    $this->ctx->log->info('Applying update 1400: Set street_unit to null if the contents were \'sole property\'');
    CRM_Core_DAO::executeQuery("Update civicrm_address SET street_unit = NULL WHERE street_unit = 'sole property'");
    return TRUE;
  }

  public function upgrade_1500(): bool {
    $this->ctx->log->info('Applying update 1500: Set civicrm_unit_business.business_id and civicrm_unit.address_id NULL on delete');
    CRM_Core_DAO::executeQuery("SET FOREIGN_KEY_CHECKS = 0");
    CRM_Core_DAO::executeQuery("ALTER TABLE `civicrm_unit_business` DROP FOREIGN KEY `FK_civicrm_unit_business_business_id`");
    CRM_Core_DAO::executeQuery("ALTER TABLE `civicrm_unit_business` ADD CONSTRAINT FOREIGN KEY (`business_id`) REFERENCES `civicrm_contact`(`id`) ON DELETE SET NULL");
    CRM_Core_DAO::executeQuery("ALTER TABLE `civicrm_unit` DROP FOREIGN KEY `FK_civicrm_unit_address_id`");
    CRM_Core_DAO::executeQuery("ALTER TABLE `civicrm_unit` ADD CONSTRAINT `FK_civicrm_unit_address_id` FOREIGN KEY (`address_id`) REFERENCES `civicrm_address`(`id`) ON DELETE SET NULL");
    CRM_Core_DAO::executeQuery("SET FOREIGN_KEY_CHECKS = 1");
    return TRUE;
  }

  public function upgrade_1600(): bool {
    $this->ctx->log->info('Applying update 1600: Add in fields to handle sync to central bia');
    CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_unit ADD COLUMN source_record_id varchar(512) DEFAULT NULL COMMENT 'Field used to handle sync processes'");
    CRM_CORE_DAO::executeQuery("ALTER TABLE civicrm_unit ADD UNIQUE INDEX UI_source_record_id (source_record_id)");
    CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_property ADD COLUMN source_record_id varchar (512) DEFAULT NULL COMMENT 'Field used to handle sync processes'");
    CRM_CORE_DAO::executeQuery("ALTER TABLE civicrm_property ADD UNIQUE INDEX UI_source_record_id (source_record_id)");
    return TRUE;
  }

  public function upgrade_1700(): bool {
    $this->ctx->log->info('Applying update 1700: Add in fields to handle sync to central bia');
    CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_unit DROP INDEX `UI_source_record_id`");
    CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_unit ADD COLUMN source_record varchar(512) DEFAULT NULL COMMENT 'Field used to handle sync processes'");
    $units = CRM_Core_DAO::executeQuery("SELECT id, source_record_id FROM civicrm_unit");
    while ($units->fetch()) {
      if (empty($units->source_record_id)) {
        continue;
      }
      $parts = explode('-', $units->source_record_id);
      CRM_Core_DAO::executeQuery("UPDATE civicrm_unit SET source_record_id = %1, source_record = %2 WHERE id = %3", [
        1 => [trim($parts[0]), 'Positive'],
        2 => [trim($parts[1]), 'String'],
        3 => [$units->id, 'Positive'],
      ]);
    }
    CRM_CORE_DAO::executeQuery("ALTER TABLE civicrm_unit ADD UNIQUE INDEX UI_source_record_id (source_record_id, source_record)");
    CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_property DROP INDEX `UI_source_record_id`");
    CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_property ADD COLUMN source_record varchar(512) DEFAULT NULL COMMENT 'Field used to handle sync processes'");
    $properties = CRM_Core_DAO::executeQuery("SELECT id, source_record_id FROM civicrm_property");
    while ($properties->fetch()) {
      if (empty($properties->source_record_id)) {
        continue;
      }
      $parts = explode('-', $properties->source_record_id);
      CRM_Core_DAO::executeQuery("UPDATE civicrm_property SET source_record_id = %1, source_record = %2 WHERE id = %3", [
        1 => [trim($parts[0]), 'Positive'],
        2 => [trim($parts[1]), 'String'],
        3 => [$properties->id, 'Positive'],
      ]);
    }
    CRM_CORE_DAO::executeQuery("ALTER TABLE civicrm_property ADD UNIQUE INDEX UI_source_record_id (source_record_id, source_record)");
    return TRUE;
  }

  public function upgrade_1800(): bool {
    $this->ctx->log->info('Applying update 1800: Fix fields to handle sync to central bia');
    CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_unit CHANGE source_record source_record varchar(255) DEFAULT NULL COMMENT 'Field used to handle sync processes'");
    CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_unit CHANGE source_record_id source_record_id int unsigned DEFAULT NULL COMMENT 'Field used to handle sync processes'");
    CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_property CHANGE source_record source_record varchar(255) DEFAULT NULL COMMENT 'Field used to handle sync processes'");
    CRM_Core_DAO::executeQuery("ALTER TABLE civicrm_property CHANGE source_record_id source_record_id int unsigned DEFAULT NULL COMMENT 'Field used to handle sync processes'");
    return TRUE;
  }

  public function upgrade_1900(): bool {
    $this->ctx->log->info('Applying update 1900: add in managed entities records for various activity types related to properties');
    $entities = [
      'OptionValue_Move Business within BIA' => 'Move Business within BIA',
      'OptionValue_Business closed' => 'Business closed',
      'OptionValue_Property sold' => 'Property sold',
      'OptionValue_Property deleted' => 'Property deleted',
      'OptionValue_Business opened' => 'Business opened',
    ];
    foreach ($entities as $managedEntityName => $optionValueName) {
      $currentEntity = \Civi\Api4\OptionValue::get(FALSE)->addWhere('option_group_id:name', '=', 'activity_type')->addWhere('name', '=', $optionValueName)->execute()->first();
      if (!empty($currentEntity)) {
        CRM_Core_DAO::executeQuery("INSERT INTO civicrm_managed (module, name, entity_type, entity_id, cleanup) VALUES ('biaproperty', %1, 'OptionValue', %2, 'unused')", [
          1 => [$managedEntityName, 'String'],
          2 => [$currentEntity['id'], 'Positive'],
        ]);
      }
    }
    return TRUE;
  }

  /**
   * Example: Run an external SQL script.
   *
   * @return TRUE on success
   * @throws Exception
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
   * @throws Exception
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
   * @throws Exception
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
  //       UPDATE civicrm_contribution SET foobar = whiz(wonky()+wanker)
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
