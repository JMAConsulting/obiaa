<?php

class CRM_CiviMobileAPI_Utils_TimeTracker {
  
  /**
   * Is "com.agiliway.time-tracker" installed
   *
   * @return bool
   */
  public static function isTimeTrackerInstalled() {
    return CRM_CiviMobileAPI_Utils_ExtensionHelper::isInstalled('com.agiliway.time-tracker');
  }
  
}
