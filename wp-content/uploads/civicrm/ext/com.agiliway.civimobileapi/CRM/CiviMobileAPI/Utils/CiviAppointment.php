<?php

class CRM_CiviMobileAPI_Utils_CiviAppointment {
  
  /**
   * Is "com.agiliway.civiappointment" installed
   *
   * @return bool
   */
  public static function isCiviAppointmentInstalled() {
    return CRM_CiviMobileAPI_Utils_ExtensionHelper::isInstalled('com.agiliway.civiappointment');
  }
  
}
