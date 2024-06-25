<?php

class CRM_CiviMobileAPI_Utils_CiviAppointment {
  
  /**
   * Is "com.agiliway.civiappointment" installed
   *
   * @return bool
   */
  public static function isCiviAppointmentInstalled() {
    try {
      $extensionStatus = civicrm_api3('Extension', 'getsingle', [
        'return' => "status",
        'full_name' => "com.agiliway.civiappointment",
      ]);
    } catch (Exception $e) {
      return FALSE;
    }
    
    if ($extensionStatus['status'] == 'installed') {
      return TRUE;
    }
    
    return FALSE;
  }
  
}
