<?php

namespace Civi\Api4\Action\CiviMobileEventRegistration;

use API_Exception;
use Civi\Api4\Generic\BasicCreateAction;
use Civi\Api4\Generic\Result;
use CRM_CiviMobileAPI_ExtensionUtil as E;
use CRM_Contact_BAO_Contact;
use CRM_Core_BAO_CustomField;
use CRM_Core_BAO_UFGroup;
use CRM_Core_DAO;
use CRM_Core_Session;
use CRM_Core_Transaction;
use CRM_Event_BAO_Participant;
use Exception;

class Create extends BasicCreateAction {
  
  /**
   * @param  Result  $result
   * @return Result
   * @throws API_Exception
   */
  public function _run(Result $result) {
    $params = $this->getParams()['values'];
    
    if (isset($params['event_id'])) {
      $eventId = $params['event_id'];
    } else {
      throw new api_Exception(E::ts("Field 'event_id' is required"));
    }
    
    $contactId = $params['contact_id'] ?? CRM_Core_Session::getLoggedInContactID();
    $fields = $this->getProfileFields($eventId);
    $requiredProfileFields = $this->getRequiredProfileFields($fields);
    
    $this->ensureRequiredFieldsExist($requiredProfileFields, $params);
    
    $isEventAvailable = $this->checkAvailablePlacesOnEvent($eventId);
    $hasRegistrationOnEvent = $this->checkParticipantRegistrationOnEvent($contactId, $eventId);
    
    if ($isEventAvailable) {
      if (!$hasRegistrationOnEvent) {
        $transaction = new CRM_Core_Transaction();
        try {
          $this->updateContactFields($params, $fields, $contactId);
          $participantId = $this->addParticipantToEvent($params, $contactId);
          $transaction->commit();
        } catch (Exception $e) {
          $transaction->rollback();
          throw new api_Exception(E::ts('Participant registration error.'));
        }
      } else {
        throw new api_Exception(E::ts('You already registered on Event.'));
      }
    } else {
      throw new api_Exception(E::ts('The event is currently full.'));
    }
    
    $result[] = ['participant_create' => $participantId ? 1 : 0];
    
    return $result;
  }
  
  private function getRequiredProfileFields($fields) {
    $requiredProfileFields = ['event_id'];
    
    foreach ($fields as $field) {
      if ($field['is_required'] == 1) {
        $requiredProfileFields[] = $field['name'];
      }
    }
    
    return $requiredProfileFields;
  }
  
  private function ensureRequiredFieldsExist($requiredProfileFields, $params) {
    foreach ($requiredProfileFields as $field) {
      if (empty($params[$field])) {
        throw new api_Exception(E::ts("Field '{$field}' is required."));
      }
    }
  }
  
  private function addParticipantToEvent($params, $contactId) {
    $participantParams = [
      'contact_id' => $contactId,
      'event_id' => $params['event_id'],
      'status_id' => $params['participant_status'] ?? 1,
      'role_id' => $params['participant_role'] ?? CRM_Event_BAO_Participant::getDefaultRoleID(),
      'register_date' => $params['participant_register_date'] ?? date('YmdHis'),
      'note' => $params['participant_note'] ?? NULL,
      'fee_level' => $params['amount_level'] ?? NULL,
      'is_pay_later' => $params['is_pay_later'] ?? 0,
      'fee_amount' => $params['fee_amount'] ?? NULL,
      'registered_by_id' => $params['registered_by_id'] ?? NULL,
      'discount_id' => $params['discount_id'] ?? NULL,
      'fee_currency' => $params['currencyID'] ?? NULL,
      'campaign_id' => $params['campaign_id'] ?? NULL,
    ];
    
    $participantParams['custom'] = [];
    
    foreach ($params as $paramName => $paramValue) {
      if (strpos($paramName, 'custom_') === 0) {
      [$customFieldID, $customValueID] = CRM_Core_BAO_CustomField::getKeyID($paramName, TRUE);
      CRM_Core_BAO_CustomField::formatCustomField($customFieldID, $participantParams['custom'], $paramValue, 'Participant', $customValueID);
      }
    }
    
    return civicrm_api4('Participant', 'create', [
      'values' => $participantParams,
      'checkPermissions' => FALSE,
    ])->first()['id'];
  }
  
  private function checkAvailablePlacesOnEvent($eventId): bool {
    $eventOpenSpaces = CRM_Event_BAO_Participant::eventFull($eventId, TRUE, FALSE);
    
    return $eventOpenSpaces && is_numeric($eventOpenSpaces) || $eventOpenSpaces === NULL;
  }
  
  private function checkParticipantRegistrationOnEvent($contactId, $eventId): bool {
    $participantCount = civicrm_api4('Participant', 'get', [
      'select' => ['id'],
      'where' => [['contact_id', '=', $contactId], ['event_id', '=', $eventId]],
      'checkPermissions' => FALSE,
    ])->count();
    
    return $participantCount != 0;
  }
  
  private function getProfileFields($eventId): array {
    $ufJoin = civicrm_api4('UFJoin', 'get', [
      'where' => [
        ['entity_table', '=', "civicrm_event"],
        ['entity_id', '=', $eventId],
      ],
      'checkPermissions' => FALSE,
    ])->first();
    
    return CRM_Core_BAO_UFGroup::getFields($ufJoin['uf_group_id']);
  }
  
  private function updateContactFields($params, $fields, $contactId) {
    $contactParams = [];
    
    foreach ($params as $key => $value) {
      if (isset($fields[$key]) && in_array($fields[$key]['field_type'], ['Individual', 'Contact'])) {
        $contactParams[$key] = $value;
      }
    }
    
    $contactType = CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_Contact', $contactId, 'contact_type');
    
    return CRM_Contact_BAO_Contact::createProfileContact($contactParams, $fields, $contactId, NULL, NULL, $contactType, TRUE);
  }
}
