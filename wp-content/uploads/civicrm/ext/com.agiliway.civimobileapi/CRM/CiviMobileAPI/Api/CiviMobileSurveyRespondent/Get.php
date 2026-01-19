<?php

class CRM_CiviMobileAPI_Api_CiviMobileSurveyRespondent_Get extends CRM_CiviMobileAPI_Api_CiviMobileBase {

  /**
   * Returns results to api
   *
   * @return array
   * @throws CRM_Core_Exception
   */
  public function getResult() {
    $surveyActivityTypesIds = CRM_CiviMobileAPI_Utils_Survey::getSurveyActivityTypesIds();

    if (empty($surveyActivityTypesIds)) {
      return [];
    }

    $gotvCustomFieldName = CRM_CiviMobileAPI_Install_Entity_CustomGroup::SURVEY . '.' . CRM_CiviMobileAPI_Install_Entity_CustomField::SURVEY_GOTV_STATUS;

    $activitiesWhereParams = [
      ['activity_type_id', 'IN', $surveyActivityTypesIds],
      ['target_contact_id', 'IS NOT NULL'],
      ['source_record_id', '=', $this->validParams['survey_id']],
    ];

    if (!empty($this->validParams['interviewer_id'])) {
      $activitiesWhereParams[] = [
        'assignee_contact_id',
        '=',
        $this->validParams['interviewer_id'],
      ];
    }

    $filterStatuses = [];
    $filterGOTV = FALSE;
    $filterInterviewed = FALSE;

    if (!empty($this->validParams['survey_status'])) {
      if (in_array('Reserved', $this->validParams['survey_status'])) {
        $filterStatuses[] = "Scheduled";
      }
      if (in_array('Interviewed', $this->validParams['survey_status'])) {
        $filterStatuses[] = "Completed";
        $filterInterviewed = TRUE;
      }
      if (in_array('GOTV', $this->validParams['survey_status'])) {
        $filterStatuses[] = "Completed";
        $filterGOTV = TRUE;
      }

      if (!empty($filterStatuses)) {
        $activitiesWhereParams[] = ['status_id:name', 'IN', $filterStatuses];
      }
    }

    $activities = civicrm_api4('Activity', 'get', [
      'select' => [
        CRM_CiviMobileAPI_Install_Entity_CustomGroup::SURVEY . '.' . CRM_CiviMobileAPI_Install_Entity_CustomField::SURVEY_GOTV_STATUS,
        'target_contact_id',
        'status_id',
        'status_id:name',
        'result',
      ],
      'where' => $activitiesWhereParams,
      'checkPermissions' => FALSE,
    ])->getArrayCopy();

    $contactIds = [];

    foreach ($activities as $activity) {
      $contactIds[] = reset($activity['target_contact_id']);
    }

    if (empty($contactIds)) {
      return [];
    }

    $contactsWhereParams = [
      ['id', 'IN', $contactIds],
    ];

    if (!empty($this->validParams['group'])) {
      $contactsWhereParams[] = ['groups', 'IN', $this->validParams['group']];
    }
    if (!empty($this->validParams['display_name'])) {
      $contactsWhereParams[] = [
        'display_name',
        'LIKE',
        '%' . $this->validParams['display_name'] . '%',
      ];
    }
    if (!empty($this->validParams['contact_type'])) {
      $contactsWhereParams[] = [
        'contact_type:name',
        '=',
        $this->validParams['contact_type'],
      ];
    }
    if (!empty($this->validParams['city'])) {
      $contactsWhereParams[] = [
        'address_primary.city',
        'LIKE',
        '%' . $this->validParams['city'] . '%',
      ];
    }
    if (!empty($this->validParams['street_address'])) {
      $contactsWhereParams[] = [
        'address_primary.street_address',
        'LIKE',
        '%' . $this->validParams['street_address'] . '%',
      ];
    }

    $contacts = civicrm_api4('Contact', 'get', [
      'select' => [
        'id',
        'contact_type',
        'display_name',
        'image_URL',
        'address_primary.street_address',
        'address_primary.city',
      ],
      'where' => $contactsWhereParams,
      'checkPermissions' => TRUE,
    ])->getArrayCopy();

    $preparedContacts = [];

    foreach ($activities as $activity) {
      if ($filterGOTV != $filterInterviewed
        && $activity[$gotvCustomFieldName] != $filterGOTV
        && $activity['status_id:name'] != 'Scheduled') {
        continue;
      }

      $contact = $contacts[array_search(reset($activity['target_contact_id']), array_column($contacts, 'id'))];

      $status = NULL;

      switch ($activity['status_id:name']) {
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

      if (empty($status)) {
        continue;
      }

      $preparedContact = [
        'contact_id' => $contact['id'],
        'contact_type' => $contact['contact_type'],
        'display_name' => $contact['display_name'],
        'image_URL' => $contact['image_URL'],
        'survey_status' => $status,
        'result' => !empty($activity['result']) ? $activity['result'] : '',
        'street_address' => $contact['address_primary.street_address'] ?? '',
        'city' => $contact['address_primary.city'] ?? '',
      ];

      $preparedContacts[] = $preparedContact;
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
   * @throws CRM_Core_Exception`
   */
  protected function getValidParams($params) {
    if (!CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionToGetRespondents()) {
      throw new CRM_Core_Exception(ts('Permission is required.'));
    }

    $loggedInContactId = CRM_Core_Session::getLoggedInContactID();

    if ($loggedInContactId !== $params['interviewer_id'] && !CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionToChangeInterviewer()) {
      if (empty($loggedInContactId)) {
        $params['interviewer_id'] = $loggedInContactId;
      } else {
        throw new CRM_Core_Exception(ts('Permission is required.'));
      }
    }

    if (!empty($params['survey_status']) && !is_array($params['survey_status'])) {
      $params['survey_status'] = [$params['survey_status']];
    }

    return $params;
  }

}
