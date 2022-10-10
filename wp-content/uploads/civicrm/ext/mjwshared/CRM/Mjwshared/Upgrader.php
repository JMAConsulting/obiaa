<?php
use CRM_Mjwshared_ExtensionUtil as E;

/**
 * Collection of upgrade steps.
 */
class CRM_Mjwshared_Upgrader extends CRM_Mjwshared_Upgrader_Base {

  /**
   * @return TRUE on success
   * @throws Exception
   */
  public function upgrade_1000() {
    $this->ctx->log->info('Applying update 1000 - Add civicrm_paymentprocessor_webhook table');
    if (!CRM_Core_DAO::checkTableExists('civicrm_paymentprocessor_webhook')) {
      // Note: this SQL installs an old version of this table which will then
      // be updated by upgrade_1001 It only exists for the sake of people
      // upgrading from old versions.
      $this->executeSqlFile('sql/upgrade_1000.sql');
    }
    return TRUE;
  }

  /**
   * @return TRUE on success
   * @throws Exception
   */
  public function upgrade_1001() {
    $this->ctx->log->info('Applying update 1001 - alter civicrm_paymentprocessor_webhook table');
    $this->executeSqlFile('sql/upgrade_1001.sql');
    return TRUE;
  }
}
