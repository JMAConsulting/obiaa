<?php

class CRM_CiviMobileAPI_Utils_Calendar {

  /**
   * Is "com.agiliway.civicalendar" installed and options use in CiviMobile
   *
   * @return bool
   */
  public static function isCivimobileUseCiviCalendarSettings() {
    return static::isCiviCalendarInstalled() && static::isActivateCiviCalendarSettings();
  }

  /**
   * Is checked 'synchronize_with_civicalendar' setting option in CiviMobile
   *
   * @return bool
   */
  public static function isActivateCiviCalendarSettings() {
    try {
      $civiCalendarSetting = civicrm_api4('Setting', 'get', [
        'select' => [
          CRM_CiviMobileAPI_Settings_Calendar_CiviMobile::getPrefix() . 'synchronize_with_civicalendar',
        ],
        'checkPermissions' => FALSE,
      ])->first()['value'];
    } catch (CRM_Core_Exception $e) {
      return FALSE;
    }

    if ($civiCalendarSetting == 1) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Is "com.agiliway.civicalendar" installed
   *
   * @return bool
   */
  public static function isCiviCalendarInstalled() {
    return CRM_CiviMobileAPI_Utils_ExtensionHelper::isInstalled('com.agiliway.civicalendar');
  }

  /**
   * Checks if the СiviCalendar version and settings format is compatible with a CiviMobileApi
   *
   * @return bool
   */
  public static function isCiviCalendarCompatible() {
    $minimalMajorVersion = 3.4;
    try {
      $calendarVersion = civicrm_api4('Extension', 'get', [
        'select' => [
          'version',
        ],
        'where' => [
          ['key', '=', 'com.agiliway.civicalendar'],
        ],
        'checkPermissions' => FALSE,
      ])->first()['version'];
    } catch (CRM_Core_Exception $e) {
      return FALSE;
    }

    if (!(floatval($calendarVersion) >= $minimalMajorVersion)) {
      return FALSE;
    }

    return TRUE;
  }

}
