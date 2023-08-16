<?php

class CRM_CiviMobileAPI_Api_CiviMobileSurveyRespondent_Get extends CRM_CiviMobileAPI_Api_CiviMobileBase {

  /**
   * Returns results to api
   *
   * @return array
   * @throws api_Exception
   */
  public function getResult() {
    $surveyActivityTypesIds = CRM_CiviMobileAPI_Utils_Survey::getSurveyActivityTypesIds();

    if (empty($surveyActivityTypesIds)) {
      return [];
    }

    try {
      $contactProfile = civicrm_api3('UFJoin', 'getsingle', [
        'entity_table' => "civicrm_survey",
        'entity_id' => $this->validParams['survey_id'],
        'weight' => 1,
      ]);

      $fields = CRM_Core_BAO_UFGroup::getFields($contactProfile['uf_group_id']);
    } catch (Exception $e) {
      return [];
    }

    $contactParams = [
      'options' => ['limit' => 0],
      'return' => ["id", "contact_type", "display_name", "image_URL"],
      'group' => !empty($this->validParams['group']) ? $this->validParams['group'] : NULL,
      'display_name' => !empty($this->validParams['display_name']) ? $this->validParams['display_name'] : NULL,
      'contact_type' => !empty($this->validParams['contact_type']) ? $this->validParams['contact_type'] : NULL,
      'city' => !empty($this->validParams['city']) ? $this->validParams['city'] : NULL,
      'street_address' => !empty($this->validParams['group']) ? $this->validParams['street_address'] : NULL,
      'check_permissions' => true
    ];

    $gotvCustomFieldName = 'custom_' . CRM_CiviMobileAPI_Utils_CustomField::getId(CRM_CiviMobileAPI_Install_Entity_CustomGroup::SURVEY,CRM_CiviMobileAPI_Install_Entity_CustomField::SURVEY_GOTV_STATUS);

    $activitiesParams = [
      'sequential' => 1,
      'source_record_id' => $this->validParams['survey_id'],
      'activity_type_id' => ['IN' => $surveyActivityTypesIds],
      'is_deleted' => 0,
      'return' => ["target_contact_id", "status_id", "result", $gotvCustomFieldName],
      'options' => ['limit' => 0],
    ];

    if (!empty($this->validParams['interviewer_id'])) {
      $activitiesParams['assignee_contact_id'] = $this->validParams['interviewer_id'];
    }

    $filterStatuses = [];
    $filterGOTV = false;
    $filterInterviewed = false;

    if (!empty($this->validParams['survey_status'])) {
      if (in_array('Reserved', $this->validParams['survey_status'])) {
        $filterStatuses[] = "Scheduled";
      }
      if (in_array('Interviewed', $this->validParams['survey_status'])) {
        $filterStatuses[] = "Completed";
        $filterInterviewed = true;
      }
      if (in_array('GOTV', $this->validParams['survey_status'])) {
        $filterStatuses[] = "Completed";
        $filterGOTV = true;
      }

      if (!empty($filterStatuses)) {
        $activitiesParams['status_id'] = ['IN' => $filterStatuses];
      }
    }

    $activities = civicrm_api3('Activity', 'get', $activitiesParams);

    $contactIds = [];

    foreach ($activities['values'] as $activity) {
      $contactIds[] = reset($activity['target_contact_id']);
    }

    if (empty($contactIds)) {
      return [];
    }

    $contactParams['id'] = ['IN' => $contactIds];

    $contacts = civicrm_api3('Contact', 'get', $contactParams)['values'];

    $activityStatuses = civicrm_api3('OptionValue', 'get', [
      'sequential' => 1,
      'option_group_id' => "activity_status",
      'name' => ['IN' => ["Scheduled", "Completed"]],
    ])['values'];

    $preparedStatuses = [];

    foreach ($activityStatuses as $status) {
      $preparedStatuses[$status['value']] = $status['name'];
    }

    $preparedContacts = [];

    foreach ($activities['values'] as $activity) {
      if ($filterGOTV != $filterInterviewed
        && $activity[$gotvCustomFieldName] != $filterGOTV
        && $preparedStatuses[$activity['status_id']] != 'Scheduled') {
        continue;
      }

      $contactId = reset($activity['target_contact_id']);
      if (!isset($contacts[$contactId])) {
        continue;
      }
      $contact = $contacts[$contactId];

      $status = NULL;

      switch ($preparedStatuses[$activity['status_id']]) {
        case 'Scheduled':
          $status = 'Reserved';
          break;
        case 'Completed':
          if (!empty($activity[$gotvCustomFieldName])) {
            $status = 'GOTV';
          } else {
            $status = 'Interviewed';
          }
          break;
      }

      if (!empty($status)) {
        $preparedContact = [
          'contact_id' => $contact['id'],
          'contact_type' => $contact['contact_type'],
          'display_name' => $contact['display_name'],
          'image_URL' => $contact['image_URL'],
          'survey_status' => $status,
          'result' => !empty($activity['result']) ? $activity['result'] : '',
        ];

        $profileResults = civicrm_api3('Profile', 'get', [
          'sequential' => 1,
          'profile_id' => $contactProfile['uf_group_id'],
          'contact_id' => $contact['id'],
          'activity_id' => $activity['id']
        ])['values'];

        foreach (array_keys($fields) as $field) {
          $preparedContact[$field] = $profileResults[$field];
        }

        $preparedContacts[] = $preparedContact;
      }
    }

    usort($preparedContacts, function($a, $b) {
      return strcasecmp($a['display_name'], $b['display_name']);
    });

    return $preparedContacts;
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
    if (!CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionToGetRespondents()) {
      throw new API_Exception(ts('Permission is required.'));
    }

    $loggedInContactId = CRM_Core_Session::getLoggedInContactID();

    if ($loggedInContactId !== $params['interviewer_id'] && !CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionToChangeInterviewer()) {
      if (empty($loggedInContactId)) {
        $params['interviewer_id'] = $loggedInContactId;
      } else {
        throw new API_Exception(ts('Permission is required.'));
      }
    }

    if (!empty($params['survey_status']) && !is_array($params['survey_status'])) {
      $params['survey_status'] = [$params['survey_status']];
    }

    return $params;
  }

}
