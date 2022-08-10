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

    $participants = civicrm_api3('Participant', 'get', [
      'sequential' => 1,
      'id' => ['IN' => $ids],
      'event_id' => $eventId
    ]);

    return CRM_CiviMobileAPI_Utils_Participant::getParticipantsShortDetails($participants['values']);
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
