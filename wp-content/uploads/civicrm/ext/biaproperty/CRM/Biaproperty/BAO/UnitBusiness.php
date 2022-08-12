<?php
use CRM_Biaproperty_ExtensionUtil as E;

class CRM_Biaproperty_BAO_UnitBusiness extends CRM_Biaproperty_DAO_UnitBusiness {

  /**
   * Create a new UnitBusiness based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Biaproperty_DAO_UnitBusiness|NULL
   *
  public static function create($params) {
    $className = 'CRM_Biaproperty_DAO_UnitBusiness';
    $entityName = 'UnitBusiness';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  } */

}
