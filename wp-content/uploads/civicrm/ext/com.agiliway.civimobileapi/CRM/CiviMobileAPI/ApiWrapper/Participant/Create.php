<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

/**
 * @deprecated will be deleted in version 7.0.0
 */
class CRM_CiviMobileAPI_ApiWrapper_Participant_Create implements API_Wrapper {

  /**
   * Interface for interpreting api input
   *
   * @param array $apiRequest
   *
   * @return array
   * @throws CRM_Core_Exception
   */
  public function fromApiInput($apiRequest) {
    if (empty($apiRequest['params']['event_id']) || empty($apiRequest['params']['contact_id'])) {
      return $apiRequest;
    }

    $participant = new CRM_Event_BAO_Participant();
    $participant->contact_id = $apiRequest['params']['contact_id'];
    $participant->event_id = $apiRequest['params']['event_id'];
    $participantExist = $participant->find(TRUE);

    $eventDetails = civicrm_api3('Event', 'getsingle', ['id' => $apiRequest['params']['event_id']]);

    $startDate = $eventDetails['start_date'];

    if (isset($eventDetails['end_date'])) {
      $endDate = $eventDetails['end_date'];
    } else {
      $endDate = date('Y-m-d H:i:s', strtotime($startDate . ' + 1 day'));
    }

    $isDisallowedEventParticipantRegistrationOverlap = Civi::settings()
      ->get('civimobile_is_disallowed_event_participant_registration_overlap');

    if (!empty($participantExist) && empty($apiRequest['params']['id'])) {
      throw new CRM_Core_Exception(E::ts('This contact has already been assigned to this event.'), 'contact_already_registered');
    }

    if ($isDisallowedEventParticipantRegistrationOverlap) {
      $isParticipantAlreadyRegistered = CRM_CiviMobileAPI_Utils_Event::isParticipantAlreadyRegistered($apiRequest['params']['contact_id'], $startDate, $endDate);

      if ($isParticipantAlreadyRegistered) {
        throw new CRM_Core_Exception(E::ts('This contact has already been assigned to event with same date.'), 'possible_event_registration_overlap');
      }
    }

    if (empty($apiRequest['params']['fee_currency'])) {
      try {
        $feeCurrency = civicrm_api3('Event', 'getvalue', [
          'return' => "currency",
          'id' => $apiRequest['params']['event_id'],
        ]);
      } catch (CRM_Core_Exception $e) {
        $feeCurrency = FALSE;
      }

      if (!empty($feeCurrency)) {
        $apiRequest['params']['fee_currency'] = $feeCurrency;
      }
    }

    if (empty($apiRequest['params']['id']) && empty($apiRequest['params']['status_id'])) {
      throw new \CRM_Core_Exception(E::ts('Empty participant status field(status_id). Please fill it.'));
    }

    return $apiRequest;
  }

  /**
   * Interface for interpreting api output
   *
   * @param $apiRequest
   * @param $result
   *
   * @return array
   */
  public function toApiOutput($apiRequest, $result) {
    if (!empty($apiRequest['params']['send_confirmation']) && $apiRequest['params']['send_confirmation'] == 1) {
      if (!empty($result['values'])) {
        $currentContactId = CRM_CiviMobileAPI_Utils_Contact::getCurrentContactId();
        foreach ($result['values'] as $participant) {
          if ($participant['contact_id'] == $currentContactId) {
            CRM_CiviMobileAPI_Utils_Emails_EventConfirmationReceipt::send($participant['id'], 'event_online_receipt');
          } else {
            CRM_CiviMobileAPI_Utils_Emails_EventConfirmationReceipt::send($participant['id'], 'event_offline_receipt');
          }
        }
      }
    }

    return $result;
  }

}
