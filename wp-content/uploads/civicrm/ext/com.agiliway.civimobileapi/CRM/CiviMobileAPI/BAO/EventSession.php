<?php

class CRM_CiviMobileAPI_BAO_EventSession extends CRM_CiviMobileAPI_DAO_EventSession {

  /**
   * Adds params
   *
   * @param $params
   *
   * @return \CRM_Core_DAO
   */
  public static function add(&$params) {
    $entity = new CRM_CiviMobileAPI_DAO_EventSession();
    $entity->copyValues($params);
    return $entity->save();
  }

  /**
   * Creates new row
   *
   * @param $params
   *
   * @return \CRM_Core_DAO
   */
  public static function &create(&$params) {
    $transaction = new self();

    if (!empty($params['id'])) {
      CRM_Utils_Hook::pre('edit', self::getEntityName(), $params['id'], $params);
    } else {
      CRM_Utils_Hook::pre('create', self::getEntityName(), NULL, $params);
    }

    $entityData = self::add($params);

    if (is_a($entityData, 'CRM_Core_Error')) {
      $transaction->rollback();
      return $entityData;
    }

    $speakersToAdd = !empty($params['speakers']) ? explode(',', $params['speakers']) : [];
    if (!empty($params['id'])) {
      $eventSession = civicrm_api3('CiviMobileEventSession', 'getsingle', [
        'id' => $params['id'],
      ]);

      $currentSpeakers = !empty($eventSession['speakers_id']) ? explode(',', $eventSession['speakers_id']) : [];
      $speakersToDelete = array_diff($currentSpeakers, $speakersToAdd);

      if (!empty($speakersToDelete)) {
        $query = "DELETE FROM civicrm_civimobile_event_session_speaker WHERE `event_session_id` = " . $params['id'] . " AND `speaker_id` IN (" . implode(',', $speakersToDelete) . ")";
        CRM_Core_DAO::executeQuery($query);
      }
      $speakersToAdd = array_diff($speakersToAdd, $currentSpeakers);
    }

    foreach ($speakersToAdd as $speakerId) {
      $eventSessionSpeaker = new CRM_CiviMobileAPI_DAO_EventSessionSpeaker();
      $eventSessionSpeaker->event_session_id = $entityData->id;
      $eventSessionSpeaker->speaker_id = $speakerId;
      $eventSessionSpeaker->save();
    }

    $transaction->commit();

    if (!empty($params['id'])) {
      CRM_Utils_Hook::post('edit', self::getEntityName(), $entityData->id, $entityData);
    } else {
      CRM_Utils_Hook::post('create', self::getEntityName(), $entityData->id, $entityData);
    }

    return $entityData;
  }

  /**
   * Deletes row
   *
   * @param $id
   */
  public static function del($id) {
    $entity = new CRM_CiviMobileAPI_DAO_EventSession();
    $entity->id = $id;
    $params = [];
    if ($entity->find(TRUE)) {
      CRM_Utils_Hook::pre('delete', self::getEntityName(), $entity->id, $params);
      $entity->delete();
      CRM_Utils_Hook::post('delete', self::getEntityName(), $entity->id, $entity);
    }
  }

  /**
   * Builds query for receiving data
   *
   * @param string $returnValue
   *
   * @return \CRM_Utils_SQL_Select
   */
  private static function buildSelectQuery() {
    $query = CRM_Utils_SQL_Select::from(CRM_CiviMobileAPI_DAO_EventSession::getTableName() . ' evs');
    $query->select('
       evs.id,
       evs.title,
       evs.description,
       evs.start_time,
       evs.end_time,
       evs.event_id,
       loc_venue.id as venue_id
    ');

    $query->select("loc_venue.name AS venue_name");
    $query->select("GROUP_CONCAT(distinct (SELECT participant.id FROM civicrm_participant participant WHERE esspeaker.speaker_id = participant.id AND participant.event_id = evs.event_id) SEPARATOR ',' ) as speakers");

    $query->join('loc_venue', 'LEFT join civicrm_civimobile_location_venue loc_venue ON evs.venue_id = loc_venue.id AND is_active = 1');
    $query->join('esspeaker', 'LEFT join civicrm_civimobile_event_session_speaker esspeaker ON evs.id = esspeaker.event_session_id');

    $query->groupBy('evs.id');

    return $query;
  }

  /**
   * Builds 'where' condition for query
   *
   * @param $query
   * @param array $params
   *
   * @return mixed
   */
  private static function buildWhereQuery($query, $params = []) {
    if (!empty($params['id'])) {
      $query->having('evs.id = #id', ['id' => $params['id']]);
    }
    if (!empty($params['event_id'])) {
      $query->where('evs.event_id = #event_id', ['event_id' => $params['event_id']]);
    }
    if (!empty($params['title'])) {
      $query->where('evs.title LIKE @title', ['title' => "%" . $params['title'] . "%"]);
    }
    if (!empty($params['speaker'])) {
      $query->having('speakers REGEXP "^#speaker,|,#speaker,|,#speaker$|^#speaker$"', ['speaker' => $params['speaker']]);
    }
    if (!empty($params['venue_id'])) {
      $query->where('venue_id = #venue_id', ['venue_id' => $params['venue_id']]);
    }
    if (!empty($params['start_time'])) {
      $query->where('evs.start_time = @start_time', ['start_time' => $params["start_time"]]);
    }
    if (!empty($params['end_time'])) {
      $query->where('evs.end_time = @end_time', ['end_time' => $params["end_time"]]);
    }
    if (!empty($params['start_time_between']) && !empty($params['end_time_between'])) {
      $query->where('(@start_time >= evs.start_time AND @start_time < evs.end_time) OR (@end_time > start_time AND @end_time <= end_time)',
        [
          'start_time' => $params["start_time_between"],
          'end_time' => $params["end_time_between"]
        ]
      );
    }
    if (!empty($params['id_except'])) {
      $query->where('evs.id <> @id', ['id' => $params["id_except"]]);
    }

    return $query;
  }

  /**
   * Adds order params to query
   *
   * @param $query
   * @param array $params
   * @return mixed
   */
  private static function buildOrderQuery($query, $params = []) {
    if (!empty($params['options']['sort'])) {
      $sortParams = explode(' ', strtolower($params['options']['sort']));
      $availableFieldsToSort = ['title', 'start_time', 'venue_name'];
      $order = '';

      if (!empty($sortParams[1]) && ($sortParams[1] == 'desc' || $sortParams[1] == 'asc')) {
        $order = $sortParams[1];
      }
      if (in_array($sortParams[0], $availableFieldsToSort)) {
        $query->orderBy($sortParams[0] . ' ' . $order);
      }
    }

    return $query;
  }

  /**
   * Gets all data
   *
   * @param array $params
   *
   * @return array
   */
  public static function getAll($params = []) {
    $query = self::buildOrderQuery(self::buildWhereQuery(self::buildSelectQuery(), $params), $params);

    $query->limit(!empty($params['options']['limit']) ? $params['options']['limit'] : 25, !empty($params['options']['offset']) ? $params['options']['offset'] : 0);

    if (CRM_Core_Session::getLoggedInContactID()) {
      $query->select('COUNT(fes.id) > 0 as is_favourite');
      $query->join('fes', 'LEFT join civicrm_civimobile_favourite_event_session fes ON evs.id = fes.event_session_id AND fes.contact_id = ' . (int)CRM_Core_Session::getLoggedInContactID());
    }

    return CRM_Core_DAO::executeQuery($query->toSQL())->fetchAll();
  }

  /**
   * Deletes all venues in EventSessions by EventId
   *
   * @param $eventId
   * @return CRM_Core_DAO|object
   */
  public static function deleteAllVenues($eventId) {
    $query = "UPDATE " . self::getTableName() . " SET `venue_id`=NULL WHERE `event_id` = %1";
    return CRM_Core_DAO::executeQuery($query, [
      1 => [(int) $eventId, 'Integer']
    ]);
  }

  /**
   * Are speakers busy on other EventSessions
   *
   * @param array $speakerIds
   * @param $startTime
   * @param $endTime
   * @return bool
   */
  public static function isSpeakersBusy($speakerIds, $sessionId, $startTime, $endTime) {
    foreach ($speakerIds as $id) {
      if (self::isSpeakerBusy($id, $sessionId, $startTime, $endTime)) {
        return TRUE;
      }
    }
    return FALSE;
  }

  /**
   * Is speaker busy on other EventSessions
   *
   * @param integer $speakerId
   * @param $sessionId
   * @param $startTime
   * @param $endTime
   * @return bool
   */
  public static function isSpeakerBusy($speakerId, $sessionId, $startTime, $endTime) {
    $params = [
      'speaker' => $speakerId,
      'start_time_between' => $startTime,
      'end_time_between' => $endTime,
      'id_except' => $sessionId
    ];

    if (self::getCount($params) != 0) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Is venue booked on other EventSessions
   *
   * @param $venueId
   * @param $sessionId
   * @param $startTime
   * @param $endTime
   * @return bool
   */
  public static function isVenueBusy($venueId, $sessionId, $startTime, $endTime) {
    $params = [
      'venue_id' => $venueId,
      'start_time_between' => $startTime,
      'end_time_between' => $endTime,
      'id_except' => $sessionId
    ];

    if (self::getCount($params) != 0) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * Returns count of rows(Without limit and offset)
   * @param $params
   * @return mixed
   */
  public static function getCount($params) {
    $query = self::buildWhereQuery(self::buildSelectQuery(), $params);
    $query = CRM_Utils_SQL_Select::from('(' . $query->toSQL() . ') as a');
    $query->select('COUNT(*) as count');

    return CRM_Core_DAO::executeQuery($query->toSQL())->fetchAll()[0]['count'];
  }

}
