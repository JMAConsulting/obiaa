<?php

class CRM_CivirulesConditions_Participant_Status extends CRM_CivirulesConditions_Generic_Status {

  /**
   * The entity name (eg. Membership)
   * @return string
   */
  protected function getEntity() {
    return 'Participant';
  }

  /**
   * The entity status field (eg. membership_status_id)
   * @return string
   */
  public function getEntityStatusFieldName() {
    return 'participant_status_id';
  }

  /**
   * Returns an array of statuses as [ id => label ]
   * @param bool $active
   * @param bool $inactive
   *
   * @return array
   */
  public static function getEntityStatusList($active = TRUE, $inactive = FALSE) {
    $params['options']['limit'] = 0;
    if ($active && !$inactive) {
      $params['is_active'] = 1;
    }
    elseif ($inactive && !$active) {
      $params['is_active'] = 0;
    }
    $participantStatusList = civicrm_api3('ParticipantStatusType', 'get', $params);
    $statuses = array();
    foreach($participantStatusList['values'] as $status) {
      $statuses[$status['id']] = $status['label'];
    }
    return $statuses;
  }

}
