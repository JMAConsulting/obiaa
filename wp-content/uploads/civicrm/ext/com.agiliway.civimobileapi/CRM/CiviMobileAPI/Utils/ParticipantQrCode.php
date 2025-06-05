<?php

class CRM_CiviMobileAPI_Utils_ParticipantQrCode {

  /**
   * Sets QR code for Event
   *
   * @param $participantId
   * @param $eventId
   * @param $contactId
   * @param $hash
   * @param $image
   * @return bool
   */
  public static function setQrCodeToParticipant($participantId, $eventId, $contactId, $hash, $image) {
    $qrEventIdFieldName = CRM_CiviMobileAPI_Install_Entity_CustomGroup::QR_CODES . '.' . CRM_CiviMobileAPI_Install_Entity_CustomField::QR_EVENT_ID;
    $qrHashFieldName = CRM_CiviMobileAPI_Install_Entity_CustomGroup::QR_CODES . '.' . CRM_CiviMobileAPI_Install_Entity_CustomField::QR_CODE;
    $qrImageFieldName = CRM_CiviMobileAPI_Install_Entity_CustomGroup::QR_CODES . '.' . CRM_CiviMobileAPI_Install_Entity_CustomField::QR_IMAGE;

    try {
      civicrm_api4('Participant', 'update', [
        'values' => [
          $qrEventIdFieldName => $eventId,
          $qrHashFieldName  => $hash,
          $qrImageFieldName  => $image,
        ],
        'where' => [
          ['id', '=', $participantId],
          ['contact_id', '=', $contactId],
        ],
        'checkPermissions' => FALSE,
      ]);
    } catch (CRM_Core_Exception $e) {
      return false;
    }

    return true;
  }

  /**
   * Gets QR code info
   *
   * @param $participantId
   * @return array|null
   */
  public static function getQrCodeInfo($participantId) {
    $customQrCode = CRM_CiviMobileAPI_Install_Entity_CustomGroup::QR_CODES . '.' . CRM_CiviMobileAPI_Install_Entity_CustomField::QR_CODE;
    $customQrImage = CRM_CiviMobileAPI_Install_Entity_CustomGroup::QR_CODES . '.' . CRM_CiviMobileAPI_Install_Entity_CustomField::QR_IMAGE;
    $customEventId = CRM_CiviMobileAPI_Install_Entity_CustomGroup::QR_CODES . '.' . CRM_CiviMobileAPI_Install_Entity_CustomField::QR_EVENT_ID;

    $participant = civicrm_api4('Participant', 'get', [
      'select' => [$customQrCode, $customQrImage, $customEventId],
      'where' => [
        ['id', '=', $participantId],
      ],
      'checkPermissions' => FALSE,
    ])->first();

    if (!empty($participant)) {
      return [
        'qr_code_image' => urldecode(html_entity_decode($participant[$customQrImage])),
        'qr_code_hash'  => $participant[$customQrCode],
        'qr_event_id'   => $participant[$customEventId]
      ];
    }

    return NULL;
  }

}
