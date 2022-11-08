<?php

namespace Civi\CiviMobileAPI\PushNotification\Entity;

use Civi\CiviMobileAPI\PushNotification\Utils\ParticipantManager;
use Civi\CiviMobileAPI\PushNotification\Utils\PushNotificationSender;
use CRM_CiviMobileAPI_Install_Entity_CustomGroup;
use CRM_CiviMobileAPI_Utils_CustomField;
use CRM_CiviMobileAPI_Install_Entity_CustomField;
use Exception;

class ParticipantPushNotification extends BasePushNotification {

  private $actionText = [
    'create' => "%display_name has created participant.",
    'edit' => "%display_name has edited your event's participation.",
    'delete' => "%display_name has deleted you from event."
  ];

  public function handlePostHook() {
    if ($this->entity !== 'Participant') {
      return;
    }

    $contacts = $this->getContacts();
    $message = $this->getMessage();
    $title = $this->getTitle();

    $data = [
      'entity' => $this->entity,
      'id' => $this->id,
      'body' => $message
    ];

    $isContactExist = isset($contacts) && !empty($contacts) && !empty($title);

    if (!$isContactExist) {
      return;
    }

    $participantManager = ParticipantManager::getInstance();
        
    /**
     * Create action
     */
    if ($this->action == 'create') {
      PushNotificationSender::send($title, $message, $contacts, $data);
    }

    /**
     * Edit action
     */
    if ($this->action == 'edit') {
      if ($participantManager->isParticipantIdInStorage($this->entityInstance->contact_id)) {
        $participantManager->deleteParticipantIdFromStorage($this->entityInstance->contact_id);
        return;
      }   
      PushNotificationSender::send($title, $message, $contacts, $data);
    }
  }

  public function handlePreHook() {
    if ($this->entity !== 'Participant') {
      return;
    }

    if($this->action == "create") {
      $customQrCode = "custom_" . CRM_CiviMobileAPI_Utils_CustomField::getId(
        CRM_CiviMobileAPI_Install_Entity_CustomGroup::QR_CODES,
        CRM_CiviMobileAPI_Install_Entity_CustomField::QR_CODE);
      $participantManager = ParticipantManager::getInstance();

      if (isset($customQrCode)) {
        $participantManager->addParticipantIds($this->entityInstance['contact_id']);
      }
    }

    
    $contacts = $this->getEventContactByParticipantId($this->id);
    $message = $this->getMessage();
    $title = $this->getTitle();

    $data = [
      'entity' => $this->entity,
      'id' => $this->id,
      'body' => $message
    ];

    $isContactExist = isset($contacts) && !empty($contacts) && !empty($title);

    if (!$isContactExist) {
      return;
    }

    /**
     * Delete action
     */
    if ($this->action == 'delete') {
      PushNotificationSender::send($title, $message, $contacts, $data);
    }
  }

  protected function getTitle() {
    if (isset($this->entityInstance->event_id)) {
      try {
        $eventTitle = civicrm_api3('Event', 'getvalue', ['return' => "title", 'id' => $this->entityInstance->event_id]);
      } catch (Exception $e) {
        $eventTitle = null;
      }
    }

    return (!empty($eventTitle)) ? $eventTitle : 'Participant';
  }

  protected function getMessage() {
    return $this->actionText[$this->action];
  }

  protected function getContacts() {
    if (isset($this->entityInstance->contact_id)) {
      $contacts[] = $this->entityInstance->contact_id;
      return $contacts;
    }

    return null;
  }

  protected function getEventContactByParticipantId($participantId) {
    $contacts = [];

    $participants = civicrm_api3('Participant', 'get', [
      'return' => ["contact_id"],
      'id' => $participantId,
    ]);

    foreach ($participants['values'] as $contact) {
      $contacts[] = $contact['contact_id'];
    }

    return array_unique($contacts);
  }
}
