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

    $currEntity = civicrm_api3($entity, 'create', $params);
    $response['entity_id'] = $currEntity['values'][0]['id'];

    return civicrm_api3_create_success([$response], $request, 'Biasync', 'Create');
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
  if ($biaAddress['count'] > 0) {
    $params['id'] = $biaAddress['values'][0]['id'];
    $response['new_entity_created'] = 0;
  }
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

  if ($biaContact['count'] > 0) {
    $contactParams['id'] = $biaContact['values'][0]['id'];
    $biaContactId = $biaContact['values'][0]['id'];
    $biaContactCustomFields = $params['biaContactCustomFields'];
    compareRemoteRecord($contactParams, $biaContactId, $biaContactCustomFields);
    $response['new_entity_created'] = 0;
  }
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

// /**
//  * Create, update and delete property
//  */
// function syncProperties($params) {
//   //check duplicates for property
//   // $request = [
//   //   "entity" => "Property",
//   //   "params" => [
//   //     'created_id' => 1,
//   //     'modified_id' => 1,
//   //     'modified_date' => 1,
//   //     'roll_no' => 12345678,
//   //     'property_address' => 'test',
//   //     'city' => 'test',
//   //     'postal_code' => 'test',
//   //     'name' => 'test',
//   //     'source_record_id' => 45,
//   //     'source_record' => 'bia1',
//   //   ]
//   // ];

//   // check if record exists by source id and source record name
//   $properties = \Civi\Api4\Property::get(TRUE)
//     ->addWhere('source_record_id', '=', $params['source_record_id'])
//     ->addWhere('source_record', '=', $params['source_record'])
//     ->execute();
  
//     if($properties->count() != 0)
//     {
      
//     }


//   /************************* add new property **************************/
//   //get address_id by property address
//   $propertyAddress = civicrm_api3('Address', 'get', ['street_address' => $propertyCheck['property_address']]);
//   $addressId = (!empty($propertyAddress['id'])) ? $propertyAddress['id'] : NULL;

//   // check if server id exists
//   if (empty($propertyCheck['id'])) {
//     //*** 1 *** create a new property
//     if (array_key_exists('id', $params)) {
//       unset($params['id']);
//     }
//     $prop = civicrm_api3('Property', 'create', $params);
//     //*** 2 *** create a new unit
//     civicrm_api3('Unit', 'create', ['address_id' => $addressId, 'property_id' => $prop['id'], 'source_record_id' => $params['source_record_id'], 'source_record' => $params['source_record']]);
//   }
//   else {
//     /************************* update property **************************/
//     //*** 1 ***  update existing property
//     $params['id'] = $propertyCheck['id'];
//     $prop = civicrm_api3('Property', 'create', $params);
//     $units = unit::get()->addWhere('property_id', '=', $propertyCheck['id'])->addWhere('source_record_id', '=', $params['source_record_id'])->addWhere('source_record', '=', $params['source_record'])->execute();
//     if (!empty($units)) {
//       foreach ($units as $unit) {
//         //*** 2 *** update existing unit
//         civicrm_api3('Unit', 'create', ['id' => $unit['id'], 'address_id' => $addressId, 'property_id' => $propertyCheck['id'], 'source_record_id' => $params['source_record_id'], 'source_record' => $params['source_record']]);
//       }
//     }
//   }
//   /************************* delete property **************************/
//   // if (!empty($params['delete_property_id'])) {
//   //   $missingProperties = civicrm_api3('Property', 'get', ['source_record_id' => $params['delete_property_id'], 'source_record' => $params['source_record']]);
//   //   if (!empty($missingProperties)) {
//   //     //*** 1 *** delete propertyowner by property_id
//   //     $propertyOwners = civicrm_api3('PropertyOwner', 'get', ['property_id' => $params['delete_property_id']]);
//   //     if (!empty($propertyOwners['id'])) {
//   //       civicrm_api3('PropertyOwner', 'delete', ['property_id' => $params['delete_property_id']]);
//   //     }
//   //     //*** 2 *** delete unit by property_id
//   //     $Units = civicrm_api3('Unit', 'get', ['property_id' => $params['delete_property_id'], 'source_record_id' => $params['source_record_id'], 'source_record' => $params['source_record']]);
//   //     if (!empty($Units['id'])) {
//   //       civicrm_api3('Unit', 'delete', ['property_id' => $params['delete_property_id'], 'source_record_id' => $params['source_record_id'], 'source_record' => $params['source_record']]);
//   //     }
//   //   }
//   // }
// }

// /**
//  * Create, update and delete contact
//  */
// function syncContacts($params) {
//   // $request = [
//   //   "entity" => "Contact",
//   //   "params" => [
//   //     'id' => $contact['contact_id'],
//   //     'source_record_id' => 45,
//   //     'source_record' => 'bia1',
//   //     'contact_type' => 'Individual',
//   //     'contact_sub_type' => 'Obiaa_Staff',
//   //     'first_name' => 'test',
//   //     'last_name' => 'test',
//   //     'organization_name' => 'test1',
//   //     'custom_' . $biaContactID => $contact['id'],
//   //     'Membership_Status.Voting_Status' => '',
//   //     'Membership_Status.Region' => 'South Central',
//   //     'Membership_Status.BIA' => 'My Bia bia1',
//   //     'street_address' => 'test',
//   //     'is_primary' => 1,
//   //     'street_number' => 14,
//   //     'is_billing' => 0,
//   //     'location_type_id' => 1,
//   //     'street_name' => 'test',
//   //     'street_type' => 'test',
//   //     'city' => 'test',
//   //     'state_province_id' => 'test',
//   //     'postal_code' => 'test',
//   //     'country_id' => 'test',
//   //     'geo_code_1' => null,
//   //     'geo_code_2' => null,
//   //     'manual_geo_code' => 0
//   //   ]
//   // ];

//   $contactCheck = civicrm_api3('Contact', 'get', ['source_record_id' => $params['id'], 'source_record' => $params['source_record']]);
//   /************************* add or update contact **************************/
//   //*** 1 *** add a new contact or update existing contact
//   if (empty($contactCheck['id'])) {
//     if (array_key_exists('id', $params)) {
//       unset($params['id']);
//     }
//   }
//   else {
//     $params['id'] = $contactCheck['id'];
//   }
//   civicrm_api3('Contact', 'create', $params);

//   /************************* sync activity **************************/
//   //*** 2 *** sync activities
//   syncActivities($params);
//   /************************* add or update address **************************/
//   //*** 3 *** add a new address
//   $addressCheck = civicrm_api3('Address', 'get', ['contact_id' => $params['id']]);
//   if (empty($addressCheck['id'])) {
//     if (array_key_exists('id', $params)) {
//       unset($params['id']);
//     }
//   }
//   else {
//     $params['id'] = $addressCheck['id'];
//   }
//   civicrm_api3('Address', 'create', $params);

//   /************************* add or update unitbusiness **************************/
//   //*** 4 *** add a new UnitBusiness
//   // get unit id by source_record_id and source_record
//   $unitCheck = civicrm_api3('Unit', 'get', ['source_record_id' => $params['source_record_id'], 'source_record' => $params['source_record']]);
//   $unitId = (!empty($unitCheck['id'])) ? $unitCheck['id'] : NULL;

//   $unitBusinessesCheck = UnitBusiness::get(FALSE)->addWhere('business_id', '=', $params['id'])->execute();
//   if (empty($unitBusinessesCheck)) {
//     //add new unitbusiness
//     civicrm_api3('UnitBusiness', 'create', ['unit_id' => $unitId, 'business_id' => $params['id']]);
//   }
//   else {
//     //update unitbusiness
//     foreach ($unitBusinessesCheck as $unitBusiness) {
//       civicrm_api3('UnitBusiness', 'create', ['id' => $unitBusiness['id'], 'unit_id' => $unitId, 'business_id' => $params['id']]);
//     }
//   }

//   /************************* add or update property owner **************************/
//   //*** 5 *** add a new property owner
//   $propertyOwnerCheck = PropertyOwner::get(FALSE)->addWhere('owner_id', '=', $params['id'])->execute();
//   $propertyCheck = civicrm_api3('Property', 'get', ['source_record_id' => $params['source_record_id'], 'source_record' => $params['source_record']]);
//   $propertyId = (!empty($propertyCheck['id'])) ? $propertyCheck['id'] : NULL;
//   if (empty($propertyOwnerCheck)) {
//     //add new property owner
//     civicrm_api3('PropertyOwner', 'create', ['property_id' => $propertyId, 'owner_id' => $params['id'], 'is_voter' => 1]);
//   }
//   else {
//     //update property owner
//     foreach ($propertyOwnerCheck as $propertyOwner) {
//       civicrm_api3('PropertyOwner', 'create', ['id' => $propertyOwner['id'], 'property_id' => $propertyId, 'owner_id' => $params['id'], 'is_voter' => 1]);
//     }
//   }

//   /************************* add or update custom fields **************************/
//   //*** 6 *** add a new custom field
//   $contactCustomFields = \Civi\Api4\CustomField::get(TRUE)
//   ->addWhere('custom_group_id.extends', '=', 'Contact')
//   ->execute();

//   if (empty($customFieldCheck)) {
//     if (array_key_exists('id', $params)) {
//       unset($params['id']);
//     }
//   }
//   else {
//     //update custom field
//     foreach ($customFieldCheck as $customField) {
//       $customName = "custom_$customField";
//       civicrm_api3('Contact', 'create', [
//         $customName => "hold",
//         'id' => $params['id'],
//       ]);
//     }
//   }


//   /************************* add or update relationships **************************/
// }

// /**
//  * Create, update and delete activity
//  * */
// function syncActivies($params) {
//   // $request = [
//   //   "entity" => "Activity",
//   //   "params" => [
//   //     'id' => $activity['id'],
//   //     'target_contact_id' => 0,
//   //     'source_contact_id' => 0,
//   //   ]
//   // ];
//   $activityCheck = civicrm_api3('Activity', 'get', ['id' => $params['id']]);
//   if (empty($activityCheck['id'])) {
//     if (array_key_exists('id', $params)) {
//       unset($params['id']);
//     }
//   }
//   else {
//     $params['id'] = $activityCheck['id'];
//   }

//   civicrm_api3('Activity', 'create', ['target_contact_id' => $params['target_contact_id'], 'source_contact_id' => $params['source_contact_id']]);
// }
