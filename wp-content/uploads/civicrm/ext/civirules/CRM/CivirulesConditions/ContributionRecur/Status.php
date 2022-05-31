<?php

class CRM_CivirulesConditions_ContributionRecur_Status extends CRM_CivirulesConditions_Generic_Status {

  /**
   * The entity name (eg. Membership)
   * @return string
   */
  protected function getEntity() {
    return 'ContributionRecur';
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
    $return = [];
    $params = [
      'return' => ["label", "value"],
      'option_group_id' => "contribution_recur_status",
      'options' => ['limit' => 0, 'sort' => "label ASC"],
    ];
    if ($active && !$inactive) {
      $params['is_active'] = 1;
    }
    elseif ($inactive && !$active) {
      $params['is_active'] = 0;
    }

    try {
      $options = civicrm_api3('OptionValue', 'get', $params)['values'];
      foreach ($options as $option) {
        $return[$option['value']] = $option['label'];
      }
    } catch (CiviCRM_API3_Exception $ex) {}
    return $return;
  }

}
