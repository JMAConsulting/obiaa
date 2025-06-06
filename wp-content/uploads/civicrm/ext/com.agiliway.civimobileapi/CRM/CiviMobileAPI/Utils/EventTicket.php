<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

/**
 * Class provide Event Tickets helper methods
 */
class CRM_CiviMobileAPI_Utils_EventTicket {

  /**
   * Gets all Contact's tickets
   *
   * @param $eventId
   * @param $contactId
   *
   * @return array
   */
  public static function getAll($eventId, $contactId) {
    if (empty($eventId) || empty($contactId)) {
      return [];
    }

    $myTickets = [];
    $event = CRM_CiviMobileAPI_Utils_Event::getById($eventId);
    $participants = CRM_CiviMobileAPI_Utils_Participant::getByEventAndContactId($eventId, $contactId);

    foreach ($participants as $participant) {
      $myTickets[] = static::prepareTicket($participant, $event);
    }

    return $myTickets;
  }

  /**
   * Prepares ticket
   *
   * @param $participant
   * @param $event
   * @return array
   */
  public static function prepareTicket($participant, $event) {
    $participantContactDisplayName = CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_Contact', $participant['contact_id'], 'display_name');
    $qrCodeInfo = CRM_CiviMobileAPI_Utils_ParticipantQrCode::getQrCodeInfo($participant['id']);

    if ($event['is_monetary'] == 1) {
      $currency = CRM_Utils_Money::format($participant['fee_amount'], $participant['fee_currency']);
    }

    return [
      'participant_contact_display_name' => $participantContactDisplayName,
      'participant_status_name' => !empty($participant['status_id:name']) ? E::ts($participant['status_id:name']) : '',
      'participant_role_name' => !empty($participant['role_id:name']) ? E::ts($participant['role_id:name'][0]) : '',
      'participant_fee_amount' => !empty($participant['fee_amount']) ? $participant['fee_amount'] : '0',
      'participant_fee_amount_currency' => !empty($currency) ? $currency : '',
      'event_name' => $event['event_title'],
      'event_start_date' => !empty($event['event_start_date']) ? $event['event_start_date'] : '',
      'event_end_date' => !empty($event['event_end_date']) ? $event['event_end_date'] : '',
      'qr_code_link' => !empty($qrCodeInfo['qr_code_image']) ? $qrCodeInfo['qr_code_image'] : ''
    ];
  }

}
