<?php

class CRM_CiviMobileAPI_BAO_EventSessionSpeaker extends CRM_CiviMobileAPI_DAO_EventSessionSpeaker {

  /**
   * Adds params
   *
   * @param $params
   *
   * @return \CRM_Core_DAO
   */
  public static function add(&$params) {
    $entity = new CRM_CiviMobileAPI_DAO_LocationVenue();
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
  public static function &create($params) {
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
    $entity = new CRM_CiviMobileAPI_DAO_LocationVenue();
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
  private static function buildSelectQuery($returnValue = 'rows') {
    $query = CRM_Utils_SQL_Select::from(self::getTableName() . ' esspeaker');

    if ($returnValue == 'rows') {
      $query->select('
        esspeaker.id,
        esspeaker.event_session_id,
        esspeaker.speaker_id
      ');
    } else {
      if ($returnValue == 'count') {
        $query->select('COUNT(id)');
      }
    }

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
      $query->where('id = #id', ['id' => $params['id']]);
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
    $query = self::buildWhereQuery(self::buildSelectQuery(), $params);
    return CRM_Core_DAO::executeQuery($query->toSQL())->fetchAll();
  }

  /**
   * Returns query for speakers by event
   *
   * @param $params
   * @return mixed
   */
  private static function getSpeakersBelongedToSessionsByEventQuery($params) {
    $query = self::buildOrderQuery(self::buildSelectQuery(), $params);
    $query->where('event_session_id IN (SELECT id FROM civicrm_civimobile_event_session WHERE event_id=#1)', [
      1 => $params['event_id']
    ]);
    $query->select('participant.id as participant_id,
                    contact.first_name,
                    contact.last_name,
                    contact.organization_name,
                    contact.job_title,
                    contact.image_URL,
                    contact.employer_id,
                    contact.display_name');
    $query->join('participant', 'INNER join civicrm_participant participant ON participant.id = esspeaker.speaker_id');
    $query->join('contact', 'LEFT join civicrm_contact contact ON contact.id = participant.contact_id');

    if (!empty($params['participant_id'])) {
      $query->where('participant.id = #1', [
        1 => $params['participant_id']
      ]);
    }

    $query->groupBy('esspeaker.speaker_id');

    return $query;
  }

  /**
   * Returns speakers by event
   *
   * @param $params
   * @return mixed
   */
  public static function getSpeakersBelongedToSessionsByEvent($params) {
    $query = self::getSpeakersBelongedToSessionsByEventQuery($params);

    $limit = isset($params['options']['limit']) ? $params['options']['limit'] : 25;
    $offset = isset($params['options']['offset']) ? $params['options']['offset'] : 0;

    if ($limit != 0) {
      $query->limit($limit, $offset);
    } else {
      return CRM_Core_DAO::executeQuery($query->toSQL())->fetchAll();
    }
    
    return CRM_Core_DAO::executeQuery($query->toSQL())->fetchAll();
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
      $availableFieldsToSort = ['display_name', 'job_title', 'organization_name'];
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
   * Returns count of rows(Without limit and offset)
   * @param $params
   * @return mixed
   */
  public static function getCountSpeakersBelongedToEvent($params) {
    $query = self::getSpeakersBelongedToSessionsByEventQuery($params);
    $query = CRM_Utils_SQL_Select::from('(' . $query->toSQL() . ') as a');
    $query->select('COUNT(*) as count');

    return CRM_Core_DAO::executeQuery($query->toSQL())->fetchAll()[0]['count'];
  }

  /**
   * Deletes all records about speakers by ParticipantID
   *
   * @param integer $participantId
   */
  public static function deleteAllSpeakersByParticipantId($participantId) {
    $sql = "DELETE FROM " . self::getTableName() . " WHERE speaker_id=" . $participantId;
    CRM_Core_DAO::executeQuery($sql);
  }

}
