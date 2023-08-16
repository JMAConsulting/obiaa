<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_DAO_LocationVenue extends CRM_Core_DAO {

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  static $_tableName = 'civicrm_civimobile_location_venue';

  /**
   * Static entity name.
   *
   * @var string
   */
  static $entityName = 'LocationVenue';

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
   * Name of current venue
   *
   * @var string
   */
  public $name;

  /**
   * Is venue active
   *
   * @var boolean
   */
  public $is_active;

  /**
   * Location id for this venue
   *
   * @var integer
   */
  public $location_id;

  /**
   * Descriptions for venue
   *
   * @var integer
   */
  public $description;

  /**
   * Descriptions for venue
   *
   * @var integer
   */
  public $address_description;

  /**
   * Venue address
   *
   * @var integer
   */
  public $address;

  /**
   * Latitude geocode
   *
   * @var integer
   */
  public $latitude;

  /**
   * longitude geocode
   *
   * @var integer
   */
  public $longitude;

  /**
   * Venue background-color for calendar
   *
   * @var string
   */
  public $background_color;

  /**
   * Venue border-color for calendar
   *
   * @var string
   */
  public $border_color;

  /**
   * Venue weight
   *
   * @var string
   */
  public $weight;

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
          'bao' => 'CRM_CiviMobileAPI_BAO_LocationVenue',
        ],
        'name' => [
          'name' => 'name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Name'),
          'description' => 'Name',
          'required' => TRUE,
          'import' => TRUE,
          'where' => self::getTableName() . '.name',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => TRUE,
          'table_name' => self::getTableName(),
          'entity' => self::getEntityName(),
          'bao' => 'CRM_CiviMobileAPI_BAO_LocationVenue',
        ],
        'description' => [
          'name' => 'description',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Description'),
          'description' => 'Description',
          'required' => FALSE,
          'import' => TRUE,
          'where' => self::getTableName() . '.description',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => TRUE,
          'table_name' => self::getTableName(),
          'entity' => self::getEntityName(),
          'bao' => 'CRM_CiviMobileAPI_BAO_LocationVenue',
        ],
        'address_description' => [
          'name' => 'address_description',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Address Description'),
          'description' => 'Address Description',
          'required' => FALSE,
          'import' => TRUE,
          'where' => self::getTableName() . '.address_description',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => TRUE,
          'table_name' => self::getTableName(),
          'entity' => self::getEntityName(),
          'bao' => 'CRM_CiviMobileAPI_BAO_LocationVenue',
        ],
        'address' => [
          'name' => 'address',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Venue location'),
          'description' => 'Venue location',
          'required' => FALSE,
          'import' => TRUE,
          'where' => self::getTableName() . '.address',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => TRUE,
          'table_name' => self::getTableName(),
          'entity' => self::getEntityName(),
          'bao' => 'CRM_CiviMobileAPI_BAO_LocationVenue',
        ],
        'is_active' => [
          'name' => 'is_active',
          'type' => CRM_Utils_Type::T_BOOLEAN,
          'title' => E::ts('Is active'),
          'description' => 'Is active',
          'required' => TRUE,
          'import' => TRUE,
          'where' => self::getTableName() . '.is_active',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => TRUE,
          'table_name' => self::getTableName(),
          'entity' => self::getEntityName(),
          'bao' => 'CRM_CiviMobileAPI_BAO_LocationVenue',
        ],
        'location_id' => [
          'name' => 'location_id',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Location id'),
          'description' => 'Location id',
          'required' => TRUE,
          'import' => TRUE,
          'where' => self::getTableName() . '.location_id',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => TRUE,
          'table_name' => self::getTableName(),
          'entity' => self::getEntityName(),
          'bao' => 'CRM_CiviMobileAPI_BAO_LocationVenue',
        ],
        'latitude' => [
          'name' => 'latitude',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Latitude'),
          'description' => 'Latitude',
          'required' => FALSE,
          'import' => TRUE,
          'where' => self::getTableName() . '.latitude',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => TRUE,
          'table_name' => self::getTableName(),
          'entity' => self::getEntityName(),
          'bao' => 'CRM_CiviMobileAPI_BAO_LocationVenue',
        ],
        'longitude' => [
          'name' => 'longitude',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Longitude'),
          'description' => 'Longitude',
          'required' => FALSE,
          'import' => TRUE,
          'where' => self::getTableName() . '.longitude',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => TRUE,
          'table_name' => self::getTableName(),
          'entity' => self::getEntityName(),
          'bao' => 'CRM_CiviMobileAPI_BAO_LocationVenue',
        ],
        'background_color' => [
          'name' => 'background_color',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Background Color'),
          'description' => 'Background Color',
          'required' => TRUE,
          'import' => TRUE,
          'where' => self::getTableName() . '.background_color',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => TRUE,
          'table_name' => self::getTableName(),
          'entity' => self::getEntityName(),
          'bao' => 'CRM_CiviMobileAPI_BAO_LocationVenue',
        ],
        'border_color' => [
          'name' => 'border_color',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Border color'),
          'description' => 'Border color',
          'required' => TRUE,
          'import' => TRUE,
          'where' => self::getTableName() . '.border_color',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => TRUE,
          'table_name' => self::getTableName(),
          'entity' => self::getEntityName(),
          'bao' => 'CRM_CiviMobileAPI_BAO_LocationVenue',
        ],
        'weight' => [
          'name' => 'weight',
          'type' => CRM_Utils_Type::T_INT,
          'title' => E::ts('Weight'),
          'description' => 'Weight',
          'required' => TRUE,
          'import' => TRUE,
          'where' => self::getTableName() . '.weight',
          'headerPattern' => '',
          'dataPattern' => '',
          'export' => TRUE,
          'table_name' => self::getTableName(),
          'entity' => self::getEntityName(),
          'bao' => 'CRM_CiviMobileAPI_BAO_LocationVenue',
        ],
      ];

      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }

    return Civi::$statics[__CLASS__]['fields'];
  }

}
