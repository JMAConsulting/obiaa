<?php

class CRM_CiviMobileAPI_Api_CiviMobileSurvey_GetSignedValues extends CRM_CiviMobileAPI_Api_CiviMobileBase {

  /**
   * Returns results to api
   *
   * @return array
   * @throws api_Exception
   */
  public function getResult() {
    $preparedValues = [];

    $surveyStructure = civicrm_api3('CiviMobileSurvey', 'get_structure', [
      'id' => $this->validParams['survey_id'],
    ])['values'][0];

    if (!$surveyStructure['is_petition']) {
      if (!CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionToViewRespondentDetails()) {
        throw new API_Exception(ts('Permission is required.'));
      }
    } else {
      if (!CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionToViewPetitionAnswers()) {
        throw new API_Exception(ts('Permission is required.'));
      }
    }

    try {
      $activity = civicrm_api3('Activity', 'getsingle', [
        'target_contact_id' => $this->validParams['contact_id'],
        'source_record_id' => $this->validParams['survey_id'],
        'activity_type_id' => $surveyStructure['activity_type_id'],
        'status_id' => "Completed"
      ]);
    } catch (Exception $e) {
      throw new api_Exception('Survey is not signed. ' . $e->getMessage(), 'survey_is_not_signed');
    }

    $preparedValues['note'] = $activity['details'];
    $preparedValues['result'] = !empty($activity['result']) ? $activity['result'] : '';

    $preparedValues['activity_profile'] = $this->getProfileValuesByActivity(
      $surveyStructure['profiles']['activity_profile'],
      $activity
    );
    $preparedValues['contact_profile'] = $this->getProfileValues(
      $surveyStructure['profiles']['contact_profile'],
      $this->validParams['contact_id'],
      $activity['id']
    );

    return [$preparedValues];
  }

  /**
   * Returns validated params
   *
   * @param $params
   *
   * @return array
   * @throws api_Exception
   */
  protected function getValidParams($params) {
    return [
      'contact_id' => $params['contact_id'],
      'survey_id' => $params['survey_id'],
    ];
  }

  /**
   * Returns values of submitted profile
   *
   * @param $profile
   * @param $contactId
   * @param $activityId
   * @return array
   */
  protected function getProfileValues($profile, $contactId, $activityId) {
    $values = [];
    $profileValues = civicrm_api3('Profile', 'get', [
      'profile_id' => $profile['id'],
      'contact_id' => $contactId,
      'activity_id' => $activityId
    ])['values'];

    foreach ($profile['fields'] as $field) {
      $values[$field['name']] = $field;
      if (isset($profileValues[$field['name']])) {
        if (is_array($profileValues[$field['name']])) {
          $selectedOptions = [];

          foreach ($profileValues[$field['name']] as $key => $value) {
            if (!preg_match('/^\[[\s\S]+\]$/', $key)) {
              $selectedOptions[] = strval($key);
            }
          }

          $values[$field['name']]['value'] = $selectedOptions;
        } else {
          $values[$field['name']]['value'] = $profileValues[$field['name']];
        }
      } else {
        $values[$field['name']]['value'] = NULL;
      }
    }

    return $values;
  }

  /**
   * Returns values of submitted profile by activity
   *
   * @param $profile
   * @param $activityId
   * @return mixed
   */
  protected function getProfileValuesByActivity($profile, $activity) {

    foreach ($profile['fields'] as &$field) {
      $field['value'] = isset($activity[$field['name']]) ? $activity[$field['name']] : NULL;
    }

    return $profile['fields'];
  }

}
