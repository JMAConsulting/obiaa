<?php

class CRM_CiviMobileAPI_Hook_Post_Event {

  public static function run($op, $objectName, $objectId) {
    if ($objectName == 'Event' && $op == 'create') {
      $qrcodeCheckinEvent = CRM_Utils_Request::retrieve('default_qrcode_checkin_event', 'String');
      $eventId = $objectId;

      CRM_CiviMobileAPI_Utils_IsAllowMobileEventRegistrationField::setValue($eventId, 1);

      if ($qrcodeCheckinEvent) {
        CRM_CiviMobileAPI_Utils_EventQrCode::setQrCodeToEvent($eventId);
      }
    }
  }
}
