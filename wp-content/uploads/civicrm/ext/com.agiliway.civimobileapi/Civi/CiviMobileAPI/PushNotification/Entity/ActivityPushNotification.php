<?php

namespace Civi\CiviMobileAPI\PushNotification\Entity;

use Civi\CiviMobileAPI\PushNotification\Utils\PushNotificationSender;
use CiviCRM_API3_Exception;
use CRM_Activity_BAO_ActivityContact;
use CRM_CiviMobileAPI_Utils_Activity;
use CRM_Core_DAO;
use CRM_Utils_Array;
use Exception;

class ActivityPushNotification extends BasePushNotification {
  
  private $actionText = [
    'create' => '%display_name has created activity.',
    'edit' => '%display_name has edited activity.',
    'delete' => '%display_name has deleted activity.'
  ];

  public function handlePostHook() {
    if ($this->entity !== 'Activity') {
      return;
    }

    if (empty($this->id) || empty($this->entity)) {
      return;
    }

    if (!empty($this->entityInstance->case_id)) {
      return;
    }

    if (CRM_CiviMobileAPI_Utils_Activity::isActivityInCase($this->id)) {
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

    /**
     * Create action
     */
    if ($this->action == 'create') {
      if ($this->entityInstance->activity_type_id == CRM_CiviMobileAPI_Utils_Activity::getReassignedCaseValue()) {
        
        $caseId = $this->getCaseIdFromActivitySubject($this->entityInstance->subject);
        $prevCaseContacts = $this->getContactsIDFromCase($caseId);
        
        $newCaseId = $this->getNewCaseIdFromActivitySubject($this->entityInstance->subject);
        $newCaseContacts = $this->getContactsIDFromCase($newCaseId);
        
        $contacts = array_unique(array_merge($prevCaseContacts, $newCaseContacts));
        
        PushNotificationSender::send($this->getCaseTitleById($newCaseId), $title, $contacts, $data);
        return;
      }
  
      if ($this->entityInstance->activity_type_id == CRM_CiviMobileAPI_Utils_Activity::getChangeCaseStartDateValue()) {
        return;
      }

      if ($this->entityInstance->activity_type_id == CRM_CiviMobileAPI_Utils_Activity::getOpenCaseValue()) {
        return;
      }
  
      if ($this->entityInstance->activity_type_id == CRM_CiviMobileAPI_Utils_Activity::getAssignCaseRoleValue()) {
        return;
      }
  
      if ($this->entityInstance->activity_type_id == CRM_CiviMobileAPI_Utils_Activity::getEventRegistrationValue()) {
        return;
      }
  
      if ($this->entityInstance->activity_type_id == CRM_CiviMobileAPI_Utils_Activity::getContributionValue()) {
        return;
      }
      PushNotificationSender::send($title, $message, $contacts, $data);
    }
  
    /**
     * Edit action
     */
    if ($this->action == 'edit') {
      PushNotificationSender::send($title, $message, $contacts, $data);
    }
  }
  
  private function getCaseIdFromActivitySubject($subject) {
    preg_match_all('/Case ([0-9]+) reassigned client from /', $subject, $matches);
    return $matches[1][0];
  }
  
  private function getNewCaseIdFromActivitySubject($subject) {
    preg_match_all('/New Case ID is ([0-9]+)\./', $subject, $matches);
    return $matches[1][0];
  }
  
  private function getContactsIDFromCase($caseId) {
    try {
      $result = civicrm_api3('Case', 'get', [
        'sequential' => 1,
        'id' => $caseId,
      ]);
    } catch (CiviCRM_API3_Exception $e) {
      return [];
    }
    
    $contacts = $result['values'][0]['contacts'];
    $contactsID = [];
    
    foreach ($contacts as $contact) {
      $contactsID[] = $contact['contact_id'];
    }
    
    return $contactsID;
  }
  
  public function handlePreHook() {
    if ($this->entity !== 'Activity') {
      return;
    }
    
    if (empty($this->id) || empty($this->entity)) {
      return;
    }
    
    if (!empty($this->entityInstance->case_id)) {
      return;
    }
    
    if (CRM_CiviMobileAPI_Utils_Activity::isActivityInCase($this->id)) {
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
  
  /**
   * Gets contact (single or plural) which related to entity
   *
   * @return array
   */
  protected function getContacts()
  {
    $contacts = [];
    
    $activityContacts = CRM_Activity_BAO_ActivityContact::buildOptions('record_type_id', 'validate');

    $targetID = CRM_Utils_Array::key('Activity Targets', $activityContacts);
    $assigneeID = CRM_Utils_Array::key('Activity Assignees', $activityContacts);

    $targetActivityContacts = CRM_Activity_BAO_ActivityContact::getNames($this->id, $targetID, true);
    $contacts['target_contact'] = end($targetActivityContacts);
    
    $assigneeActivityContacts = CRM_Activity_BAO_ActivityContact::getNames($this->id, $assigneeID, true);
    $contacts['assignee_contact'] = end($assigneeActivityContacts);
    
    $prepareContacts = array_merge($contacts['target_contact'], $contacts['assignee_contact']);

    if ($this->action == 'edit' || $this->action == 'delete') {
      $sourceID = CRM_Utils_Array::key('Activity Source', $activityContacts);
      $sourceActivityContacts = CRM_Activity_BAO_ActivityContact::getNames($this->id, $sourceID, true);
      $contacts['source_contact'] = end($sourceActivityContacts);
      $prepareContacts = array_merge($prepareContacts, $contacts['source_contact']);
    }

    return array_unique($prepareContacts);
  }
  
  /**
   * Gets text for push notification
   *
   * @return string
   */
  protected function getMessage()
  {
    return $this->actionText[$this->action];
  }
  
  /**
   * Gets title of entity
   *
   * @return string
   */
  protected function getTitle()
  {
    if ($this->id) {
      try {
        $activityTitle = CRM_Core_DAO::getFieldValue('CRM_Activity_BAO_Activity', $this->id, 'subject');
      } catch (Exception $e) {
        $activityTitle = null;
      }
    }
    
    return (!empty($activityTitle)) ?  $activityTitle : 'Activity';
  }
  
  /**
   * Gets title of case for reassign action
   *
   * @return string
   */
  protected function getCaseTitleById($caseId)
  {
    try {
      $result = civicrm_api3('Case', 'get', [
        'sequential' => 1,
        'return' => ["subject"],
        'id' => $caseId,
      ]);
    } catch (Exception $e) {
      $result = null;
    }
    
    return (!empty($result['values'][0]['subject'])) ?  $result['values'][0]['subject'] : 'Case reassigned';
  }
}
