<?php

namespace Civi\CiviMobileAPI\PushNotification\Entity;

use Civi\CiviMobileAPI\PushNotification\Utils\PushNotificationSender;
use CRM_CiviMobileAPI_Utils_Request;
use CRM_Utils_Request;

class CasePushNotification extends BasePushNotification {
  
  private $actionText = [
    'create' => '%display_name has created case.',
    'edit' => '%display_name has edited case.',
    'delete' => '%display_name has deleted case.',
  ];
  
  public function handlePostHook() {
    if ($this->entity !== 'Case') {
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
  }
  
  public function handlePreHook() {
    if ($this->entity !== 'Case') {
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
     * Delete action
     */
    if ($this->action == 'delete') {
      PushNotificationSender::send($title, $message, $contacts, $data);
    }
  }
  
  protected function getTitle()
  {
    if (CRM_Utils_Request::retrieve('activity_subject', 'String')) {
      return CRM_Utils_Request::retrieve('activity_subject', 'String');
    }
    
    if (CRM_Utils_Request::retrieve('subject', 'String')) {
      return CRM_Utils_Request::retrieve('subject', 'String');
    }
    
    if (!empty($this->entityInstance->subject)) {
      return $this->entityInstance->subject;
    }
    
    return 'Case';
  }
  
  protected function getMessage()
  {
    return $this->actionText[$this->action];
  }
  
  /**
   * Gets contact (single or plural) which related to entity
   *
   * @return array
   */
  protected function getContacts()
  {
    if ($this->action == "create") {
      $contacts = [];
  
      $contactID = CRM_CiviMobileAPI_Utils_Request::getInstance()->get('cid', 'String');
      
      if (isset($contactID)) {
        $contacts[] = $contactID;
        return $contacts;
      }
  
      $paramsJson = CRM_CiviMobileAPI_Utils_Request::getInstance()->get('json', 'String');
      $contactId = isset($paramsJson) ? json_decode($paramsJson)->contact_id : null;
      
      if (isset($contactId)) {
        $contacts[] = $contactId;
        return $contacts;
      }
  
      return isset($_POST['client_id']) ? [$_POST['client_id']] : [];
    }
    
    // Get case relationship contacts
    if ($this->action == "edit" || $this->action == "delete") {
      $contacts = [];
  
      $relationship = civicrm_api3('Relationship', 'get', ['case_id' => $this->id]);
      foreach ($relationship['values'] as $contact) {
        if (intval($contact['is_active'])) {
          $contacts[] = $contact['contact_id_a'];
          $contacts[] = $contact['contact_id_b'];
        }
      }
  
      return array_unique($contacts);
    }
    
    return [];
  }
}
