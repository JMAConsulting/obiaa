<?php

class CRM_CiviMobileAPI_Utils_CustomGroup {

  /**
   * Deletes custom group by name
   *
   * @param $customGroupName
   */
  public static function delete($customGroupName) {
    $customGroupID = civicrm_api3('CustomGroup', 'get', ['name' => $customGroupName]);
    if (!empty($customGroupID["values"])) {
      foreach ($customGroupID["values"] as $group) {
        civicrm_api3('CustomGroup', 'delete', ['id' => $group['id']]);
      }
    }
  }

  /**
   * Deletes custom group
   *
   * @param $customGroup
   *
   * @throws \CiviCRM_API3_Exception
   */
  public static function deleteCustomGroup($customGroup) {
    $customGroupID = civicrm_api3('CustomGroup', 'get', [
      'return' => "id",
      'name' => $customGroup,
    ]);
    if (!empty($customGroupID["values"])) {
      $id = array_shift($customGroupID['values'])['id'];
      civicrm_api3('CustomGroup', 'delete', ['id' => $id]);
    }
  }

}

