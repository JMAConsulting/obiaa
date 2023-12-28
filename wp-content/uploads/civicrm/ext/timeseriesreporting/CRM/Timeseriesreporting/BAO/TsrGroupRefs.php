<?php
// phpcs:disable
use CRM_Timeseriesreporting_ExtensionUtil as E;
// phpcs:enable

class CRM_Timeseriesreporting_BAO_TsrGroupRefs extends CRM_Timeseriesreporting_DAO_TsrGroupRefs {

  /**
   * Create a new TsrGroupRefs based on array-data
   *
   * @param array $params key-value pairs
   * @return CRM_Timeseriesreporting_DAO_TsrGroupRefs|NULL
   */
  /*
  public static function create($params) {
    $className = 'CRM_Timeseriesreporting_DAO_TsrGroupRefs';
    $entityName = 'TsrGroupRefs';
    $hook = empty($params['id']) ? 'create' : 'edit';

    CRM_Utils_Hook::pre($hook, $entityName, CRM_Utils_Array::value('id', $params), $params);
    $instance = new $className();
    $instance->copyValues($params);
    $instance->save();
    CRM_Utils_Hook::post($hook, $entityName, $instance->id, $instance);

    return $instance;
  }
  */

}
