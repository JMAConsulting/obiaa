<?php

class CRM_CiviMobileAPI_Utils_ExtensionHelper {

  /**
   * Is extension installed
   *
   * @return bool
   */
  public static function isInstalled($extensionKey) {
    try {
      $extensionStatus = civicrm_api4('Extension', 'get', [
        'select' => [
          'status',
        ],
        'where' => [
          ['key', '=', $extensionKey],
        ],
        'checkPermissions' => FALSE,
      ])->first();
    } catch (CRM_Core_Exception $e) {
      return FALSE;
    }

    if ($extensionStatus['status'] == 'installed') {
      return TRUE;
    }

    return FALSE;
  }

}
