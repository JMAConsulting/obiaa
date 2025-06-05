<?php

class CRM_CiviMobileAPI_Utils_Statistic_Utils {

  /**
   * Get renewal membership Ids
   *
   * @return array
   */
  public static function getRenewalMembershipIds() {
    $renewalMembershipsId = [];

    $renewalActivities = civicrm_api4('Activity', 'get', [
      'select' => [
        'source_record_id',
        'activity_type_id',
        'activity_date_time',
      ],
      'where' => [
        ['activity_type_id:name', '=', 'Membership Renewal'],
      ],
      'checkPermissions' => FALSE,
    ])->getArrayCopy();

    foreach ($renewalActivities as $renewalActivity) {
      $renewalMembershipsId[] = $renewalActivity['source_record_id'];
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
    $contributionsDate = civicrm_api4('Contribution', 'get', [
      'select' => [
        'MIN(receive_date) AS min_receive_date',
        'MAX(receive_date) AS max_receive_date',
      ],
      'checkPermissions' => FALSE,
    ])->first();

    $contributionsDate['max_receive_date'] = ((int)date('Y', strtotime($contributionsDate['max_receive_date'])) + 1) . '-01-01';

    return $contributionsDate;
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
