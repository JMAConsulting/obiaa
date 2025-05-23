<?php

/**
 * Class provide Event helper methods
 */
class CRM_CiviMobileAPI_Utils_Event {

  /**
   * Gets Event by id
   *
   * @param $eventId
   *
   * @return array|bool
   */
  public static function getById($eventId) {
    $event = civicrm_api4('Event', 'get', [
      'where' => [
        ['id', '=', $eventId],
      ],
      'limit' => 1,
      'checkPermissions' => FALSE,
    ])->first();

    return !empty($event) ? $event : false;
  }

  /**
   * Check is event have location
   *
   * @param $eventId
   *
   * @return bool
   */
  public static function isEventHaveLocation($eventId) {
    $event = civicrm_api4('Event', 'get', [
      'select' => ['loc_block_id'],
      'where' => [
        ['id', '=', $eventId],
      ],
      'limit' => 1,
      'checkPermissions' => FALSE,
    ])->first();

    if (!is_null($event['loc_block_id'])) {
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
