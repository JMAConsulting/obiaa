<?php

class CRM_CiviMobileAPI_Api_CiviMobileSurveyRespondent_GetToReserve extends CRM_CiviMobileAPI_Api_CiviMobileBase {

  /**
   * Returns results to api
   *
   * @return array
   * @throws CRM_Core_Exception
   */
  public function getResult() {
    $contactsWhereParams = [];

    if (!empty($this->validParams['group'])) {
      $contactsWhereParams[] = ['groups', 'IN', $this->validParams['group']];
    }
    if (!empty($this->validParams['display_name'])) {
      $contactsWhereParams[] = [
        'display_name',
        'LIKE',
        "%{$this->validParams['display_name']}%",
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
        "%{$this->validParams['city']}%",
      ];
    }
    if (!empty($this->validParams['street_address'])) {
      $contactsWhereParams[] = [
        'address_primary.street_address',
        'LIKE',
        "%{$this->validParams['street_address']}%",
      ];
    }

    $surveyActivityTypesIds = CRM_CiviMobileAPI_Utils_Survey::getSurveyActivityTypesIds();

    if (empty($surveyActivityTypesIds)) {
      return [];
    }

    $activities = civicrm_api4('Activity', 'get', [
      'select' => [
        'target_contact_id',
        'status_id',
      ],
      'where' => [
        ['source_record_id', '=', $this->validParams['survey_id']],
        ['activity_type_id', 'IN', $surveyActivityTypesIds],
        ['is_deleted', '=', FALSE],
        ['target_contact_id', 'IS NOT NULL'],
      ],
      'checkPermissions' => FALSE,
    ])->getArrayCopy();

    $reservedContactIds = [];

    foreach ($activities as $activity) {
      $reservedContactIds[] = reset($activity['target_contact_id']);
    }

    $contactsWhereParams[] = ['id', 'NOT IN', $reservedContactIds];

    $contacts = civicrm_api4('Contact', 'get', [
      'select' => [
        'id',
        'contact_type',
        'display_name',
        'image_URL',
        'address_primary.street_address',
        'address_primary.city',
        'address_primary.id',
        'address_primary.country_id:label',
        'contact_type:name',
      ],
      'where' => $contactsWhereParams,
      'orderBy' => [
        'display_name' => 'ASC',
      ],
      'checkPermissions' => TRUE,
    ])->getArrayCopy();

    $preparedContacts = [];

    foreach ($contacts as $contact) {
      $preparedContacts[$contact['id']] = [
        'contact_id' => $contact['id'],
        'display_name' => $contact['display_name'],
        'image_URL' => $contact['image_URL'],
        'street_address' => $contact['address_primary.street_address'],
        'city' => $contact['address_primary.city'],
        'country' => $contact['address_primary.country_id:label'],
        'contact_type' => $contact['contact_type:name'],
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
   * @throws CRM_Core_Exception`
   */
  protected function getValidParams($params) {
    return $params;
  }

}
