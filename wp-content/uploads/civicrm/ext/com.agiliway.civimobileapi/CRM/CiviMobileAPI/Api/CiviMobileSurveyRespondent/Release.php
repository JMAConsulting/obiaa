<?php

class CRM_CiviMobileAPI_Api_CiviMobileSurveyRespondent_Release extends CRM_CiviMobileAPI_Api_CiviMobileBase {

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
      throw new api_Exception('The survey doesn`t exists.', 'survey_does_not_exists');
    }

    $surveyActivityTypesIds = CRM_CiviMobileAPI_Utils_Survey::getSurveyActivityTypesIds();

    if (empty($surveyActivityTypesIds)) {
      return ['message' => 'Respondents weren`t released.'];
    }

    $activities = civicrm_api3('Activity', 'get', [
      'sequential' => 1,
      'source_record_id' => $survey['id'],
      'activity_type_id' => ['IN' => $surveyActivityTypesIds],
      'target_contact_id' => ['IN' => $this->validParams['contact_ids']],
      'assignee_id' => $this->validParams['interviewer_id'],
      'is_deleted' => 0,
      'status_id' => "Scheduled",
      'options' => ['limit' => 0]
    ]);

    if ($activities['count'] != count($this->validParams['contact_ids'])) {
      throw new api_Exception('Some contacts aren`t reserved respondents.', 'some_contacts_are_not_reserved_respondents');
    }

    foreach ($activities['values'] as $activity) {
      civicrm_api3('Activity', 'create', [
        'id' => $activity['id'],
        'is_deleted' => 1,
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
   * @throws api_Exception`
   */
  protected function getValidParams($params) {
    if (!CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionToReleaseRespondents()) {
      throw new API_Exception(ts('Permission is required.'));
    }

    $loggedInContactId = CRM_Core_Session::getLoggedInContactID();

    if (!empty($this->validParams['interviewer_id']) &&
      $this->validParams['interviewer_id'] != $loggedInContactId &&
      !CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionToChangeInterviewer()
    ) {
      throw new API_Exception(ts('Permission is required.'));
    }

    $params['interviewer_id'] = !empty($params['interviewer_id']) ? $params['interviewer_id'] : $loggedInContactId;

    $params['contact_ids'] = explode(',', $params['contact_ids']);

    return $params;
  }

}
