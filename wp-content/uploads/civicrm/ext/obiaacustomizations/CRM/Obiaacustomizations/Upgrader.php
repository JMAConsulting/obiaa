<?php

use CRM_Obiaacustomizations_ExtensionUtil as E;

/**
 * Collection of upgrade steps.
 */
class CRM_Obiaacustomizations_Upgrader extends CRM_Extension_Upgrader_Base {

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
  public function upgrade_1000(): bool {
    $this->ctx->log->info('Applying update 1000: Fix token notice in Message Templates');
    $templates = [
      'contribution_online_template' => 'contribution_online_receipt',
      'event_offline_template' => 'event_offline_receipt',
      'event_online_template' => 'event_online_receipt',
    ];
    foreach ($templates as $fileName => $workflowName) {
      $content = file_get_contents(E::path('./' . $fileName . '.tpl'));
      CRM_Core_DAO::executeQuery("UPDATE civicrm_msg_template SET msg_text = NULL, msg_html = %2 WHERE workflow_name = %1 AND is_default = 1 AND is_reserved = 0", [
        1 => [$workflowName, 'String'],
        2 => [$content, 'String'],
      ]);
    }
    return TRUE;
  }

  public function upgrade_2100(): bool {
    $this->ctx->log->info('Applying update 1100: Update Relationship tab to show business position and job title');
    CRM_Core_DAO::executeQuery('UPDATE civicrm_saved_search SET api_params = \'{"version":4,"select":["near_relation:label","RelationshipCache_Contact_far_contact_id_01.display_name","start_date","end_date","RelationshipCache_Contact_far_contact_id_01.address_primary.city","RelationshipCache_Contact_far_contact_id_01.address_primary.state_province_id:label","RelationshipCache_Contact_far_contact_id_01.email_primary.email","RelationshipCache_Contact_far_contact_id_01.phone_primary.phone","permission_near_to_far:label","permission_far_to_near:label","is_active","Business_Contact.Business_Contact_Position","RelationshipCache_Contact_far_contact_id_01.job_title"],"orderBy":[],"where":[["RelationshipCache_Contact_far_contact_id_01.is_deleted","=",false]],"groupBy":[],"join":[["Contact AS RelationshipCache_Contact_far_contact_id_01","LEFT",["far_contact_id","=","RelationshipCache_Contact_far_contact_id_01.id"]]],"having":[]}\' WHERE name = "Contact_Summary_Relationships"');
    CRM_Core_DAO::executeQuery('UPDATE civicrm_search_display SET settings = \'{"description":null,"sort":[],"limit":50,"pager":{"hide_single":true,"expose_limit":true},"placeholder":5,"columns":[{"type":"field","key":"near_relation:label","dataType":"String","label":"Relationship","sortable":true,"icons":[{"field":"permission_far_to_near:icon","side":"left"}]},{"type":"field","key":"RelationshipCache_Contact_far_contact_id_01.display_name","dataType":"String","label":"With","sortable":true,"icons":[{"field":"RelationshipCache_Contact_far_contact_id_01.contact_sub_type:icon","side":"left"},{"field":"RelationshipCache_Contact_far_contact_id_01.contact_type:icon","side":"left"},{"field":"permission_near_to_far:icon","side":"right"}],"link":{"path":"","entity":"Contact","action":"view","join":"RelationshipCache_Contact_far_contact_id_01","target":""},"title":"View Related Contact"},{"type":"field","key":"start_date","dataType":"Date","label":"Dates","sortable":true,"rewrite":"[start_date] - [end_date]"},{"type":"field","key":"RelationshipCache_Contact_far_contact_id_01.address_primary.city","dataType":"String","label":"City","sortable":true},{"type":"field","key":"RelationshipCache_Contact_far_contact_id_01.address_primary.state_province_id:label","dataType":"Integer","label":"State\/Prov","sortable":true},{"type":"field","key":"RelationshipCache_Contact_far_contact_id_01.email_primary.email","dataType":"String","label":"Email","sortable":true,"icons":[{"icon":"fa-ban","side":"left","if":["RelationshipCache_Contact_far_contact_id_01.do_not_email","=",true]}]},{"type":"field","key":"RelationshipCache_Contact_far_contact_id_01.phone_primary.phone","dataType":"String","label":"Phone","sortable":true,"icons":[{"icon":"fa-ban","side":"left","if":["RelationshipCache_Contact_far_contact_id_01.do_not_phone","=",true]}]},{"type":"field","key":"RelationshipCache_Contact_far_contact_id_01.job_title","dataType":"String","label":"Job Title","sortable":true},{"type":"field","key":"Business_Contact.Business_Contact_Position","dataType":"String","label":"Business Contact Position","sortable":true},{"text":"","style":"default","size":"btn-xs","icon":"fa-bars","links":[{"entity":"Relationship","action":"view","join":"","target":"crm-popup","icon":"fa-external-link","text":"View Relationship","style":"default","path":"","task":"","condition":[],"conditions":[]},{"entity":"Relationship","action":"update","join":"","target":"crm-popup","icon":"fa-pencil","text":"Update Relationship","style":"default","path":"","task":"","condition":[],"conditions":[]},{"task":"disable","entity":"Relationship","join":"","target":"crm-popup","icon":"fa-toggle-off","text":"Disable Relationship","style":"default","path":"","action":"","condition":[],"conditions":[]},{"entity":"Relationship","action":"delete","join":"","target":"crm-popup","icon":"fa-trash","text":"Delete Relationship","style":"danger","path":"","task":"","condition":[],"conditions":[]}],"type":"menu","label":"Row Actions","label_hidden":true,"alignment":"text-right"}],"actions":false,"classes":["table","table-striped"],"toolbar":[{"action":"add","entity":"Relationship","text":"Add Relationship","icon":"fa-plus","style":"primary","target":"crm-popup","join":"","path":"","task":"","condition":[],"conditions":[]}]}\' WHERE name = "Contact_Summary_Relationships_Active"');
    CRM_Core_DAO::executeQuery('UPDATE civicrm_search_display SET settings = \'{"description":"","sort":[],"limit":50,"pager":{"hide_single":true,"expose_limit":true},"placeholder":5,"columns":[{"type":"field","key":"near_relation:label","dataType":"String","label":"Relationship","sortable":true,"icons":[{"field":"permission_far_to_near:icon","side":"left"}]},{"type":"field","key":"RelationshipCache_Contact_far_contact_id_01.display_name","dataType":"String","label":"With","sortable":true,"icons":[{"field":"RelationshipCache_Contact_far_contact_id_01.contact_sub_type:icon","side":"left"},{"field":"RelationshipCache_Contact_far_contact_id_01.contact_type:icon","side":"left"},{"field":"permission_near_to_far:icon","side":"right"}],"link":{"path":"","entity":"Contact","action":"view","join":"RelationshipCache_Contact_far_contact_id_01","target":""},"title":"View Related Contact"},{"type":"field","key":"start_date","dataType":"Date","label":"Dates","sortable":true,"rewrite":"[start_date] - [end_date]"},{"type":"field","key":"RelationshipCache_Contact_far_contact_id_01.address_primary.city","dataType":"String","label":"City","sortable":true},{"type":"field","key":"RelationshipCache_Contact_far_contact_id_01.address_primary.state_province_id:label","dataType":"Integer","label":"State\/Prov","sortable":true},{"type":"field","key":"RelationshipCache_Contact_far_contact_id_01.email_primary.email","dataType":"String","label":"Email","sortable":true,"icons":[{"icon":"fa-ban","side":"left","if":["RelationshipCache_Contact_far_contact_id_01.do_not_email","=",true]}]},{"type":"field","key":"RelationshipCache_Contact_far_contact_id_01.phone_primary.phone","dataType":"String","label":"Phone","sortable":true,"icons":[{"icon":"fa-ban","side":"left","if":["RelationshipCache_Contact_far_contact_id_01.do_not_phone","=",true]}]},{"type":"field","key":"RelationshipCache_Contact_far_contact_id_01.job_title","dataType":"String","label":"Job Title","sortable":true},{"type":"field","key":"Business_Contact.Business_Contact_Position","dataType":"String","label":"Business Contact Position","sortable":true},{"text":"","style":"default","size":"btn-xs","icon":"fa-bars","label":"Row Actions","label_hidden":true,"links":[{"entity":"Relationship","action":"view","join":"","target":"crm-popup","icon":"fa-external-link","text":"View Relationship","style":"default","path":"","task":"","condition":[],"conditions":[]},{"entity":"Relationship","action":"update","join":"","target":"crm-popup","icon":"fa-pencil","text":"Update Relationship","style":"default","path":"","task":"","condition":[],"conditions":[]},{"task":"enable","entity":"Relationship","join":"","target":"crm-popup","icon":"fa-toggle-on","text":"Enable Relationship","style":"default","path":"","action":"","condition":[],"conditions":[]},{"entity":"Relationship","action":"delete","join":"","target":"crm-popup","icon":"fa-trash","text":"Delete Relationship","style":"danger","path":"","task":"","condition":[],"conditions":[]}],"type":"menu","alignment":"text-right"}],"actions":false,"classes":["table","table-striped","disabled"]}\' WHERE name = "Contact_Summary_Relationships_Inactive" ');
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
