<?php
use CRM_Biaproperty_ExtensionUtil as E;

/**
 * Collection of upgrade steps.
 */
class CRM_Biaproperty_Upgrader extends CRM_Extension_Upgrader_Base {

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

  public function upgrade_2000(): bool {
    $this->ctx->log->info('Applying update 2000: Fix Find Businesses search for MySQL8 issue');
    CRM_Core_DAO::executeQuery('UPDATE civicrm_saved_search SET api_params = \'{"version":4,"select":["GROUP_CONCAT(DISTINCT business_id.display_name) AS GROUP_CONCAT_business_id_display_name","GROUP_CONCAT(DISTINCT UnitBusiness_Unit_unit_id_01_Unit_Address_address_id_01.street_unit) AS GROUP_CONCAT_UnitBusiness_Unit_unit_id_01_Unit_Address_address_id_01_street_unit","UnitBusiness_Contact_business_id_01.display_name","GROUP_CONCAT(DISTINCT UnitBusiness_Unit_unit_id_01_Unit_Address_address_id_01.street_address) AS GROUP_CONCAT_UnitBusiness_Unit_unit_id_01_Unit_Address_address_id_01_street_address"],"orderBy":[],"where":[],"groupBy":["unit_id","UnitBusiness_Contact_business_id_01.id"],"join":[["Unit AS UnitBusiness_Unit_unit_id_01","LEFT",["unit_id","=","UnitBusiness_Unit_unit_id_01.id"]],["Address AS UnitBusiness_Unit_unit_id_01_Unit_Address_address_id_01","LEFT",["UnitBusiness_Unit_unit_id_01.address_id","=","UnitBusiness_Unit_unit_id_01_Unit_Address_address_id_01.id"]],["Contact AS UnitBusiness_Contact_business_id_01","INNER",["business_id","=","UnitBusiness_Contact_business_id_01.id"],["UnitBusiness_Contact_business_id_01.is_deleted","=",false]]],"having":[]}\' WHERE id = 19');
    CRM_Core_DAO::executeQuery('UPDATE civicrm_search_display SET settings = \'{"actions":false,"limit":50,"classes":["table","table-striped"],"pager":{"show_count":true,"expose_limit":true},"sort":[],"columns":[{"type":"field","key":"GROUP_CONCAT_business_id_display_name","dataType":"String","label":"Business","sortable":true,"link":{"path":"civicrm\/contact\/view?cid=[UnitBusiness_Contact_business_id_01.id]","entity":"","action":"","join":"","target":""},"title":"View Business"},{"type":"field","key":"GROUP_CONCAT_UnitBusiness_Unit_unit_id_01_Unit_Address_address_id_01_street_unit","dataType":"String","label":"Address","sortable":true,"rewrite":"[GROUP_CONCAT_UnitBusiness_Unit_unit_id_01_Unit_Address_address_id_01_street_unit] - [GROUP_CONCAT_UnitBusiness_Unit_unit_id_01_Unit_Address_address_id_01_street_address]","empty_value":"[GROUP_CONCAT_UnitBusiness_Unit_unit_id_01_Unit_Address_address_id_01_street_address]"},{"text":"Actions","style":"default","size":"btn-sm","icon":"fa-bars","links":[{"path":"civicrm\/add-business?bid=[business_id]&change_title=1&uid=[unit_id]","icon":"fa-angle-double-right","text":"Move business within BIA","style":"default","condition":[],"entity":"","action":"","join":"","target":""},{"path":"civicrm\/close-business?bid=[business_id]","icon":"fa-times","text":"Close business","style":"default","condition":[],"entity":"","action":"","join":"","target":""}],"type":"menu","alignment":"text-right"}]}\' WHERE saved_search_id = 19');
    return TRUE;
  }

  public function upgrade_2100(): bool {
    $this->ctx->log->info('Applying update 2100: Fix Properties Tab for MySQL8 issue');
    CRM_Core_DAO::executeQuery('UPDATE civicrm_saved_search SET api_params = \'{"version":4,"select":["name","property_address","COUNT(DISTINCT Property_PropertyOwner_property_id_02.owner_id) AS COUNT_Property_PropertyOwner_property_id_02_owner_id","roll_no","city","postal_code","GROUP_CONCAT(DISTINCT Property_PropertyOwner_property_id_01.is_voter) AS GROUP_CONCAT_Property_PropertyOwner_property_id_01_is_voter"],"orderBy":[],"where":[],"groupBy":["id","Property_PropertyOwner_property_id_01_PropertyOwner_Contact_owner_id_01.id"],"join":[["PropertyOwner AS Property_PropertyOwner_property_id_01","LEFT",["id","=","Property_PropertyOwner_property_id_01.property_id"]],["Contact AS Property_PropertyOwner_property_id_01_PropertyOwner_Contact_owner_id_01","LEFT",["Property_PropertyOwner_property_id_01.owner_id","=","Property_PropertyOwner_property_id_01_PropertyOwner_Contact_owner_id_01.id"]],["PropertyOwner AS Property_PropertyOwner_property_id_02","LEFT",["id","=","Property_PropertyOwner_property_id_02.property_id"]]],"having":[]}\' WHERE id = 6');
    CRM_Core_DAO::executeQuery('UPDATE civicrm_search_display SET settings = \'{"actions":false,"limit":50,"classes":["table-striped"],"pager":{"show_count":true,"expose_limit":true},"sort":[["roll_no","ASC"],["name","ASC"],["property_address","ASC"]],"columns":[{"type":"field","key":"roll_no","dataType":"String","label":"Roll No","sortable":true},{"type":"field","key":"name","dataType":"String","label":"Name","sortable":true},{"type":"field","key":"property_address","dataType":"String","label":"Property Address","sortable":true},{"type":"field","key":"GROUP_CONCAT_Property_PropertyOwner_property_id_01_is_voter","dataType":"Boolean","label":"Is Voter?","sortable":true},{"type":"field","key":"city","dataType":"String","label":"City","sortable":true},{"type":"field","key":"postal_code","dataType":"String","label":"Postal Code","sortable":true},{"size":"","links":[{"entity":"","action":"","join":"","target":"","icon":"fa-eye","text":"View","style":"success","path":"civicrm\/biaunits#?pid=[id]&title=[name] - [property_address]","condition":["name","IS NOT EMPTY"]},{"entity":"","action":"","join":"","target":"","icon":"fa-eye","text":"View","style":"success","path":"civicrm\/biaunits#?pid=[id]&title=[property_address]","condition":["name","IS EMPTY"]},{"entity":"Property","action":"update","join":"","target":"crm-popup","icon":"fa-pencil","text":"Edit","style":"info","path":"","condition":[]},{"path":"civicrm\/close-property?id=[id]&cid=[Property_PropertyOwner_property_id_01_PropertyOwner_Contact_owner_id_01.id]","icon":"fa-arrow-circle-right","text":"Sell","style":"danger","condition":[],"entity":"","action":"","join":"","target":""}],"type":"buttons","alignment":"text-right"}],"button":null,"cssRules":[]}\' WHERE saved_search_id = 6');
    return TRUE;
  }

  public function upgrade_2200(): bool {
    $this->ctx->log->info('Applying update 2200: Fix WorkAddresses Saved Search for MySQL8 issue');
    CRM_Core_DAO::executeQuery('UPDATE civicrm_saved_search SET api_params = \'{"version":4,"select":["id","Unit_UnitBusiness_unit_id_01_UnitBusiness_Contact_business_id_01.display_name","Unit_UnitBusiness_unit_id_01_UnitBusiness_Contact_business_id_01.id","GROUP_CONCAT(DISTINCT Unit_Address_address_id_01.geo_code_1) AS GROUP_CONCAT_Unit_Address_address_id_01_geo_code_1","GROUP_CONCAT(DISTINCT Unit_Address_address_id_01.geo_code_2) AS GROUP_CONCAT_Unit_Address_address_id_01_geo_code_2","GROUP_CONCAT(DISTINCT Unit_Address_address_id_01.street_unit) AS GROUP_CONCAT_Unit_Address_address_id_01_street_unit","GROUP_CONCAT(DISTINCT Unit_Address_address_id_01.city) AS GROUP_CONCAT_Unit_Address_address_id_01_city","GROUP_CONCAT(DISTINCT Unit_Address_address_id_01.postal_code) AS GROUP_CONCAT_Unit_Address_address_id_01_postal_code","GROUP_CONCAT(DISTINCT Unit_Address_address_id_01.country_id:label) AS GROUP_CONCAT_Unit_Address_address_id_01_country_id_label","GROUP_CONCAT(DISTINCT Unit_Address_address_id_01_Address_StateProvince_state_province_id_01.abbreviation) AS GROUP_CONCAT_Unit_Address_address_id_01_Address_StateProvince_state_province_id_01_abbreviation","GROUP_CONCAT(DISTINCT Unit_Address_address_id_01.street_address) AS GROUP_CONCAT_Unit_Address_address_id_01_street_address"],"orderBy":[],"where":[],"groupBy":["id","Unit_UnitBusiness_unit_id_01_UnitBusiness_Contact_business_id_01.id"],"join":[["UnitBusiness AS Unit_UnitBusiness_unit_id_01","LEFT",["id","=","Unit_UnitBusiness_unit_id_01.unit_id"]],["Contact AS Unit_UnitBusiness_unit_id_01_UnitBusiness_Contact_business_id_01","LEFT",["Unit_UnitBusiness_unit_id_01.business_id","=","Unit_UnitBusiness_unit_id_01_UnitBusiness_Contact_business_id_01.id"],["Unit_UnitBusiness_unit_id_01_UnitBusiness_Contact_business_id_01.is_deleted","=",false]],["Address AS Unit_Address_address_id_01","LEFT",["address_id","=","Unit_Address_address_id_01.id"]],["StateProvince AS Unit_Address_address_id_01_Address_StateProvince_state_province_id_01","LEFT",["Unit_Address_address_id_01.state_province_id","=","Unit_Address_address_id_01_Address_StateProvince_state_province_id_01.id"]]],"having":[]}\' WHERE name = \'Businesses_new_\'');
    CRM_Core_DAO::executeQuery('UPDATE civicrm_search_display SET settings = \'{"style":"ul","limit":0,"sort":[],"pager":false,"columns":[{"type":"field","key":"GROUP_CONCAT_Unit_Address_address_id_01_street_unit","dataType":"String","link":{"path":"civicrm\/unit\/form?reset=1&action=update&context=update&id=[id]","entity":"","action":"","join":"","target":"_blank"},"rewrite":"Unit #[GROUP_CONCAT_Unit_Address_address_id_01_street_unit] [Unit_Address_address_id_01.street_address]","empty_value":"[GROUP_CONCAT_Unit_Address_address_id_01_street_address]"},{"type":"field","key":"GROUP_CONCAT_Unit_Address_address_id_01_city","dataType":"String"},{"type":"field","key":"GROUP_CONCAT_Unit_Address_address_id_01_postal_code","dataType":"String"},{"type":"field","key":"GROUP_CONCAT_Unit_Address_address_id_01_country_id_label","dataType":"Integer","rewrite":"[Unit_Address_address_id_01_Address_StateProvince_state_province_id_01.abbreviation], [GROUP_CONCAT_Unit_Address_address_id_01_country_id_label]","empty_value":"[Unit_Address_address_id_01_Address_StateProvince_state_province_id_01.abbreviation]"},{"links":[{"path":"civicrm\/contact\/map?reset=1&cid=[Unit_UnitBusiness_unit_id_01_UnitBusiness_Contact_business_id_01.id]","icon":"fa-map-marker","text":"Map","style":"default","condition":["GROUP_CONCAT_Unit_Address_address_id_01_geo_code_1","IS NOT EMPTY"],"entity":"","action":"","join":"","target":""}],"type":"links"}],"placeholder":5}\' WHERE name = \'Businesses_new_List_1\'');
    return TRUE;
  }

  public function upgrade_2201(): bool {
    $this->ctx->log->info('Ensure there is a new businesses search as per wvbia');
    $check = CRM_Core_DAO::singleValueQuery('SELECT id FROM civicrm_saved_search WHERE name = \'Businesses_new_\'');
    if (empty($check)) {
      CRM_Core_DAO::executeQuery('INSERT INTO civicrm_saved_search (name,label,api_entity, api_params, created_id, modified_id) VALUES (\'Businesses_new_\', \'Businesses (new)\', \'Unit\', \'{"version":4,"select":["id","Unit_UnitBusiness_unit_id_01_UnitBusiness_Contact_business_id_01.display_name","Unit_UnitBusiness_unit_id_01_UnitBusiness_Contact_business_id_01.id","GROUP_CONCAT(DISTINCT Unit_Address_address_id_01.geo_code_1) AS GROUP_CONCAT_Unit_Address_address_id_01_geo_code_1","GROUP_CONCAT(DISTINCT Unit_Address_address_id_01.geo_code_2) AS GROUP_CONCAT_Unit_Address_address_id_01_geo_code_2","GROUP_CONCAT(DISTINCT Unit_Address_address_id_01.street_unit) AS GROUP_CONCAT_Unit_Address_address_id_01_street_unit","GROUP_CONCAT(DISTINCT Unit_Address_address_id_01.city) AS GROUP_CONCAT_Unit_Address_address_id_01_city","GROUP_CONCAT(DISTINCT Unit_Address_address_id_01.postal_code) AS GROUP_CONCAT_Unit_Address_address_id_01_postal_code","GROUP_CONCAT(DISTINCT Unit_Address_address_id_01.country_id:label) AS GROUP_CONCAT_Unit_Address_address_id_01_country_id_label","GROUP_CONCAT(DISTINCT Unit_Address_address_id_01_Address_StateProvince_state_province_id_01.abbreviation) AS GROUP_CONCAT_Unit_Address_address_id_01_Address_StateProvince_state_province_id_01_abbreviation","GROUP_CONCAT(DISTINCT Unit_Address_address_id_01.street_address) AS GROUP_CONCAT_Unit_Address_address_id_01_street_address"],"orderBy":[],"where":[],"groupBy":["id","Unit_UnitBusiness_unit_id_01_UnitBusiness_Contact_business_id_01.id"],"join":[["UnitBusiness AS Unit_UnitBusiness_unit_id_01","LEFT",["id","=","Unit_UnitBusiness_unit_id_01.unit_id"]],["Contact AS Unit_UnitBusiness_unit_id_01_UnitBusiness_Contact_business_id_01","LEFT",["Unit_UnitBusiness_unit_id_01.business_id","=","Unit_UnitBusiness_unit_id_01_UnitBusiness_Contact_business_id_01.id"],["Unit_UnitBusiness_unit_id_01_UnitBusiness_Contact_business_id_01.is_deleted","=",false]],["Address AS Unit_Address_address_id_01","LEFT",["address_id","=","Unit_Address_address_id_01.id"]],["StateProvince AS Unit_Address_address_id_01_Address_StateProvince_state_province_id_01","LEFT",["Unit_Address_address_id_01.state_province_id","=","Unit_Address_address_id_01_Address_StateProvince_state_province_id_01.id"]]],"having":[]}\', 238, 240)');
      CRM_Core_DAO::executeQuery('INSERT INTO civicrm_search_display (name, label, saved_search_id, type, settings) SELECT \'Businesses_new_List_1\', \'Businesses (new) List 1\', id, \'list\',  \'{"style":"ul","limit":0,"sort":[],"pager":false,"columns":[{"type":"field","key":"GROUP_CONCAT_Unit_Address_address_id_01_street_unit","dataType":"String","link":{"path":"civicrm/unit/form?reset=1&action=update&context=update&id=[id]","entity":"","action":"","join":"","target":"_blank"},"rewrite":"Unit #[GROUP_CONCAT_Unit_Address_address_id_01_street_unit] [Unit_Address_address_id_01.street_address]","empty_value":"[GROUP_CONCAT_Unit_Address_address_id_01_street_address]"},{"type":"field","key":"GROUP_CONCAT_Unit_Address_address_id_01_city","dataType":"String"},{"type":"field","key":"GROUP_CONCAT_Unit_Address_address_id_01_postal_code","dataType":"String"},{"type":"field","key":"GROUP_CONCAT_Unit_Address_address_id_01_country_id_label","dataType":"Integer","rewrite":"[Unit_Address_address_id_01_Address_StateProvince_state_province_id_01.abbreviation], [GROUP_CONCAT_Unit_Address_address_id_01_country_id_label]","empty_value":"[Unit_Address_address_id_01_Address_StateProvince_state_province_id_01.abbreviation]"},{"links":[{"path":"civicrm/contact/map?reset=1&cid=[Unit_UnitBusiness_unit_id_01_UnitBusiness_Contact_business_id_01.id]","icon":"fa-map-marker","text":"Map","style":"default","condition":["GROUP_CONCAT_Unit_Address_address_id_01_geo_code_1","IS NOT EMPTY"],"entity":"","action":"","join":"","target":""}],"type":"links"}],"placeholder":5}\' FROM civicrm_saved_search WHERE name = \'Businesses_new_\'');
    }
    return TRUE;
  }

  public function upgrade_2202(): bool {
    $this->ctx->log->info('Apply Update to the Units Search Display to fix delete link');
    $searchDisplay = \Civi\Api4\SearchDisplay::get(FALSE)->addWhere('saved_search_id', '=', 11)->execute()->first();
    foreach ($searchDisplay['settings']['columns'] as $key => $column) {
      if (isset($column['links']) && $column['links'][0]['path'] == 'civicrm/unit/form?action=delete&id=[id]') {
        $searchDisplay['settings']['columns'][$key]['links'][0]['path'] = 'civicrm/unit/form?action=delete&id=[id]&pid=[property_id]';
      }
    }
    \Civi\Api4\SearchDisplay::update(FALSE)->addValue('settings', $searchDisplay['settings'])->addWhere('id', '=', $searchDisplay['id'])->execute();
    return TRUE;
  }

  public function upgrade_2203(): bool {
    $this->ctx->log->info('Upgrade 2203: Modify Custom field defaults and change name of Bia Staff label Also fix local bia headings menu url');
    $staffType = civicrm_api3('ContactType', 'get', ['name' => 'OBIAA_Staff']);
    civicrm_api3('ContactType', 'create', [
      'label' => 'BIA Member\'s Staff',
      'id' => $staffType['id'],
    ]);
    $customFields = ['Open_Date', 'Close_Date'];
    foreach ($customFields as $customFieldName) {
      $customField = civicrm_api3('CustomField', 'get', ['name' => $customFieldName]);
      \Civi\Api4\CustomField::update(FALSE)->addValue('start_date_years', 50)->addValue('end_date_years', 50)->addWhere('id', '=', $customField['id'])->execute();
    }
    CRM_Core_DAO::executeQuery("UPDATE civicrm_navigation SET url = %1 WHERE name = %2", [
      1 => [CRM_Utils_System::url('civicrm/admin/custom/group/field/option', ['reset' => 1, 'action' => 'browse', 'gid' => 4, 'fid' => 9], TRUE, NULL, TRUE, FALSE, TRUE), 'String'],
      2 => ['Local BIA Heading', 'String'],
    ]);
    return TRUE;
  }

  public function upgrade_2204(): bool {
    $this->ctx->log->info('Upgrade 2204 Remove original standard bia1 Local Bia Headings');
    $biaHeadings = [
      'Bakeries',
      'Dine',
      'Miscellaneous',
      'Shoppe',
      'Sip',
    ];
    foreach ($biaHeadings as $biaHeading) {
      $contactCount = \Civi\Api4\Contact::get(FALSE)->addWhere('Business_Category.Child_Class_Unique', '=', $biaHeading)->execute();
      if (count($contactCount) < 1) {
        $optionValue = \Civi\Api4\OptionValue::get(FALSE)->addWhere('value', '=', $biaHeading)->addWhere('option_group_id:name', '=', 'Business_Category_Child_Class_Unique')->execute()->first();
        \Civi\Api4\OptionValue::delete(FALSE)->addWhere('id', '=', $optionValue['id'])->execute();
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
