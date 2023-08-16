<?php

/**
 * Class provide case summary information
 */
class CRM_CiviMobileAPI_Utils_CaseSummary {

  /**
   * Get count of cases
   *
   * @param $status
   * @param $params
   * @return int
   */
  public function getCountOfCases($status, $params) {
    $contactIds = $this->getContactsIdByName($params['contact_display_name']);
    $caseId = $this->getCaseIdByActivities($params['activity_type']);

    if (!empty($params['id'])) {
      $caseId = array_intersect($caseId, $params['id']['IN']);
    }

    try {
      $cases = civicrm_api3('Case', 'get', [
        'sequential' => 1,
        'id' => ["IN" => $caseId],
        'status_id' => $status,
        'case_type_id' => !empty($params['case_type_id']) ? $params['case_type_id'] : NULL,
        'subject' => !empty($params['subject']) ? $params['subject'] : NULL,
        'contact_id' => !empty($contactIds) ? ['IN' => $contactIds] : NULL,
        'start_date' => !empty($params['start_date']) ? $params['start_date'] : NULL,
        'end_date' => !empty($params['end_date']) ? $params['end_date'] : NULL,
        'options' => ['limit' => 0],
      ])['values'];
    } catch (CiviCRM_API3_Exception $e) {
      return 0;
    }

    return count($cases);
  }

  /**
   * Get Contacts Ids by display name
   *
   * @param $displayName
   * @return array
   */
  public static function getContactsIdByName($displayName) {
    try {
      $contacts = civicrm_api3('Contact', 'get', [
        'sequential' => 1,
        'display_name' => $displayName,
        'contact_is_deleted' => 0,
        'options' => ['limit' => 0],
      ])['values'];
    } catch (CiviCRM_API3_Exception $e) {
      return [];
    }

    $contactsId = [];
    if (!empty($contacts)) {
      foreach ($contacts as $contact) {
        $contactsId[] = $contact['id'];
      }
    }

    return $contactsId;
  }

  /**
   * Get Case Ids by activities
   *
   * @param int $activityTypeParam
   * @return array
   */
  public static function getCaseIdByActivities($activityTypeParam) {
    if ($activityTypeParam == 1) {
      $activityType = 'upcoming';
    } elseif ($activityTypeParam == 2) {
      $activityType = 'any';
    } else {
      $activityType = 'recent';
    }

    $userID = CRM_Core_Session::getLoggedInContactID();
    $caseActivityQuery = CRM_Case_BAO_Case::getCaseActivityQuery($activityType, $userID, "civicrm_case.is_deleted = 0");
    $caseActivities = CRM_Core_DAO::executeQuery($caseActivityQuery)->fetchAll();

    $caseIds = [];
    if (!empty($caseActivities)) {
      foreach ($caseActivities as $caseActivity) {
        $caseIds[] = $caseActivity['case_id'];
      }
    }

    return $caseIds;
  }

}
