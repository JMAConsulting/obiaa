<?php

class CRM_CiviMobileAPI_Utils_TimeTracker {
  
  /**
   * Is "com.agiliway.time-tracker" installed
   *
   * @return bool
   */
  public static function isTimeTrackerInstalled() {
    try {
      $extensionStatus = civicrm_api3('Extension', 'getsingle', [
        'return' => "status",
        'full_name' => "com.agiliway.time-tracker",
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
