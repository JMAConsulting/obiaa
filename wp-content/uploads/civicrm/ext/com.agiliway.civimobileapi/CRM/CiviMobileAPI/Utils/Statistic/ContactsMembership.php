<?php

class CRM_CiviMobileAPI_Utils_Statistic_ContactsMembership {

  /**
   * Get new or current membership count
   *
   * @param $contactsId
   * @param $params
   * @param $status
   * @param bool $isRenewal
   * @return int
   */
  public static function getMembershipsCount($contactsId, $params, $status = NULL, $isRenewal = FALSE) {
    $prepareReceiveDate = (new CRM_CiviMobileAPI_Utils_Statistic_ChartBar)->getPrepareReceiveDate($params);
    $startDate = $prepareReceiveDate['start_date'];
    $endDate = $prepareReceiveDate['end_date'];
    $renewalMembershipsParam = (!empty(CRM_CiviMobileAPI_Utils_Statistic_Utils::getRenewalMembershipIds())) ? ["IN" => CRM_CiviMobileAPI_Utils_Statistic_Utils::getRenewalMembershipIds()] : NULL;
    $count = 0;

    try {
      $memberships = civicrm_api3('Membership', 'get', [
        'sequential' => 1,
        'options' => ['limit' => 0],
        'id' => ($isRenewal) ? $renewalMembershipsParam : NULL,
        'contact_id' => ['IN' => $contactsId],
        'membership_type_id' => !empty($params['membership_type_id']) ? $params['membership_type_id'] : NULL,
        'status_id' => $status,
      ])['values'];
    } catch (CiviCRM_API3_Exception $e) {
      return 0;
    }

    if (!empty($memberships)) {
      foreach ($memberships as $membership) {
        if ($membership['start_date'] == NULL) {
          $membership['start_date'] = $membership['join_date'];
        }

        if ($membership['start_date'] >= $startDate && $membership['start_date'] <= $endDate) {
          $count++;
        }
      }
    }

    return $count;
  }

}
