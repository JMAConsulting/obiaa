<?php

class CRM_CiviMobileAPI_Utils_Statistic_Utils {

  /**
   * Get renewal membership Ids
   *
   * @return array
   */
  public static function getRenewalMembershipIds() {
    $renewalMembershipsId = [];

    try {
      $renewalActivities = civicrm_api3('Activity', 'get', [
        'sequential' => 1,
        'return' => ["source_record_id", "activity_type_id", "activity_date_time"],
        'activity_type_id' => "Membership Renewal",
        'options' => ['limit' => 0],
      ])['values'];
    } catch (CiviCRM_API3_Exception $e) {
      return [];
    }

    if (!empty($renewalActivities)) {
      foreach ($renewalActivities as $renewalActivity) {
        $renewalMembershipsId[] = $renewalActivity['source_record_id'];
      }
    }

    return $renewalMembershipsId;
  }

  /**
   * Get membership contact Ids
   *
   * @return array
   */
  public static function getListOfMembershipContactIds() {
    $membershipsTable = CRM_Member_DAO_Membership::getTableName();
    $contactsId = [];

    try {
      $membershipsContactIds = CRM_Core_DAO::executeQuery("SELECT DISTINCT(contact_id) FROM $membershipsTable RIGHT JOIN civicrm_contact ON civicrm_membership.contact_id = civicrm_contact.id AND civicrm_contact.is_deleted = 0 WHERE civicrm_membership.contact_id IS NOT NULL")->fetchAll();
    } catch (Exception $e) {
      return [];
    }

    if (!empty($membershipsContactIds)) {
      foreach ($membershipsContactIds as $membershipContactId) {
        $contactsId[] = $membershipContactId['contact_id'];
      }
    }

    return $contactsId;
  }

  /**
   * Returns contribution date interval
   *
   * @return array
   */
  public static function getDefaultContributionDateInterval() {
    $dates = [
      'min_receive_date' => '',
      'max_receive_date' => ''
    ];

    try {
      $dao = CRM_Core_DAO::executeQuery('SELECT MIN(civicrm_contribution.receive_date) as min_receive_date, MAX(civicrm_contribution.receive_date) as max_receive_date FROM civicrm_contribution');

      if ($dao->fetch()) {
        $dates['min_receive_date'] = $dao->min_receive_date;
        $dates['max_receive_date'] = ((int)date('Y', strtotime($dao->max_receive_date)) + 1) . '-01-01';
      }
    } catch (Exception $e) {}

    return $dates;
  }

  /**
   * Explodes and trims string
   *
   * @param $string
   * @return array
   */
  public static function explodesString($string) {
    return !empty($string) ? explode(",&nbsp;", $string) : [];
  }
}
