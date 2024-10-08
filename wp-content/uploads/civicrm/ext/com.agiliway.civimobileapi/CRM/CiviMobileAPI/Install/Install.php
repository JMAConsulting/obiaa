<?php

class CRM_CiviMobileAPI_Install_Install {

  /**
   * Installs requirements for extension
   */
  public static function run() {

    (new CRM_CiviMobileAPI_Install_Entity_OptionGroup())->install();
    (new CRM_CiviMobileAPI_Install_Entity_OptionValue())->install();
    (new CRM_CiviMobileAPI_Install_Entity_CustomGroup())->install();
    (new CRM_CiviMobileAPI_Install_Entity_CustomField())->install();
    (new CRM_CiviMobileAPI_Install_Entity_UpdateMessageTemplate())->install();
    (new CRM_CiviMobileAPI_Install_Entity_Job())->install();

  }

  /**
   * Disables extension's Entities
   */
  public static function disable() {
    (new CRM_CiviMobileAPI_Install_Entity_CustomGroup())->disableAll();
    (new CRM_CiviMobileAPI_Install_Entity_Job())->disableAll();
  }

  /**
   * Enables extension's Entities
   */
  public static function enable() {
    (new CRM_CiviMobileAPI_Install_Entity_CustomGroup())->enableAll();
    (new CRM_CiviMobileAPI_Install_Entity_Job())->enableAll();
    (new CRM_CiviMobileAPI_Install_Entity_ApplicationQrCode())->install();
  }

  /**
   * Uninstall extension's Entities
   */
  public static function uninstall() {
    (new CRM_CiviMobileAPI_Install_Entity_Job())->deleteAll();
    (new CRM_CiviMobileAPI_Install_Entity_OptionValue())->deleteAll();
    (new CRM_CiviMobileAPI_Install_Entity_OptionGroup())->deleteAll();
  }

}
