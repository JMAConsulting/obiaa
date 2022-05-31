<?php

class CRM_CivirulesConditions_Contribution_Status extends CRM_CivirulesConditions_Generic_Status {

  /**
   * The entity name (eg. Membership)
   * @return string
   */
  protected function getEntity() {
    return 'Contribution';
  }

  /**
   * The entity status field (eg. membership_status_id)
   * @return string
   */
  public function getEntityStatusFieldName() {
    return 'contribution_status_id';
  }

  /**
   * Returns an array of statuses as [ id => label ]
   * @param bool $active
   * @param bool $inactive
   *
   * @return array
   */
  public static function getEntityStatusList($active = TRUE, $inactive = FALSE) {
    return parent::getEntityStatusListFromOptionGroup('contribution_status', $active, $inactive);
  }

}
