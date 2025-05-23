<?php

class CRM_CiviMobileAPI_Utils_EventQrCode {
  /**
   * Checks is Event used QR code
   *
   * @param $eventId
   *
   * @return int|NULL
   */
  public static function isEventUsedQrCode($eventId) {
    $customFieldName = CRM_CiviMobileAPI_Install_Entity_CustomGroup::QR_USES . '.' . CRM_CiviMobileAPI_Install_Entity_CustomField::IS_QR_USED;

    $event = civicrm_api4('Event', 'get', [
      'select' => [
        $customFieldName,
      ],
      'where' => [
        ['id', '=', $eventId],
      ],
      'checkPermissions' => FALSE,
    ])->first();

    return $event[$customFieldName];
  }

  /**
   * Sets QR code for Event
   *
   * @param $eventId
   * @return bool
   */
  public static function setQrCodeToEvent($eventId) {
    $customFieldName = CRM_CiviMobileAPI_Install_Entity_CustomGroup::QR_USES . '.' . CRM_CiviMobileAPI_Install_Entity_CustomField::IS_QR_USED;

    try {
      civicrm_api4('Event', 'update', [
        'values' => [
          $customFieldName => 1,
        ],
        'where' => [
          ['id', '=', $eventId],
        ],
        'checkPermissions' => FALSE,
      ]);
    } catch (CRM_Core_Exception $e) {
      return false;
    }

    return true;
  }

}
