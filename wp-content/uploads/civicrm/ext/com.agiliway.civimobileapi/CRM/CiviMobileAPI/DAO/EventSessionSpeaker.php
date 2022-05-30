<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_DAO_EventSessionSpeaker extends CRM_Core_DAO {

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  static $_tableName = 'civicrm_civimobile_event_session_speaker';

  /**
   * Static entity name.
   *
   * @var string
   */
  static $entityName = 'EventSessionSpeaker';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log
   * table.
   *
   * @var boolean
   */
  static $_log = TRUE;

  /**
   * Unique id of current row
   *
   * @var int
   */
  public $id;

  /**
   * EventSessionID
   *
   * @var int
   */
  public $event_session_id;

  /**
   * SpeakerID
   *
   * @var int
   */
  public $speaker_id;

  /**
   * Returns the names of this table
   *
   * @return string
   */
  static function getTableName() {
    return self::$_tableName;
  }

  /**
   * Returns entity name
   *
   * @return string
   */
  static function getEntityName() {
    return self::$entityName;
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('id'),
          'description' => 'id',
          'required' => TRUE,
          'import' => TRUE,
          'where' => self::getTableName() . '.id',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => TRUE,
          'table_name' => self::getTableName(),
          'entity' => self::getEntityName(),
          'bao' => 'CRM_CiviMobileAPI_BAO_EventSessionSpeaker',
        ],
        'event_session_id' => [
          'name' => 'event_session_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('EventSessionId'),
          'description' => 'EventSessionId',
          'required' => TRUE,
          'import' => TRUE,
          'where' => self::getTableName() . '.event_session_id',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => TRUE,
          'table_name' => self::getTableName(),
          'entity' => self::getEntityName(),
          'bao' => 'CRM_CiviMobileAPI_BAO_EventSessionSpeaker',
        ],
        'speaker_id' => [
          'name' => 'speaker_id',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('SpeakerID'),
          'description' => 'SpeakerID',
          'required' => FALSE,
          'import' => TRUE,
          'where' => self::getTableName() . '.speaker_id',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => TRUE,
          'table_name' => self::getTableName(),
          'entity' => self::getEntityName(),
          'bao' => 'CRM_CiviMobileAPI_BAO_EventSessionSpeaker',
        ]
      ];

      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }

    return Civi::$statics[__CLASS__]['fields'];
  }

}
