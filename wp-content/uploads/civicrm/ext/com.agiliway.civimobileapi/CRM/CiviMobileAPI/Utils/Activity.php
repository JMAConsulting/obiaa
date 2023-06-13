<?php

/**
 * Class provide Activity helper methods
 */
class CRM_CiviMobileAPI_Utils_Activity {

  /**
   * Cache for activity types
   *
   * @var array|null
   */
  protected static $activityTypes = null;
  
  /**
   * @param $activityId
   * @return bool
   */
  public static function isActivityInCase($activityId) {
    return CRM_Core_DAO::executeQuery('SELECT id FROM civicrm_case_activity WHERE activity_id = %1', [
      1 => [$activityId, 'Integer'],
    ])->fetch();
  }
  
  /**
   * Gets activity types from cache(if exist)
   *
   * @return array
   */
  public static function getTypes() {
    if (!isset(self::$activityTypes)) {
      self::$activityTypes = self::getTypesFromDb();
    }
    
    return self::$activityTypes;
  }

  /**
   * Gets activity type from database
   *
   * @return array
   */
  public static function getTypesFromDb() {
    try {
      $result = civicrm_api3('OptionValue', 'get', [
        'sequential' => 1,
        'option_group_id' => "activity_type",
        'options' => ['limit' => 0],
      ]);
    } catch (CiviCRM_API3_Exception $e) {
      return [];
    }

    return $result['values'];
  }
  
  public static function getAssignCaseRoleValue() {
    foreach (self::getTypes() as $type) {
      if ($type['name'] == 'Assign Case Role') {
        return $type['value'];
      }
    }
  
    return null;
  }

  /**
   * @return array
   */
  public static function getChangeCaseStartDateValue() {
    foreach (self::getTypes() as $type) {
      if ($type['name'] == 'Change Case Start Date') {
        return $type['value'];
      }
    }

    return null;
  }
  
  /**
   * @return array
   */
  public static function getEventRegistrationValue() {
    foreach (self::getTypes() as $type) {
      if ($type['name'] == 'Event Registration') {
        return $type['value'];
      }
    }
    
    return null;
  }
  
  /**
   * @return array
   */
  public static function getContributionValue() {
    foreach (self::getTypes() as $type) {
      if ($type['name'] == 'Contribution') {
        return $type['value'];
      }
    }
    
    return null;
  }

  /**
   * @return array
   */
  public static function getReassignedCaseValue() {
    foreach (self::getTypes() as $type) {
      if ($type['name'] == 'Reassigned Case') {
        return $type['value'];
      }
    }

    return null;
  }

  /**
   * @return array
   */
  public static function getOpenCaseValue() {
    foreach (self::getTypes() as $type) {
      if ($type['name'] == 'Open Case') {
        return $type['value'];
      }
    }

    return null;
  }

}
