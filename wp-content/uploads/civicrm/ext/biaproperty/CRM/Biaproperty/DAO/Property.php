<?php

/**
 * DAOs provide an OOP-style facade for reading and writing database records.
 *
 * DAOs are a primary source for metadata in older versions of CiviCRM (<5.74)
 * and are required for some subsystems (such as APIv3).
 *
 * This stub provides compatibility. It is not intended to be modified in a
 * substantive way. Property annotations may be added, but are not required.
 * @property string $id 
 * @property string $roll_no 
 * @property string $created_id 
 * @property string $property_address 
 * @property string $name 
 * @property string $city 
 * @property string $postal_code 
 * @property string $modified_id 
 * @property string $modified_date 
 * @property string $source_record_id 
 * @property string $source_record 
 */
class CRM_Biaproperty_DAO_Property extends CRM_Biaproperty_DAO_Base {

  /**
   * Required by older versions of CiviCRM (<5.74).
   * @var string
   */
  public static $_tableName = 'civicrm_property';

}
