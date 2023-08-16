<?php

class CRM_CiviMobileAPI_Api_CiviMobileSurvey_Sign extends CRM_CiviMobileAPI_Api_CiviMobileBase {

  /**
   * Returns results to api
   *
   * @return array
   * @throws api_Exception
   */
  public function getResult() {
    $transaction = new CRM_Core_Transaction();

    if (!empty($this->validParams['values']['contact_profile']['email-Primary'])
      && $this->validParams['survey_structure']['is_petition']
      && empty($this->validParams['contact_id'])
    ) {
      $contacts = civicrm_api3('Contact', 'get', [
        'sequential' => 1,
        'email' => $this->validParams['values']['contact_profile']['email-Primary']
      ]);

      if (!empty($contacts['values'])) {
        $this->validParams['contact_id'] = $contacts['values'][0]['id'];
      }
    }

    $userSurvey = civicrm_api3('CiviMobileSurvey', 'get_contact_surveys', [
      'contact_id' => $this->validParams['contact_id'],
      'survey_id' => $this->validParams['survey_id'],
    ])['values'][0];
    if ($userSurvey['is_signed']) {
      $transaction->rollback();
      throw new api_Exception('Survey already signed.', 'survey_already_signed');
    }

    if (!$this->validParams['survey_structure']['is_petition']) {
      try {
        $activity = civicrm_api3('Activity', 'getsingle', [
          'sequential' => 1,
          'status_id' => "Scheduled",
          'source_record_id' => $this->validParams['survey_id'],
          'target_contact_id' => $this->validParams['contact_id'],
          'is_deleted' => 0,
          'return' => ["assignee_contact_id", "id"]
        ]);
      } catch (Exception $e) {
        $transaction->rollback();
        throw new api_Exception('The respondent is not reserved.', 'unknown_exception');
      }

      if (!in_array(CRM_Core_Session::getLoggedInContactID(), $activity['assignee_contact_id']) && !CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionToChangeInterviewer()) {
        throw new API_Exception(ts('Permission is required.'));
      }

      $params = [
          'voter_id' => $this->validParams['contact_id'],
          'survey_id' => $this->validParams['survey_id'],
          'activity_id' => $activity['id'],
          'surveyTitle' => $this->validParams['survey_structure']['title'],
          'activity_type_id' => $this->validParams['survey_structure']['activity_type_id'],
          'details' => $this->validParams['note'],
          'result' => $this->validParams['result']
        ] + $this->validParams['values']['contact_profile'] + $this->validParams['values']['activity_profile'];

      CRM_Campaign_Form_Task_Interview::registerInterview($params);
    } else {
      $contactParams = [
          'contactId' => $this->validParams['contact_id']
        ] + $this->validParams['values']['contact_profile'];

      $contactId = CRM_Contact_BAO_Contact::createProfileContact($contactParams, $this->validParams['survey_structure']['profiles']['contact_profile']['fields'],
        $this->validParams['contact_id'], NULL, $this->validParams['survey_structure']['contact_profile']['id'], 'Individual', TRUE);

      $activityParams = [
          'contactId' => $contactId,
          'sid' => $this->validParams['survey_id'],
          'status_id' => "Completed"
        ] + $this->validParams['values']['activity_profile'];

      $activityParams['custom'] = CRM_Core_BAO_CustomField::postProcess($activityParams, NULL, 'Activity', FALSE, FALSE);

      $petitionBAO = new CRM_Campaign_BAO_Petition();
      $petitionBAO->createSignature($activityParams);
    }

    $transaction->commit();

    return [['message' => "Survey successfully signed."]];
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
    $loggedInContactId = CRM_Core_Session::getLoggedInContactID();
    $validatedParams = [];
    if (!is_array($params['values'])) {
      throw new api_Exception('Values must be object.', 'values_must_be_object');
    }

    $surveyStructure = civicrm_api3('CiviMobileSurvey', 'get_structure', [
      'id' => $params['survey_id'],
    ])['values'][0];

    if ($surveyStructure['is_petition']) {
      if (!CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionToSignPetition()) {
        throw new API_Exception(ts('Permission is required.'));
      }
      if ($loggedInContactId) {
        $params['contact_id'] = $loggedInContactId;
      }
    } else {
      if (!$loggedInContactId) {
        throw new API_Exception(ts('Not authorized.'));
      }
      if (empty($params['contact_id'])) {
        throw new API_Exception(ts('contact_id is required.'));
      }
      if (!CRM_Core_Permission::check('interview campaign contacts')) {
        throw new API_Exception(ts('Permission is required.'));
      }
    }

    $validatedParams['survey_id'] = $params['survey_id'];
    $validatedParams['survey_structure'] = $surveyStructure;
    $validatedParams['contact_id'] = !empty($params['contact_id']) ? $params['contact_id'] : NULL;
    $validatedParams['contact_profile_id'] = $surveyStructure['profiles']['contact_profile']['id'];
    $validatedParams['activity_profile_id'] = $surveyStructure['profiles']['activity_profile']['id'];
    $validatedParams['note'] = !empty($params['note']) ? $params['note'] : NULL;

    if (!empty($params['result'])) {
      $resultOptionLabel = NULL;

      foreach ($surveyStructure['result_set'] as $option) {
        if ($option['name'] == $params['result']) {
          $resultOptionLabel = $option['label'];
        }
      }

      if (empty($resultOptionLabel)) {
        throw new api_Exception("Result option doesn`t exist.", 'no_result_option');
      }

      $validatedParams['result'] = $resultOptionLabel;
    } else {
      $validatedParams['result'] = NULL;
    }

    $validatedParams['values'] = [
      'activity_profile' => [],
      'contact_profile' => []
    ];

    foreach ($surveyStructure['profiles'] as $key => $profile) {
      foreach ($profile['fields'] as $field) {
        if ((!isset($params['values'][$field['name']]) || (isset($params['values'][$field['name']]) && $params['values'][$field['name']] == "")) && $field['is_required']) {
          throw new api_Exception("'{$field['name']}' is required.", 'required_value');
        }
        if ($field['html_type'] == 'CheckBox' && is_array($params['values'][$field['name']])) {
          $options = [];

          foreach ($params['values'][$field['name']] as $value) {
            $options[$value] = 1;
          }

          $validatedParams['values'][$key][$field['name']] = $options;
        } elseif(isset($params['values'][$field['name']])) {
          $validatedParams['values'][$key][$field['name']] = $params['values'][$field['name']];
        }

      }

      $validatedParams[$key . "_id"] = $profile['id'];
    }

    return $validatedParams;
  }

}
