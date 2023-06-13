<?php

namespace Civi\CiviMobileAPI\PushNotification\Entity;

use Civi\CiviMobileAPI\PushNotification\Utils\PushNotificationSender;
use CRM_CiviMobileAPI_Utils_Request;
use CRM_Core_Session;
use Exception;

class RelationshipPushNotification extends BasePushNotification {

  private $actionText = [
    'create' => '%display_name has created relationship.',
    'edit' => '%display_name has edited relationship.',
    'delete' => '%display_name has deleted relationship.'
  ];

  private $caseID;

  public function handlePostHook() {
    if ($this->entity !== 'Relationship') {
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
      PushNotificationSender::send($title, $message, $contacts, $data);
    }
  
    /**
     * Delete action
     */
    if ($this->action == 'delete') {
      PushNotificationSender::send($title, $message, $contacts, $data);
    }
  }

  protected function getTitle() {
    if ($this->caseID) {
      try {
        $caseTitle = civicrm_api3('Case', 'getvalue', ['return' => 'subject', 'id' => $this->caseID]);
      } catch (Exception $e) {
        $caseTitle = null;
      }
    }

    return (!empty($caseTitle)) ?  $caseTitle : 'Relationship';
  }

  protected function getMessage()
  {
    return $this->actionText[$this->action];
  }

  protected function getContacts() {
    $contacts = [];
    $this->setCaseId();

    if ($this->caseID) {
      $relationship = civicrm_api3('Relationship', 'get', ['case_id' => $this->caseID]);
      
      foreach ($relationship['values'] as $contact) {
        if (intval($contact['is_active'])) {
          $contacts[] = $contact['contact_id_a'];
          $contacts[] = $contact['contact_id_b'];
        }
      }
  
      return array_unique($contacts);
    }

    if (isset($this->entityInstance->contact_id_a) && isset($this->entityInstance->contact_id_b)) {
      $contacts[] = $this->entityInstance->contact_id_a;
      $contacts[] = $this->entityInstance->contact_id_b;
    }

    $contacts = array_unique($contacts);
    $contactId = CRM_Core_Session::singleton()->getLoggedInContactID();
    if ($key = array_search($contactId, $contacts)) {
      unset($contacts[$key]);
    }

    return $contacts;
  }

  private function setCaseId() {
    if ($this->action == 'create') {
      $this->caseID = isset($this->entityInstance->case_id) ? $this->entityInstance->case_id : null;
    }
  
    if ($this->action == 'edit') {
      if (isset($this->entityInstance->case_id)) {
        $this->caseID = $this->entityInstance->case_id;
      }
      else {
        $this->caseID = CRM_CiviMobileAPI_Utils_Request::getInstance()->post('case_id', 'String');
      }
    }
  }
}
