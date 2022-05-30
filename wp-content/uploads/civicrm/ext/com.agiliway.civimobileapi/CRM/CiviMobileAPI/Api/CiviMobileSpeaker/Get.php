<?php

class CRM_CiviMobileAPI_Api_CiviMobileSpeaker_Get extends CRM_CiviMobileAPI_Api_CiviMobileBase {

  /**
   * Returns results to api
   *
   * @return array
   * @throws CiviCRM_API3_Exception
   */
  public function getResult() {
    $preparedSpeakers = [];

    $speakers = CRM_CiviMobileAPI_BAO_EventSessionSpeaker::getSpeakersBelongedToSessionsByEvent($this->validParams);
    $participantBioFieldName = $customFieldName = "custom_" . CRM_CiviMobileAPI_Utils_CustomField::getId(CRM_CiviMobileAPI_Install_Entity_CustomGroup::AGENDA_PARTICIPANT,
        CRM_CiviMobileAPI_Install_Entity_CustomField::AGENDA_PARTICIPANT_BIO);

    foreach ($speakers as $speaker) {
      try {
        $participant = civicrm_api3('Participant', 'getsingle', [
          'event_id' => $this->validParams['event_id'],
          'id' => $speaker['speaker_id']
        ]);
      } catch (Exception $e) {
        continue;
      }

      $preparedSpeakers[] = [
        'participant_id' => $speaker['speaker_id'],
        'contact_id' => $participant['contact_id'],
        'event_id' => $participant['event_id'],
        'display_name' => $participant['display_name'],
        'participant_register_date' => $participant['participant_register_date'],
        'participant_bio' => !empty($participant[$participantBioFieldName]) ? $participant[$participantBioFieldName] : '',
        'image_URL' => !empty($speaker['image_URL']) ? $speaker['image_URL'] : '',
        'job_title' => !empty($speaker['job_title']) ? $speaker['job_title'] : '',
        'current_employer' => !empty($speaker['organization_name']) ? $speaker['organization_name'] : '',
        'current_employer_id' => !empty($speaker['employer_id']) ? $speaker['employer_id'] : '',
        'first_name' => !empty($speaker['first_name']) ? $speaker['first_name'] : '',
        'last_name' => !empty($speaker['last_name']) ? $speaker['last_name'] : ''
      ];
    }

    return $preparedSpeakers;
  }

  /**
   * Returns validated params
   *
   * @param $params
   * @return array
   * @throws api_Exception
   */
  protected function getValidParams($params) {
    if (!CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionForGetSpeaker()) {
      throw new api_Exception('You don`t have enough permissions.', 'do_not_have_enough_permissions');
    }

    return $params;
  }

}
