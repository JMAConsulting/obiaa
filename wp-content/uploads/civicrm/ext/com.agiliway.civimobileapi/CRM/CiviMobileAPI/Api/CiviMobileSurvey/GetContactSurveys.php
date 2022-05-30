<?php

class CRM_CiviMobileAPI_Api_CiviMobileSurvey_GetContactSurveys extends CRM_CiviMobileAPI_Api_CiviMobileBase {

  /**
   * Returns results to api
   *
   * @return array
   * @throws api_Exception
   */
  public function getResult() {
    $preparedSurveys = [];
    $paramsToApi = [
      'options' => ['limit' => 0],
      'activity_type_id' => $this->validParams['activity_type_id'],
      'is_active' => $this->validParams['is_active']
    ];

    if (!empty($this->validParams['survey_id'])) {
      $paramsToApi['id'] = $this->validParams['survey_id'];
    }

    $surveys = civicrm_api3('Survey', 'get', $paramsToApi)['values'];

    foreach ($surveys as $key => &$surveyInfo) {
      if (!empty($this->validParams['title']['LIKE'])
        && stripos($surveyInfo['title'], $this->validParams['title']['LIKE']) === false) {
          continue;
      }

      $survey = [
        'id' => $surveyInfo['id'],
        'title' => $surveyInfo['title'],
        'is_active' => $surveyInfo['is_active'],
        'activity_type_id' => $surveyInfo['activity_type_id']
      ];

      if (!empty($this->validParams['contact_id'])) {
        $activities = civicrm_api3('Activity', 'get', [
          'target_contact_id' => $this->validParams['contact_id'],
          'source_record_id' => $surveyInfo['id'],
          'activity_type_id' => $this->validParams['activity_type_id'],
          'status_id' => "Completed",
          'is_deleted' => 0,
        ]);

        $survey['is_signed'] = (int)(boolean)$activities['count'];

        if (!is_null($this->validParams['is_signed'])
          && ((!$survey['is_signed'] && $this->validParams['is_signed']) || ($survey['is_signed'] && !$this->validParams['is_signed']))
        ) {
          continue;
        }
      }

      $preparedSurveys[] = $survey;
    }

    return $preparedSurveys;
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
    if (CRM_Core_Session::getLoggedInContactID()) {
      if (empty($params['activity_type_id'])) {
        $params['activity_type_id'] = [
          'IN' => []
        ];

        $activityTypes = civicrm_api3('OptionValue', 'get', [
          'sequential' => 1,
          'option_group_id' => "activity_type",
          'component_id' => "CiviCampaign",
        ])['values'];

        foreach ($activityTypes as $type) {
          $params['activity_type_id']['IN'][] = $type['name'];
        }
      }
    } else {
      $params['activity_type_id'] = "Petition";
    }

    return [
      'is_signed' => isset($params['is_signed']) ? $params['is_signed'] : NULL,
      'survey_id' => isset($params['survey_id']) ? $params['survey_id'] : NULL,
      'contact_id' => isset($params['contact_id']) ? $params['contact_id'] : NULL,
      'activity_type_id' => $params['activity_type_id'],
      'title' => isset($params['title']) ? $params['title'] : NULL,
      'is_active' => isset($params['is_active']) ? $params['is_active'] : NULL,
    ];
  }

}
