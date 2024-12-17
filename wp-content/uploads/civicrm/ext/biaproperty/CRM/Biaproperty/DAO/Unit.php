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
 * @property string $unit_size 
 * @property string $unit_price 
 * @property string $unit_status 
 * @property string $mls_listing_link 
 * @property string $unit_photo 
 * @property string $unit_location 
 * @property string $address_id 
 * @property string $property_id 
 * @property string $source_record_id 
 * @property string $source_record 
 */
class CRM_Biaproperty_DAO_Unit extends CRM_Core_DAO_Base {

  /**
   * Required by older versions of CiviCRM (<5.74).
   * @var string
   */
  public static $_tableName = 'civicrm_unit';

}
