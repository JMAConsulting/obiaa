<?php

/**
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 *
 * Generated from biaproperty/xml/schema/CRM/Biaproperty/Property.xml
 * DO NOT EDIT.  Generated by CRM_Core_CodeGen
 * (GenCodeChecksum:92ce9db8f3c3eb4d96c63b83352c3192)
 */
use CRM_Biaproperty_ExtensionUtil as E;

/**
 * Database access object for the Property entity.
 */
class CRM_Biaproperty_DAO_Property extends CRM_Core_DAO {
  const EXT = E::LONG_NAME;
  const TABLE_ADDED = '';

  /**
   * Static instance to hold the table name.
   *
   * @var string
   */
  public static $_tableName = 'civicrm_property';

  /**
   * Should CiviCRM log any modifications to this table in the civicrm_log table.
   *
   * @var bool
   */
  public static $_log = TRUE;

  /**
   * Paths for accessing this entity in the UI.
   *
   * @var string[]
   */
  protected static $_paths = [
    'add' => 'civicrm/property/form?reset=1&action=add',
    'view' => 'civicrm/property/form?reset=1&action=view&id=[id]',
    'update' => 'civicrm/property/form?reset=1&action=update&id=[id]',
    'delete' => 'civicrm/property/form?reset=1&action=delete&id=[id]',
  ];

  /**
   * Unique Property ID
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $id;

  /**
   * Roll #
   *
   * @var string
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $roll_no;

  /**
   * FK to Contact
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $created_id;

  /**
   * Property Tax Roll Address
   *
   * @var string|null
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $property_address;

  /**
   * Property Name
   *
   * @var string|null
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $name;

  /**
   * City this property is in
   *
   * @var string|null
   *   (SQL type: varchar(64))
   *   Note that values will be retrieved from the database as a string.
   */
  public $city;

  /**
   * postal code this property is in
   *
   * @var string|null
   *   (SQL type: varchar(64))
   *   Note that values will be retrieved from the database as a string.
   */
  public $postal_code;

  /**
   * FK to Contact
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $modified_id;

  /**
   * When was the property was created or modified or deleted.
   *
   * @var string
   *   (SQL type: timestamp)
   *   Note that values will be retrieved from the database as a string.
   */
  public $modified_date;

  /**
   * Field used to handle sync processes
   *
   * @var int|string|null
   *   (SQL type: int unsigned)
   *   Note that values will be retrieved from the database as a string.
   */
  public $source_record_id;

  /**
   * Field used to handle sync processes
   *
   * @var string|null
   *   (SQL type: varchar(255))
   *   Note that values will be retrieved from the database as a string.
   */
  public $source_record;

  /**
   * Class constructor.
   */
  public function __construct() {
    $this->__table = 'civicrm_property';
    parent::__construct();
  }

  /**
   * Returns localized title of this entity.
   *
   * @param bool $plural
   *   Whether to return the plural version of the title.
   */
  public static function getEntityTitle($plural = FALSE) {
    return $plural ? E::ts('Properties') : E::ts('Property');
  }

  /**
   * Returns foreign keys and entity references.
   *
   * @return array
   *   [CRM_Core_Reference_Interface]
   */
  public static function getReferenceColumns() {
    if (!isset(Civi::$statics[__CLASS__]['links'])) {
      Civi::$statics[__CLASS__]['links'] = static::createReferenceColumns(__CLASS__);
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'created_id', 'civicrm_contact', 'id');
      Civi::$statics[__CLASS__]['links'][] = new CRM_Core_Reference_Basic(self::getTableName(), 'modified_id', 'civicrm_contact', 'id');
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'links_callback', Civi::$statics[__CLASS__]['links']);
    }
    return Civi::$statics[__CLASS__]['links'];
  }

  /**
   * Returns all the column names of this table
   *
   * @return array
   */
  public static function &fields() {
    if (!isset(Civi::$statics[__CLASS__]['fields'])) {
      Civi::$statics[__CLASS__]['fields'] = [
        'id' => [
          'name' => 'id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => E::ts('Unique Property ID'),
          'required' => TRUE,
          'where' => 'civicrm_property.id',
          'table_name' => 'civicrm_property',
          'entity' => 'Property',
          'bao' => 'CRM_Biaproperty_DAO_Property',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'roll_no' => [
          'name' => 'roll_no',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Roll No'),
          'description' => E::ts('Roll #'),
          'required' => FALSE,
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_property.roll_no',
          'table_name' => 'civicrm_property',
          'entity' => 'Property',
          'bao' => 'CRM_Biaproperty_DAO_Property',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => NULL,
        ],
        'created_id' => [
          'name' => 'created_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => E::ts('FK to Contact'),
          'where' => 'civicrm_property.created_id',
          'table_name' => 'civicrm_property',
          'entity' => 'Property',
          'bao' => 'CRM_Biaproperty_DAO_Property',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'html' => [
            'type' => 'EntityRef',
          ],
          'add' => NULL,
        ],
        'property_address' => [
          'name' => 'property_address',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Property Address'),
          'description' => E::ts('Property Tax Roll Address'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_property.property_address',
          'table_name' => 'civicrm_property',
          'entity' => 'Property',
          'bao' => 'CRM_Biaproperty_DAO_Property',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => NULL,
        ],
        'name' => [
          'name' => 'name',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Name'),
          'description' => E::ts('Property Name'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_property.name',
          'table_name' => 'civicrm_property',
          'entity' => 'Property',
          'bao' => 'CRM_Biaproperty_DAO_Property',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => NULL,
        ],
        'city' => [
          'name' => 'city',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('City'),
          'description' => E::ts('City this property is in'),
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
          'where' => 'civicrm_property.city',
          'table_name' => 'civicrm_property',
          'entity' => 'Property',
          'bao' => 'CRM_Biaproperty_DAO_Property',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => NULL,
        ],
        'postal_code' => [
          'name' => 'postal_code',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Postal Code'),
          'description' => E::ts('postal code this property is in'),
          'maxlength' => 64,
          'size' => CRM_Utils_Type::BIG,
          'where' => 'civicrm_property.postal_code',
          'table_name' => 'civicrm_property',
          'entity' => 'Property',
          'bao' => 'CRM_Biaproperty_DAO_Property',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => NULL,
        ],
        'modified_id' => [
          'name' => 'modified_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => E::ts('FK to Contact'),
          'where' => 'civicrm_property.modified_id',
          'table_name' => 'civicrm_property',
          'entity' => 'Property',
          'bao' => 'CRM_Biaproperty_DAO_Property',
          'localizable' => 0,
          'FKClassName' => 'CRM_Contact_DAO_Contact',
          'html' => [
            'type' => 'EntityRef',
          ],
          'add' => NULL,
        ],
        'modified_date' => [
          'name' => 'modified_date',
          'type' => CRM_Utils_Type::T_TIMESTAMP,
          'title' => E::ts('Modified Date'),
          'description' => E::ts('When was the property was created or modified or deleted.'),
          'required' => FALSE,
          'where' => 'civicrm_property.modified_date',
          'default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
          'table_name' => 'civicrm_property',
          'entity' => 'Property',
          'bao' => 'CRM_Biaproperty_DAO_Property',
          'localizable' => 0,
          'html' => [
            'label' => E::ts("Modified Date"),
          ],
          'readonly' => TRUE,
          'add' => NULL,
        ],
        'source_record_id' => [
          'name' => 'source_record_id',
          'type' => CRM_Utils_Type::T_INT,
          'description' => E::ts('Field used to handle sync processes'),
          'where' => 'civicrm_property.source_record_id',
          'default' => NULL,
          'table_name' => 'civicrm_property',
          'entity' => 'Property',
          'bao' => 'CRM_Biaproperty_DAO_Property',
          'localizable' => 0,
          'html' => [
            'type' => 'Number',
          ],
          'add' => NULL,
        ],
        'source_record' => [
          'name' => 'source_record',
          'type' => CRM_Utils_Type::T_STRING,
          'title' => E::ts('Source Record'),
          'description' => E::ts('Field used to handle sync processes'),
          'maxlength' => 255,
          'size' => CRM_Utils_Type::HUGE,
          'where' => 'civicrm_property.source_record',
          'default' => NULL,
          'table_name' => 'civicrm_property',
          'entity' => 'Property',
          'bao' => 'CRM_Biaproperty_DAO_Property',
          'localizable' => 0,
          'html' => [
            'type' => 'Text',
          ],
          'add' => NULL,
        ],
      ];
      CRM_Core_DAO_AllCoreTables::invoke(__CLASS__, 'fields_callback', Civi::$statics[__CLASS__]['fields']);
    }
    return Civi::$statics[__CLASS__]['fields'];
  }

  /**
   * Return a mapping from field-name to the corresponding key (as used in fields()).
   *
   * @return array
   *   Array(string $name => string $uniqueName).
   */
  public static function &fieldKeys() {
    if (!isset(Civi::$statics[__CLASS__]['fieldKeys'])) {
      Civi::$statics[__CLASS__]['fieldKeys'] = array_flip(CRM_Utils_Array::collect('name', self::fields()));
    }
    return Civi::$statics[__CLASS__]['fieldKeys'];
  }

  /**
   * Returns the names of this table
   *
   * @return string
   */
  public static function getTableName() {
    return self::$_tableName;
  }

  /**
   * Returns if this table needs to be logged
   *
   * @return bool
   */
  public function getLog() {
    return self::$_log;
  }

  /**
   * Returns the list of fields that can be imported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &import($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getImports(__CLASS__, 'property', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of fields that can be exported
   *
   * @param bool $prefix
   *
   * @return array
   */
  public static function &export($prefix = FALSE) {
    $r = CRM_Core_DAO_AllCoreTables::getExports(__CLASS__, 'property', $prefix, []);
    return $r;
  }

  /**
   * Returns the list of indices
   *
   * @param bool $localize
   *
   * @return array
   */
  public static function indices($localize = TRUE) {
    $indices = [
      'UI_property_address' => [
        'name' => 'UI_property_address',
        'field' => [
          0 => 'property_address',
        ],
        'localizable' => FALSE,
        'unique' => TRUE,
        'sig' => 'civicrm_property::1::property_address',
      ],
      'UI_source_record_id' => [
        'name' => 'UI_source_record_id',
        'field' => [
          0 => 'source_record_id',
          1 => 'source_record',
        ],
        'localizable' => FALSE,
        'unique' => TRUE,
        'sig' => 'civicrm_property::1::source_record_id::source_record',
      ],
    ];
    return ($localize && !empty($indices)) ? CRM_Core_DAO_AllCoreTables::multilingualize(__CLASS__, $indices) : $indices;
  }

}
