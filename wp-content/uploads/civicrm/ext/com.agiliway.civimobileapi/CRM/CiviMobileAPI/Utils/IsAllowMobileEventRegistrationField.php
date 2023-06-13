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
    $customFieldName = "custom_" . CRM_CiviMobileAPI_Utils_CustomField::getId(
      CRM_CiviMobileAPI_Install_Entity_CustomGroup::ALLOW_MOBILE_REGISTRATION,
      CRM_CiviMobileAPI_Install_Entity_CustomField::IS_MOBILE_EVENT_REGISTRATION
    );

    try {
      $event = civicrm_api3('Event', 'getsingle', [
          'id' => $eventId,
          'return' => [$customFieldName]
      ]);
    } catch (CiviCRM_API3_Exception $e) {
      $event = [];
    }

    return (!empty($event[$customFieldName]) ? $event[$customFieldName] : NULL);
  }

  /**
   * Sets value from custom field
   *
   * @param $eventId
   * @param $value
   * @return bool
   */
  public static function setValue($eventId, $value) {
    $customFieldName = "custom_" . CRM_CiviMobileAPI_Utils_CustomField::getId(
      CRM_CiviMobileAPI_Install_Entity_CustomGroup::ALLOW_MOBILE_REGISTRATION,
      CRM_CiviMobileAPI_Install_Entity_CustomField::IS_MOBILE_EVENT_REGISTRATION
    );

    try {
        civicrm_api3('Event', 'create', [
          'id' => $eventId,
          $customFieldName  => $value
        ]);
    } catch (CiviCRM_API3_Exception $e) {
      return false;
    }

    return true;
  }

}
