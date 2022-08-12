<?php
use CRM_Biaproperty_ExtensionUtil as E;

class CRM_Biaproperty_BAO_Unit extends CRM_Biaproperty_DAO_Unit {

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
