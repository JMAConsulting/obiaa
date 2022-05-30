<?php

class CRM_CiviMobileAPI_Api_CiviMobileSurveyRespondent_GetToReserve extends CRM_CiviMobileAPI_Api_CiviMobileBase {

  /**
   * Returns results to api
   *
   * @return array
   * @throws api_Exception
   */
  public function getResult() {
    $contactParams = [
      'options' => ['limit' => 0, 'sort' => 'display_name'],
      'return' => ["id", "contact_type", "display_name", "image_URL", "street_address", "city", "country", "address_id", "contact_type"],
      'group' => !empty($this->validParams['group']) ? $this->validParams['group'] : NULL,
      'display_name' => !empty($this->validParams['display_name']) ? $this->validParams['display_name'] : NULL,
      'contact_type' => !empty($this->validParams['contact_type']) ? $this->validParams['contact_type'] : NULL,
      'city' => !empty($this->validParams['city']) ? $this->validParams['city'] : NULL,
      'street_address' => !empty($this->validParams['group']) ? $this->validParams['street_address'] : NULL,
      'check_permissions' => true
    ];

    $contacts = civicrm_api3('Contact', 'get', $contactParams)['values'];

    $surveyActivityTypesIds = CRM_CiviMobileAPI_Utils_Survey::getSurveyActivityTypesIds();

    if (empty($surveyActivityTypesIds)) {
      return [];
    }

    $activities = civicrm_api3('Activity', 'get', [
      'sequential' => 1,
      'source_record_id' => $this->validParams['survey_id'],
      'activity_type_id' => ['IN' => $surveyActivityTypesIds],
      'is_deleted' => 0,
      'return' => ["target_contact_id", "status_id"],
      'options' => ['limit' => 0],
    ])['values'];

    foreach ($activities as $activity) {
      $contactId = reset($activity['target_contact_id']);

      if (isset($contacts[$contactId])) {
        unset($contacts[$contactId]);
      }
    }

    $preparedContacts = [];

    foreach ($contacts as $key => $contact) {
      $preparedContacts[$key] = [
        'contact_id' => $contact['id'],
        'display_name' => $contact['display_name'],
        'image_URL' => $contact['image_URL'],
        'street_address' => $contact['street_address'],
        'city' => $contact['city'],
        'country' => $contact['country'],
        'contact_type' => $contact['contact_type'],
      ];
    }

    if (!empty($this->validParams['sequential'])) {
      return array_values($preparedContacts);
    }

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
    return $params;
  }

}
