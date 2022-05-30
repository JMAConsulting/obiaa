<?php

class CRM_CiviMobileAPI_Utils_Agenda_Speakers {

  /**
   * Returns array with names of speakers
   *
   * @param $stringIds
   * @param $eventId
   * @return array
   */
  public static function getSpeakersNames($stringIds, $eventId) {
    if (empty($stringIds)) {
      return [];
    }
    $ids = explode(',', $stringIds);
    $names = [];

    $participants = civicrm_api3('Participant', 'get', [
      'sequential' => 1,
      'id' => ['IN' => $ids],
      'event_id' => $eventId
    ]);
    foreach ($participants["values"] as $speaker) {
      $names[] = [
        'display_name' => $speaker["display_name"],
        'id' => $speaker["id"],
        'contact_id' => $speaker["contact_id"]
      ];
    }
    return $names;
  }

  /**
   * Is speakers in Event
   *
   * @param $ids
   * @param $eventId
   * @return bool
   */
  public static function issetSpeakers($ids, $eventId) {
    if (empty($ids)) {
      return FALSE;
    }

    $participants = civicrm_api3('Participant', 'get', [
      'sequential' => 1,
      'id' => ['IN' => $ids],
      'event_id' => $eventId
    ]);

    if (count($ids) == count($participants["values"])) {
      return TRUE;
    }
    return FALSE;
  }

}
