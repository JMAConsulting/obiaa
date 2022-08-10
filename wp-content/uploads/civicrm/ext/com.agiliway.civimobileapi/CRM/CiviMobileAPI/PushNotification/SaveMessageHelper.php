<?php

class CRM_CiviMobileAPI_PushNotification_SaveMessageHelper {

  /**
   * Save send message for contacts
   *
   * @param $listOfContactsID
   * @param $objID
   * @param $objType
   * @param $text
   * @param $title
   * @param $data
   *
   * @return bool
   */
  public static function saveMessages($listOfContactsID, $objID, $objType, $title = NULL, $text = NULL, $data = NULL) {
    $pushNotificationLifetime = Civi::settings()->get("civimobile_push_notification_lifetime");
    if (isset($pushNotificationLifetime) && $pushNotificationLifetime == 0) {
      return FALSE;
    }

    foreach ($listOfContactsID as $contactId) {
      $paramsForInsert = [
        'contact_id' => $contactId,
        'data' => $data,
        'message_title' => $title,
        'entity_table' => $objType,
        'entity_id' => $objID,
        'invokeContactId' => CRM_Core_Session::singleton()->getLoggedInContactID(),
      ];

      CRM_CiviMobileAPI_BAO_PushNotificationMessages::create($paramsForInsert);
    }

    return TRUE;
  }

}
