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

    $participants = civicrm_api4('Participant', 'get', [
      'select' => [
        'contact_id.display_name',
        'id',
        'contact_id',
      ],
      'where' => [
        ['id', 'IN', $ids],
        ['event_id', '=', $eventId],
      ],
      'checkPermissions' => FALSE,
    ])->getArrayCopy();

    return CRM_CiviMobileAPI_Utils_Participant::getParticipantsShortDetails($participants);
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

    $participantsCount = civicrm_api4('Participant', 'get', [
      'select' => [
        'row_count',
      ],
      'where' => [
        ['id', 'IN', $ids],
        ['event_id', '=', $eventId],
      ],
      'checkPermissions' => FALSE,
    ])->count();

    if (count($ids) == $participantsCount) {
      return TRUE;
    }
    return FALSE;
  }

}
