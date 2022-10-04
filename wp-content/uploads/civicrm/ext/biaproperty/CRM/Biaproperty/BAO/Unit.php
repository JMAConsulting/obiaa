<?php
use CRM_Biaproperty_ExtensionUtil as E;
use Civi\Api4\Address;

class CRM_Biaproperty_BAO_Unit extends CRM_Biaproperty_DAO_Unit {

  /**
   * Used for Unit Address auto complete search
   * Find addresses where either the street address or the street unit contain the input value
   * @param array $params
   *
   * @return array
   */
  public static function unitAddressRetrieve(array $params) {
    $options = [];
    if (isset($params['property_id'])) {
      $properties = Address::get(FALSE)
        ->addClause('OR', ['street_address', 'LIKE', $params['street_address']['LIKE']], ['street_unit', 'LIKE', $params['street_address']['LIKE']])
        ->addJoin('Unit AS unit', 'INNER', ['unit.address_id', '=', 'id'])->addWhere('unit.property_id', '=', trim($params['property_id']))
        ->setLimit(100)->execute();
    }
    else {
      $properties = Address::get(FALSE)
        ->addClause('OR', ['street_address', 'LIKE', $params['street_address']['LIKE']], ['street_unit', 'LIKE', $params['street_address']['LIKE']])
        ->setLimit(100)->execute();
    }
    foreach ($properties as $property) {
      $options[$property['id']] = $property;
    }
    return $options;
  }

  /**
   * Create a new Unit based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Biaproperty_DAO_Unit|NULL
   *
  public static function create($params) {
    $className = 'CRM_Biaproperty_DAO_Unit';
    $entityName = 'Unit';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */

}
