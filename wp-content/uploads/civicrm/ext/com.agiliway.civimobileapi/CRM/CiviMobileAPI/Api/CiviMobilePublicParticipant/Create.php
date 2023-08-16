<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_Api_CiviMobilePublicParticipant_Create extends CRM_CiviMobileAPI_Api_CiviMobileBase {

  /**
   * Returns results to api
   *
   * @return array
   * @throws CiviCRM_API3_Exception
   */
  public function getResult() {
    $publicKeyFieldId = "custom_" . CRM_CiviMobileAPI_Utils_CustomField::getId(
      CRM_CiviMobileAPI_Install_Entity_CustomGroup::PUBLIC_INFO,
      CRM_CiviMobileAPI_Install_Entity_CustomField::PUBLIC_KEY
      );
    $result = civicrm_api3('Participant', 'create', [
      'event_id' => $this->validParams["event_id"],
      'contact_id' => $this->validParams["contact_id"],
      'role_id' => $this->validParams["default_role_id"]
    ]);

    $publicKey = CRM_CiviMobileAPI_Utils_Participant::generatePublicKey($result['id']);
    $result = civicrm_api3('Participant', 'create', [
      'id' => $result['id'],
      $publicKeyFieldId => $publicKey
    ]);

    foreach ($result["values"] as $key => $participant) {
      $result["values"][$key]['participant_public_key'] = $publicKey;
    }

    $this->sendEmail($result['id']);

    return $result['values'];
  }

  /**
   * Returns validated params
   *
   * @param $params
   * @return array
   * @throws api_Exception
   */
  protected function getValidParams($params) {
    $event = new CRM_Event_BAO_Event();
    $event->id = $params['event_id'];
    $event->is_public = 1;
    $eventExistence = $event->find(TRUE);
    if (empty($eventExistence)) {
      throw new api_Exception(E::ts('Event(id=' . $params['event_id'] . ') does not exist or is not public.'), 'public_event_does_not_exist');
    }

    $contactId = $this->getContactId($params);
    $this->updateContactFields($contactId, $params);

    $result = [
      'event_id' => $params["event_id"],
      'contact_id' => $contactId,
      'default_role_id' => $event->default_role_id,
      'contact_email' => $params['contact_email']
    ];

    return $result;
  }

  /**
   * Gets ContactId by Email.
   * If not exist contact with given email, it creates new Contact with
   *
   * @param $params
   * @return integer
   * @throws api_Exception
   */
  private function getContactId($params) {
    try {
      $contactByEmail = civicrm_api3('Email', 'getsingle', [
        'sequential' => 1,
        'is_primary' => 1,
        'email' => $params["contact_email"],
      ]);
    } catch (CiviCRM_API3_Exception $e) {
      return $this->createContact($params);
    }

    return (int) $contactByEmail["contact_id"];
  }

  /**
   * Creates new Contact by params
   *
   * @param $params
   * @return integer
   * @throws api_Exception
   */
  private function createContact($params) {
    try {
      $contact = civicrm_api3('Contact', 'create', [
        'contact_type' => "Individual",
        'first_name' => $params["first_name"],
        'last_name' => $params["last_name"],
      ]);
    } catch (CiviCRM_API3_Exception $e) {
      throw new api_Exception(E::ts('Can not create Contact. Error: ') . $e->getMessage(), 'can_not_create_contact');
    }

    try {
      civicrm_api3('Email', 'create', [
        'contact_id' => $contact["id"],
        'email' => $params["contact_email"],
        'is_primary' => 1,
      ]);
    } catch (CiviCRM_API3_Exception $e) {
      throw new api_Exception(E::ts('Can not create Email to Contact. Error: ') . $e->getMessage(), 'can_not_create_email_to_contact');
    }

    return (int) $contact["id"];
  }

  /**
   * Updates first_name and last_name for contact by contact_id
   *
   * @param $contactId
   * @param $params
   */
  private function updateContactFields($contactId, $params) {
    try {
      civicrm_api3('Contact', 'create', [
        'contact_id' => $contactId,
        'first_name' => $params["first_name"],
        'last_name' => $params["last_name"],
      ]);
    } catch (CiviCRM_API3_Exception $e) {
      throw new api_Exception(E::ts('Can not update Contact. Error: ') . $e->getMessage(), 'can_not_update_contact');
    }
  }

  /**
   * Send email for participant
   *
   * @param $participantId
   */
  private function sendEmail($participantId) {
    try {
      $event = civicrm_api3('Event', 'getsingle', [
        'id' => $this->validParams["event_id"]
      ]);
    } catch (Exception $e) {
      return;
    }

    try {
      $participant = civicrm_api3('Participant', 'getsingle', [
        'id' => $participantId
      ]);
    } catch (Exception $e) {
      return;
    }

    $emailParams = [
      'participant' => $participant,
      'event' => $event,
      'params' => [
        $participantId => [
          'first_name' => $participant['first_name'],
          'last_name' => $participant['last_name'],
          'email-Primary' => $this->validParams['contact_email'],
          'is_primary' => 1,
          'is_pay_later' => $event['is_pay_later'],
          'contact_id' => $participant['contact_id'],
          'defaultRole' => $event['default_role_id'],
          'participant_role_id' => $participant['participant_role_id'],
          'description' => E::ts('Event Registration') . ' ' . $event['title'],
        ]
      ]
    ];

    $smarty = CRM_Core_Smarty::singleton();
    $smarty->assign('event', $event);

    CRM_Event_BAO_Event::sendMail($participant["contact_id"], $emailParams, $participant['participant_id']);
  }

}
