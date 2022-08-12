<?php
use CRM_Biaproperty_ExtensionUtil as E;

class CRM_Biaproperty_BAO_Property extends CRM_Biaproperty_DAO_Property {


public static function retrieve(array $params) {
     $options = [];
     $properties = \Civi\Api4\Property::get(FALSE)
      ->addSelect('id', 'address_id.name', 'address_id.street_address')
      ->addJoin('PropertyOwner AS property_owner', 'LEFT', ['property_owner.property_id', '=', 'id'])
      ->addClause('OR', ['property_owner.owner_id', 'IS NULL'], ['property_owner.owner_id', '!=', $params['owner_id']['!=']])
      //->addClause('address_id.name', 'LIKE', $params['name']['LIKE'])
      ->setLimit(100)
      ->execute();
    foreach ($properties as $property) {
      $options[$property['id']] = $property;
    }
    return $options;
}


  /**
   * Create a new Property based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Biaproperty_DAO_Property|NULL
   *
  public static function create($params) {
    $className = 'CRM_Biaproperty_DAO_Property';
    $entityName = 'Property';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */

}
