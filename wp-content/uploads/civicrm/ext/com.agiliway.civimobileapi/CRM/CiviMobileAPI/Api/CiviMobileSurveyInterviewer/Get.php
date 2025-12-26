<?php

class CRM_CiviMobileAPI_Api_CiviMobileSurveyInterviewer_Get extends CRM_CiviMobileAPI_Api_CiviMobileBase {

  /**
   * Returns results to api
   *
   * @return array
   * @throws CRM_Core_Exception
   */
  public function getResult() {
    $survey = civicrm_api4('Survey', 'get', [
      'where' => [
        ['id', '=', $this->validParams['survey_id']],
      ],
      'checkPermissions' => FALSE,
    ])->first();

    if (empty($survey)) {
      throw new CRM_Core_Exception('The survey doesn`t exists.', 'survey_does_not_exists');
    }

    $activities = civicrm_api4('Activity', 'get', [
      'select' => [
        'contact.display_name',
        'contact.id',
      ],
      'join' => [
        [
          'Contact AS contact',
          'LEFT',
          ['contact.id', 'IN', 'assignee_contact_id'],
        ],
      ],
      'where' => [
        ['is_deleted', '=', FALSE],
        ['source_record_id', '=', $survey['id']],
        ['activity_type_id', '=', $survey['activity_type_id']],
        ['status_id:name', 'IN', ["Completed", "Scheduled"]],
      ],
      'checkPermissions' => FALSE,
      'groupBy' => [
        'contact.id',
      ],
    ])->getArrayCopy();

    $interviewers = [];

    foreach ($activities as $activity) {
      $interviewers[] = [
        'id' => $activity['contact.id'],
        'display_name' => $activity['contact.display_name'],
      ];
    }

    return array_values($interviewers);
  }

  /**
   * Returns validated params
   *
   * @param $params
   *
   * @return array
   * @throws CRM_Core_Exception
   */
  protected function getValidParams($params) {
    if (!CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionToChangeInterviewer()) {
      throw new CRM_Core_Exception(ts('Permission is required.'));
    }

    return $params;
  }

}
