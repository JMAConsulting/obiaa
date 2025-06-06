<?php

class CRM_CiviMobileAPI_Api_CiviMobileSurveyRespondent_Reserve extends CRM_CiviMobileAPI_Api_CiviMobileBase {

  /**
   * Returns results to api
   *
   * @return array
   * @throws api_Exception
   */
  public function getResult() {
    $survey = civicrm_api4('Survey', 'get', [
      'where' => [
        ['id', '=', $this->validParams['survey_id']],
      ],
      'checkPermissions' => FALSE,
    ])->first();

    if (empty($survey)) {
      throw new api_Exception('The survey doesn`t exists.', 'survey_does_not_exists');
    }

    if (!empty($survey['default_number_of_contacts']) && count($this->validParams['contact_ids']) > $survey['default_number_of_contacts']) {
      throw new api_Exception('You can reserve no more than ' . $survey['default_number_of_contacts'] . ' contacts per interviewer at one time.', 'too_much_contacts_per_interviewer_at_one_time');
    }

    $surveyActivityTypesIds = CRM_CiviMobileAPI_Utils_Survey::getSurveyActivityTypesIds();

    $activities = civicrm_api4('Activity', 'get', [
      'where' => [
        ['is_deleted', '=', FALSE],
        ['source_record_id', '=', $survey['id']],
        ['activity_type_id', 'IN', $surveyActivityTypesIds],
        ['target_contact_id', 'IN', $this->validParams['contact_ids']],
      ],
      'checkPermissions' => FALSE,
    ])->getArrayCopy();

    if (count($activities) > 0) {
      throw new api_Exception('Some contacts already reserved.', 'some_contacts_already_reserved');
    }

    if (!empty($survey['max_number_of_contacts'])) {
      $reservedRespondents = civicrm_api3('CiviMobileSurveyRespondent', 'get', [
        'sequential' => 1,
        'survey_id' => $survey['id'],
        'interviewer_id' => $this->validParams['interviewer_id'],
      ]);

      if ($reservedRespondents['count'] + count($this->validParams['contact_ids']) > $survey['max_number_of_contacts']) {
        throw new api_Exception('You can reserve no more than ' . $survey['max_number_of_contacts'] . ' contacts per interviewer.', 'too_much_contacts_per_interviewer');
      }
    }

    foreach ($this->validParams['contact_ids'] as $id) {
      civicrm_api4('Activity', 'create', [
        'values' => [
          'source_contact_id' => CRM_Core_Session::getLoggedInContactID(),
          'source_record_id' => $survey['id'],
          'target_contact_id' => $id,
          'subject' => $survey['title'] . ' - Respondent Reservation',
          'activity_type_id' => $survey['activity_type_id'],
          'status_id:name' => "Scheduled",
          'assignee_contact_id' => $this->validParams['interviewer_id'],
        ],
        'checkPermissions' => FALSE,
      ]);
    }

    return ['message' => 'Contacts successfully reserved.'];
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
    if (!CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionToReserveRespondents()) {
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

    $contactsCount = civicrm_api4('Contact', 'get', [
      'select' => [
        'row_count',
      ],
      'where' => [
        ['id', 'IN', $params['contact_ids']],
      ],
      'checkPermissions' => TRUE,
    ])->count();

    if (count($params['contact_ids']) != $contactsCount) {
      throw new api_Exception('Some contacts don`t exists or you don`t have permissions to view them.', 'some_contacts_do_not_exists');
    }

    return $params;
  }

}
