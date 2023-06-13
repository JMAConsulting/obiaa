<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_DAO_EventSession extends CRM_Core_DAO {

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  static $_tableName = 'civicrm_civimobile_event_session';

  /**
   * Static entity name.
   *
   * @var string
   */
  static $entityName = 'EventSession';

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
   * Title of current EventSession
   *
   * @var string
   */
  public $title;

  /**
   * EventSession description
   *
   * @var string
   */
  public $description;

  /**
   * EventSession start time
   *
   * @var string
   */
  public $start_time;

  /**
   * EventSession end time
   *
   * @var string
   */
  public $end_time;

  /**
   * Id of event that includes EventSession
   *
   * @var int
   */
  public $event_id;

  /**
   * Id of venue which belongs to EventSession
   *
   * @var int
   */
  public $venue_id;

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
          'bao' => 'CRM_CiviMobileAPI_BAO_EventSession',
        ],
        'title' => [
          'name' => 'title',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Title'),
          'description' => 'Title',
          'required' => TRUE,
          'import' => TRUE,
          'where' => self::getTableName() . '.title',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => TRUE,
          'table_name' => self::getTableName(),
          'entity' => self::getEntityName(),
          'bao' => 'CRM_CiviMobileAPI_BAO_EventSession',
        ],
        'description' => [
          'name' => 'description',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Description'),
          'description' => 'Description',
          'required' => TRUE,
          'import' => TRUE,
          'where' => self::getTableName() . '.description',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => TRUE,
          'table_name' => self::getTableName(),
          'entity' => self::getEntityName(),
          'bao' => 'CRM_CiviMobileAPI_BAO_EventSession',
        ],
        'start_time' => [
          'name' => 'start_time',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('Start time'),
          'description' => 'Start time',
          'required' => TRUE,
          'import' => TRUE,
          'where' => self::getTableName() . '.start_time',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => TRUE,
          'table_name' => self::getTableName(),
          'entity' => self::getEntityName(),
          'bao' => 'CRM_CiviMobileAPI_BAO_EventSession',
        ],
        'end_time' => [
          'name' => 'end_time',
          'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
          'title' => E::ts('End time'),
          'description' => 'End time',
          'required' => TRUE,
          'import' => TRUE,
          'where' => self::getTableName() . '.end_time',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => TRUE,
          'table_name' => self::getTableName(),
          'entity' => self::getEntityName(),
          'bao' => 'CRM_CiviMobileAPI_BAO_EventSession',
        ],
        'event_id' => [
          'name' => 'event_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Event id'),
          'description' => 'Event id',
          'required' => TRUE,
          'import' => TRUE,
          'where' => self::getTableName() . '.event_id',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => TRUE,
          'table_name' => self::getTableName(),
          'entity' => self::getEntityName(),
          'bao' => 'CRM_CiviMobileAPI_BAO_EventSession',
        ],
        'venue_id' => [
          'name' => 'venue_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Venue id'),
          'description' => 'Venue id',
          'required' => TRUE,
          'import' => TRUE,
          'where' => self::getTableName() . '.venue_id',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => TRUE,
          'table_name' => self::getTableName(),
          'entity' => self::getEntityName(),
          'bao' => 'CRM_CiviMobileAPI_BAO_EventSession',
        ],
      ];

      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }

    return Civi::$statics[__CLASS__]['fields'];
  }

}
