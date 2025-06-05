<?php

class CRM_CiviMobileAPI_Utils_IsAllowMobileEventRegistrationField {

  /**
   * Gets value from custom field
   *
   * @param $eventId
   *
   * @return int|NULL
   */
  public static function getValue($eventId) {
    $customFieldName = CRM_CiviMobileAPI_Install_Entity_CustomGroup::ALLOW_MOBILE_REGISTRATION . '.' . CRM_CiviMobileAPI_Install_Entity_CustomField::IS_MOBILE_EVENT_REGISTRATION;

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
   * Sets value from custom field
   *
   * @param $eventId
   * @param $value
   * @return bool
   */
  public static function setValue($eventId, $value) {
    $customFieldName = CRM_CiviMobileAPI_Install_Entity_CustomGroup::ALLOW_MOBILE_REGISTRATION . '.' . CRM_CiviMobileAPI_Install_Entity_CustomField::IS_MOBILE_EVENT_REGISTRATION;

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
