<?php

/**
 * Class provide Event helper methods
 */
class CRM_CiviMobileAPI_Utils_Event {

  /**
   * Checks if "Same email" option is enabled
   * This option allows creating Participant
   * with Contacts which has same emails
   *
   * @param $eventId
   *
   * @return bool
   * @throws \CiviCRM_API3_Exception
   */
  public static function isAllowSameEmail($eventId) {
    $allowSameParticipantEmails = civicrm_api3('Event', 'getvalue', [
      'return' => "allow_same_participant_emails",
      'id' => $eventId,
    ]);

    return !empty($allowSameParticipantEmails) && $allowSameParticipantEmails == 1;
  }

  /**
   * Gets Event by id
   *
   * @param $eventId
   *
   * @return bool
   */
  public static function getById($eventId) {
    $event = civicrm_api3('Event', 'getsingle', [
      'id' => $eventId
    ]);

    return (!empty($event) && $event['is_error'] != 1) ? $event : false;
  }

  /**
   * Check is event have location
   *
   * @param $eventId
   *
   * @return bool
   */
  public static function isEventHaveLocation($eventId) {
    try {
      $apiOutput = civicrm_api3('Event', 'getsingle', [
        'return' => ["loc_block_id"],
        'id' => $eventId,
      ]);
    } catch (Exception $e) {
      return FALSE;
    }
    if (array_key_exists('loc_block_id', $apiOutput)) {
      return TRUE;
    } else {
      return FALSE;
    }
  }

  public static function isParticipantAlreadyRegistered($contactId, $startDate, $endDate) {

    $registrationCount = self::getParticipantRegistrationCount($contactId, $startDate, $endDate);

    return $registrationCount > 0;
  }

  private static function getParticipantRegistrationCount($contactId, $startDate, $endDate) {

    if (empty($endDate)) {
      $endDate = date('Y-m-d H:i:s', strtotime($startDate . ' + 1 day'));
    }

    $participantFilters = [
      ['contact_id', '=', $contactId],
      ['event_id.is_template', '=', FALSE],
      ['event_id.is_active', '=', TRUE],
      [
        'OR', [
        [
          'AND', [
          ['event_id.start_date', '<=', $endDate],
          ['event_id.end_date', '>', $startDate],
        ]
        ],
        [
          'AND', [
          ['event_id.start_date', '<', $endDate],
          ['event_id.end_date', '>=', $startDate],
        ]
        ],
      ]
      ],
    ];


    return civicrm_api4('Participant', 'get', ['where' => $participantFilters])->count();
  }
  
  public static function getParticipantsCountByEvent($events) {
    $eventIds = array_column($events, 'id');
    
    $participantsCountByEvent = civicrm_api4('Participant', 'get', [
      'select' => ['event_id', 'COUNT(*) AS participant_count'],
      'join' => [['Event AS event', 'LEFT', ['event.id', '=', 'event_id']]],
      'where' => [['event_id', 'IN', $eventIds]],
      'groupBy' => ['event_id'],
      'checkPermissions' => FALSE,
    ]);
    
    $formattedParticipantsCountByEvent = [];
    
    foreach ($participantsCountByEvent as $event) {
      $formattedParticipantsCountByEvent[$event['event_id']] = $event['participant_count'];
    }
    
    return $formattedParticipantsCountByEvent;
  }


}
