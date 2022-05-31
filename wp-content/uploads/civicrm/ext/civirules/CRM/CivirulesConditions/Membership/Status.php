<?php

class CRM_CivirulesConditions_Membership_Status extends CRM_CivirulesConditions_Generic_Status {

  /**
   * The entity name (eg. Membership)
   * @return string
   */
  protected function getEntity() {
    return 'Membership';
  }

  /**
   * The entity status field (eg. membership_status_id)
   * @return string
   */
  public function getEntityStatusFieldName() {
    return 'status_id';
  }

  /**
   * Returns an array of statuses as [ id => label ]
   * @param bool $active
   * @param bool $inactive
   *
   * @return array
   */
  public static function getEntityStatusList($active = TRUE, $inactive = FALSE) {
    $return = [];
    $params = [];
    if ($active && !$inactive) {
      $params = ['is_active' => 1];
    }
    elseif ($inactive && !$active) {
      $params = ['is_active' => 0];
    }
    $params['options'] = ['limit' => 0, 'sort' => "label ASC"];

    try {
      $apiMembershipStatus = civicrm_api3("MembershipStatus", "Get", $params)['values'];
      foreach ($apiMembershipStatus as $membershipStatus) {
        $return[$membershipStatus['id']] = $membershipStatus['label'];
      }
    } catch (CiviCRM_API3_Exception $ex) {}
    return $return;
  }

}
