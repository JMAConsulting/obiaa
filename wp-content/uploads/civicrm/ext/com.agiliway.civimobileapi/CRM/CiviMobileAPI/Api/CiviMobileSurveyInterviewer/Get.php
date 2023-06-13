<?php

class CRM_CiviMobileAPI_Api_CiviMobileSurveyInterviewer_Get extends CRM_CiviMobileAPI_Api_CiviMobileBase {

  /**
   * Returns results to api
   *
   * @return array
   * @throws api_Exception
   */
  public function getResult() {
    try {
      $survey = civicrm_api3('Survey', 'getsingle', [
        'id' => $this->validParams['survey_id'],
      ]);
    } catch (Exception $e) {
      throw new api_Exception('Survey does not exists','survey_does_not_exists');
    }

    $activities = civicrm_api3('Activity', 'get', [
      'sequential' => 1,
      'return' => ["assignee_contact_name"],
      'source_record_id' => $survey['id'],
      'activity_type_id' => $survey['activity_type_id'],
      'status_id' => ['IN' => ["Completed", "Scheduled"]],
      'is_deleted' => 0,
      'options' => ['limit' => 0],
    ]);

    $interviewers = [];

    foreach ($activities['values'] as $activity) {
      foreach ($activity['assignee_contact_name'] as $key => $value) {
        $interviewers[$key] = [
          'id' => $key,
          'display_name' => $value
        ];
      }
    }

    $interviewers = array_values($interviewers);

    usort($interviewers, function($a, $b) {
      return strcasecmp($a['display_name'], $b['display_name']);
    });

    return $interviewers;
  }

  /**
   * Returns validated params
   *
   * @param $params
   *
   * @return array
   * @throws api_Exception`
   */
  protected function getValidParams($params) {
    if (!CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionToChangeInterviewer()) {
      throw new API_Exception(ts('Permission is required.'));
    }

    return $params;
  }

}
