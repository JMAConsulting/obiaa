<?php

use Civi\Api4\Utils\CoreUtil;

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
    if (CoreUtil::getApiClass('CaseActivity'))
    {
      return civicrm_api4('CaseActivity', 'get', [
        'where' => [
          ['activity_id', '=', $activityId],
        ],
        'checkPermissions' => FALSE,
      ])->count() > 0;
    }

    return false;
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
    return civicrm_api4('OptionValue', 'get', [
      'where' => [
        ['option_group_id:name', '=', 'activity_type'],
      ],
      'checkPermissions' => FALSE,
    ])->getArrayCopy();
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
