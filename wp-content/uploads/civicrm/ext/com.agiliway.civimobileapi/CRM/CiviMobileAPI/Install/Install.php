<?php

class CRM_CiviMobileAPI_Install_Install {

  /**
   * Installs requirements for extension
   */
  public static function run() {
    (new CRM_CiviMobileAPI_Install_Entity_UpdateMessageTemplate())->install();
    (new CRM_CiviMobileAPI_Install_Entity_ApplicationQrCode())->install();
  }
}
