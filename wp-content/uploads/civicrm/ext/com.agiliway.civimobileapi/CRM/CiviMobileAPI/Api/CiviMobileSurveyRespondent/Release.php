<?php

class CRM_CiviMobileAPI_Api_CiviMobileSurveyRespondent_Release extends CRM_CiviMobileAPI_Api_CiviMobileBase {

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

    $surveyActivityTypesIds = CRM_CiviMobileAPI_Utils_Survey::getSurveyActivityTypesIds();

    if (empty($surveyActivityTypesIds)) {
      return ['message' => 'Respondents weren`t released.'];
    }

    $activities = civicrm_api4('Activity', 'get', [
      'where' => [
        ['source_record_id', '=', $survey['id']],
        ['activity_type_id', 'IN', $surveyActivityTypesIds],
        ['target_contact_id', 'IN', $this->validParams['contact_ids']],
        ['assignee_contact_id', '=', $this->validParams['interviewer_id']],
        ['is_deleted', '=', FALSE],
        ['status_id:name', '=', 'Scheduled'],
      ],
      'checkPermissions' => FALSE,
    ])->getArrayCopy();

    if (count($activities) != count($this->validParams['contact_ids'])) {
      throw new CRM_Core_Exception('Some contacts aren`t reserved respondents.', 'some_contacts_are_not_reserved_respondents');
    }

    foreach ($activities as $activity) { // maybe replace with save
      civicrm_api4('Activity', 'update', [
        'values' => [
          'is_deleted' => TRUE,
        ],
        'where' => [
          ['id', '=', $activity['id']],
        ],
        'checkPermissions' => FALSE,
      ]);
    }

    return ['message' => 'Respondents is successfully released.'];
  }

  /**
   * Returns validated params
   *
   * @param $params
   *
   * @return array
   * @throws CRM_Core_Exception`
   */
  protected function getValidParams($params) {
    if (!CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionToReleaseRespondents()) {
      throw new CRM_Core_Exception(ts('Permission is required.'));
    }

    $loggedInContactId = CRM_Core_Session::getLoggedInContactID();

    if (!empty($this->validParams['interviewer_id']) &&
      $this->validParams['interviewer_id'] != $loggedInContactId &&
      !CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionToChangeInterviewer()
    ) {
      throw new CRM_Core_Exception(ts('Permission is required.'));
    }

    $params['interviewer_id'] = !empty($params['interviewer_id']) ? $params['interviewer_id'] : $loggedInContactId;

    $params['contact_ids'] = explode(',', $params['contact_ids']);

    return $params;
  }

}