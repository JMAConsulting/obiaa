<?php
use CRM_Biasynchandler_ExtensionUtil as E;

/**
 * Biasync.Create API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */

/**
 * Biasync.Create API
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @see civicrm_api3_create_success
 *
 * @throws API_Exception
 */
function civicrm_api3_biasync_Create($request) {
  if (!empty($request) && isset($request['entity']) && isset($request['params'])) {

    //get entity name from the request
    $entity = $request['entity'];
    $params = $request['params'];

    $params['options'] = ['limit' => 0];
    $params['sequential'] = 1;

    $response = [];

    // Alter params based on entity type
    if($entity == 'Activity') {
      $response = syncActivities($params);
    }

    elseif($entity == 'UnitBusiness') {
      $response = syncUnitBusinesses($params);
    }
    
    elseif($entity == 'PropertyOwner') {
      $response = syncUnitBusinesses($params);
    }

    elseif($entity == 'Address') {
      $response = syncAddresses($params);
    }

    elseif($entity == 'Contact') {
      $response = syncContacts($params);
    }

    else {
      $response = syncGeneralEntity($params, $entity);
    }

        // Update or create entity
    if(isset($response['new_entity_created'])){
      $currEntity = civicrm_api3($entity, 'create', $params);
      $response['entity_id'] = $currEntity['values'][0]['id'];
      return civicrm_api3_create_success([$response], $request, 'Biasync', 'Create');
    }

    // Do not update activities if some were found in syncActivities
    else {
      return civicrm_api3_create_success([], $request, 'Biasync', 'Create');
    }
  }
  return civicrm_api3_create_error("Request cannot be blank - ensure enitity and params for syncing are set");
}

function syncGeneralEntity(&$params, $entity) {
    // If an ID is received in the response, the entity exists, and an update operation is triggered.
    $entityCheck = civicrm_api3($entity, 'get', ['source_record_id' => $params['source_record_id'], 'source_record' =>$params['source_record'], 'options' => ['limit' => 0],'sequential' => 1]);

    // Perform update operation using the received parameters
    if (isset($entityCheck['values'][0]['id'])) {
      $params['id'] = $entityCheck['values'][0]['id'];
      unset($params['source_record_id']);
      unset($params['source_record']);
      $response['new_entity_created'] = 0;
    } 

    // No ID received, so the entity does not exist. Proceed with creating the entity.
    else {
      unset($params['id']);
      $response['new_entity_created'] = 1;
    }

    if($entity == 'Units') {
      syncUnits($params, $entityCheck);
    }
    return $response;
}

function syncUnits(&$params, $entityCheck) {

  if(isset($params['unitAddress']) && isset($params['unitArray'])) {
    $unitAddress = $params['unitAddress'];
    $unitArray = $params['unitArray'];

    // If unit was found
    if(isset($params['id'])){
      $unitAddress['id'] = $entityCheck['values'][0]['address_id'];
      $unitArray['id'] = $params['id'];
      unset($unitArray['source_record_id']);
      unset($unitArray['source_record']);
    }

    // If unit was not found
    else {
      unset($unitAddress['id']);
      unset($unitArray['id']);
    }
    $unitAddress['options'] = ['limit' => 0];
    $unitAddress['sequential'] = 1;

    $remoteAddress = civicrm_api3('Address', 'create', $unitAddress);
    $unitArray['address_id'] = $remoteAddress['values'][0]['id'];
  }
}

function syncActivities(&$params) {
  $response = [];
  $activity = civicrm_api3('Activity', 'get', ['custom_' . $params['activityBiaSource'] => $params['custom_' . $params['activityBiaSource']], 'custom_' . $params['activityBiaId'] => $params['custom_' . $params['activityBiaId']], 'options' => ['limit' => 0],'sequential' => 1]);

  // Create new activity if none were found
  if($activity['count'] == 0) {
    unset($params['$activityBiaSource']);
    unset($params['$activityBiaId']);
    $response['new_entity_created'] = 1;
  }
  return $response;
}

function syncUnitBusinesses(&$params) {
  $response = [];
  $params['unit_id'] = civicrm_api3('Unit', 'get', ['source_record_id' => $params['source_record_id'], 'source_record' => $params['source_record'], 'options' => ['limit' => 0],'sequential' => 1])['values'][0]['id'];
  unset($params['source_record_id']);
  unset($params['source_record']);

  $remoteBiaUnitBusiness = civicrm_api3('UnitBusiness', 'get', ['unit_id' => $params['unit_id'], 'business_id' => $params['business_id'], 'options' => ['limit' => 0],'sequential' => 1]);

  if ($remoteBiaUnitBusiness['count'] > 0) {
    $params['id'] = $remoteBiaUnitBusiness['values'][0]['id'];
    $response['new_entity_created'] = 0;
  }
  else {
    unset($params['id']);
    $response['new_entity_created'] = 1;
  }
  return $response;
}

function syncPropertyOwners(&$params) {
  $response = [];
  $params['property_id'] = civicrm_api3('Property', 'get', ['source_record_id' => $params['source_record_id'], 'source_record' => $params['source_record'], 'options' => ['limit' => 0],'sequential' => 1])['values'][0]['id'];
  unset($params['source_record_id']);
  unset($params['source_record']);

  $check = civicrm_api3('PropertyOwner', 'get', ['property_id' => $params['property_id'], 'owner_id' => $params['owner_id'],'options' => ['limit' => 0],'sequential' => 1]);
  if ($check['count'] > 0) {
    $params['id'] = $check['values'][0]['id'];
    $response['new_entity_created'] = 0;
  }
  else {
    unset($params['id']);
    $response['new_entity_created'] = 1;
  }
  return $response;
}

function syncAddresses(&$params) {
  $biaAddress = civicrm_api3('Address', 'get', ['contact_id' => $params['contact_id'], 'is_primary' => 1, 'options' => ['limit' => 0],'sequential' => 1]);
  // If an address was found, update ID to match
  if ($biaAddress['count'] > 0) {
    $params['id'] = $biaAddress['values'][0]['id'];
    $response['new_entity_created'] = 0;
  }
  // If no matching addresses were found, prepare params to create new address
  else {
    unset($params['id']);
    $response['new_entity_created'] = 1;
  }
  return $response;
}

function syncContacts(&$params) {
  $biaContact = civicrm_api3('Contact', 'get', [
  'sequential' => 1,
    'return' => ['first_name', 'last_name', 'email', 'phone'],
    'custom_' . $params['biaContactID'] => $params['custom_' . $params['biaContactID']],
    'custom_' . $params['$biaSource'] => $params['custom_' . $params['$biaSource']],
    'options' => ['limit' => 0],
  ]);

  $contactParams = $params['contactParams'];

  // If a matching Contact was found, update params and create new activity records
  if ($biaContact['count'] > 0) {
    $contactParams['id'] = $biaContact['values'][0]['id'];
    $biaContactId = $biaContact['values'][0]['id'];
    $biaContactCustomFields = $params['biaContactCustomFields'];
    compareRemoteRecord($contactParams, $biaContactId, $biaContactCustomFields);
    $response['new_entity_created'] = 0;
  }
  // If no matching contact was found, prepare params to create a new one
  else {
    unset($contactParams['id']);
    $response['new_entity_created'] = 1;
  }
  $params = $contactParams;
  return $response;
}

function compareRemoteRecord($contactParams, $biaContactId, $biaContactCustomFields): void {
  $options = $differences = [];
  $remoteRecord = civicrm_api3('Contact', 'get', ['id' => $biaContactId, 'return' => array_values($biaContactCustomFields), 'options' => ['limit' => 0]]);
  foreach ($biaContactCustomFields as $customFieldName => $customField) {
    if (isset($contactParams[$customField]) && $contactParams[$customField] != $remoteRecord['values'][0][$customField]) {
      $differences[$customFieldName] = $remoteRecord['values'][$biaContactId][$customField];
    }
  }
  if (!empty($differences)) {
    $message = '<p>The following contact details were changed in the remote sync</p>';
    foreach ($differences as $customField => $originalValue) {
      $message .= '<p>' . $customField . ' Original value was ' . $originalValue . '</p>';
    }
    civicrm_api3('Activity', 'create', [
      'source_contact_id' => 'user_contact_id',
      'target_contact_id' => $biaContactId,
      'activity_type_id' => 'changed_contact_details',
      'subject' => 'Contact Details changed via sync from bia site',
      'details' => $message,
      'status_id' => 'Completed',
      'options' => ['limit' => 0]
    ]);
  }
}
