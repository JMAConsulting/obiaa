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
  if (!empty($request)) {
    //get entity name from the parameters
    $entity = $request['entity'];
    $params = $request['params'];

    // If we receive a $response['id'], then entity exists and we proceed to update the entity with the params sent.
    switch ($entity) {
      case "Property":
        //synchronized property changes
        syncProperties($params);
        break;

      case "Contact":
        //synchronized contact changes
        syncContact($params);
        break;

      default:
        break;
    }

    // Spec: civicrm_api3_create_success($values = 1, $params = [], $entity = NULL, $action = NULL)
    return civicrm_api3_create_success($entity, $request, 'Biasync', 'Create');
  }
}

/**
 * Create, update and delete property
 */
function syncProperties($params) {
  //check duplicates for property
  // $request = [
  //   "entity" => "Property",
  //   "params" => [
  //     'id' => 45,
  //     'created_id' => 1,
  //     'modified_id' => 1,
  //     'modified_date' => 1,
  //     'roll_no' => 12345678,
  //     'property_address' => 'test',
  //     'city' => 'test',
  //     'postal_code' => 'test',
  //     'name' => 'test',
  //     'source_record_id' => 45,
  //     'source_record' => 'bia1',
  //   ]
  // ];

  // check if record exists by source id and source record name
  $propertyCheck = civicrm_api3('Property', 'get', ['source_record_id' => $params['source_record_id'], 'source_record' => $params['source_record']]);
  /************************* add new property **************************/
  //get address_id by property address
  $propertyAddress = civicrm_api3('Address', 'get', ['street_address' => $propertyCheck['property_address']]);
  $addressId = (!empty($propertyAddress['id'])) ? $propertyAddress['id'] : NULL;

  // check if server id exists
  if (empty($propertyCheck['id'])) {
    //*** 1 *** create a new property
    if (array_key_exists('id', $params)) {
      unset($params['id']);
    }
    $prop = civicrm_api3('Property', 'create', $params);
    //*** 2 *** create a new unit
    civicrm_api3('Unit', 'create', ['address_id' => $addressId, 'property_id' => $prop['id'], 'source_record_id' => $params['source_record_id'], 'source_record' => $params['source_record']]);
  }
  else {
    /************************* update property **************************/
    //*** 1 ***  update existing property
    $params['id'] = $propertyCheck['id'];
    $prop = civicrm_api3('Property', 'create', $params);
    $units = unit::get()->addWhere('property_id', '=', $propertyCheck['id'])->addWhere('source_record_id', '=', $params['source_record_id'])->addWhere('source_record', '=', $params['source_record'])->execute();
    if (!empty($units)) {
      foreach ($units as $unit) {
        //*** 2 *** update existing unit
        civicrm_api3('Unit', 'create', ['id' => $unit['id'], 'address_id' => $addressId, 'property_id' => $propertyCheck['id'], 'source_record_id' => $params['source_record_id'], 'source_record' => $params['source_record']]);
      }
    }
  }
  /************************* delete property **************************/
  // if (!empty($params['delete_property_id'])) {
  //   $missingProperties = civicrm_api3('Property', 'get', ['source_record_id' => $params['delete_property_id'], 'source_record' => $params['source_record']]);
  //   if (!empty($missingProperties)) {
  //     //*** 1 *** delete propertyowner by property_id
  //     $propertyOwners = civicrm_api3('PropertyOwner', 'get', ['property_id' => $params['delete_property_id']]);
  //     if (!empty($propertyOwners['id'])) {
  //       civicrm_api3('PropertyOwner', 'delete', ['property_id' => $params['delete_property_id']]);
  //     }
  //     //*** 2 *** delete unit by property_id
  //     $Units = civicrm_api3('Unit', 'get', ['property_id' => $params['delete_property_id'], 'source_record_id' => $params['source_record_id'], 'source_record' => $params['source_record']]);
  //     if (!empty($Units['id'])) {
  //       civicrm_api3('Unit', 'delete', ['property_id' => $params['delete_property_id'], 'source_record_id' => $params['source_record_id'], 'source_record' => $params['source_record']]);
  //     }
  //   }
  // }
}

/**
 * Create, update and delete contact
 */
function syncContacts($params) {
  // $request = [
  //   "entity" => "Contact",
  //   "params" => [
  //     'id' => $contact['contact_id'],
  //     'source_record_id' => 45,
  //     'source_record' => 'bia1',
  //     'contact_type' => 'Individual',
  //     'contact_sub_type' => 'Obiaa_Staff',
  //     'first_name' => 'test',
  //     'last_name' => 'test',
  //     'organization_name' => 'test1',
  //     'custom_' . $biaContactID => $contact['id'],
  //     'Membership_Status.Voting_Status' => '',
  //     'Membership_Status.Region' => 'South Central',
  //     'Membership_Status.BIA' => 'My Bia bia1',
  //     'street_address' => 'test',
  //     'is_primary' => 1,
  //     'street_number' => 14,
  //     'is_billing' => 0,
  //     'location_type_id' => 1,
  //     'street_name' => 'test',
  //     'street_type' => 'test',
  //     'city' => 'test',
  //     'state_province_id' => 'test',
  //     'postal_code' => 'test',
  //     'country_id' => 'test',
  //     'geo_code_1' => null,
  //     'geo_code_2' => null,
  //     'manual_geo_code' => 0
  //   ]
  // ];

  $contactCheck = civicrm_api3('Contact', 'get', ['id' => $params['id']]);
  /************************* add or update contact **************************/
  //*** 1 *** add a new contact or update existing contact
  if (empty($contactCheck['id'])) {
    if (array_key_exists('id', $params)) {
      unset($params['id']);
    }
  }
  else {
    $params['id'] = $contactCheck['id'];
  }
  civicrm_api3('Contact', 'create', $params);
  /************************* sync activity **************************/
  //*** 2 *** sync activities
  syncActivities($params);
  /************************* add or update address **************************/
  //*** 3 *** add a new address
  $addressCheck = civicrm_api3('Address', 'get', ['contact_id' => $params['id']]);
  if (empty($addressCheck['id'])) {
    if (array_key_exists('id', $params)) {
      unset($params['id']);
    }
  }
  else {
    $params['id'] = $addressCheck['id'];
  }
  civicrm_api3('Address', 'create', $params);
  /************************* add or update unitbusiness **************************/
  //*** 4 *** add a new UnitBusiness
  // get unit id by source_record_id and source_record
  $unitCheck = civicrm_api3('Unit', 'get', ['source_record_id' => $params['source_record_id'], 'source_record' => $params['source_record']]);
  $unitId = (!empty($unitCheck['id'])) ? $unitCheck['id'] : NULL;

  $unitBusinessesCheck = UnitBusiness::get(FALSE)->addWhere('business_id', '=', $params['id'])->execute();
  if (empty($unitBusinessesCheck)) {
    //add new unitbusiness
    civicrm_api3('UnitBusiness', 'create', ['unit_id' => $unitId, 'business_id' => $params['id']]);
  }
  else {
    //update unitbusiness
    foreach ($unitBusinessesCheck as $unitBusiness) {
      civicrm_api3('UnitBusiness', 'create', ['id' => $unitBusiness['id'], 'unit_id' => $unitId, 'business_id' => $params['id']]);
    }
  }
  /************************* add or update property owner **************************/
  //*** 5 *** add a new property owner
  $propertyOwnerCheck = PropertyOwner::get(FALSE)->addWhere('owner_id', '=', $params['id'])->execute();
  $propertyCheck = civicrm_api3('Property', 'get', ['source_record_id' => $params['source_record_id'], 'source_record' => $params['source_record']]);
  $propertyId = (!empty($propertyCheck['id'])) ? $propertyCheck['id'] : NULL;
  if (empty($propertyOwnerCheck)) {
    //add new property owner
    civicrm_api3('PropertyOwner', 'create', ['property_id' => $propertyId, 'owner_id' => $params['id'], 'is_voter' => 1]);
  }
  else {
    //update property owner
    foreach ($propertyOwnerCheck as $propertyOwner) {
      civicrm_api3('PropertyOwner', 'create', ['id' => $propertyOwner['id'], 'property_id' => $propertyId, 'owner_id' => $params['id'], 'is_voter' => 1]);
    }
  }

  /************************* delete missing contact **************************/
}

/**
 * Create, update and delete activity
 * */
function syncActivies($params) {
  // $request = [
  //   "entity" => "Activity",
  //   "params" => [
  //     'id' => $activity['id'],
  //     'target_contact_id' => 0,
  //     'source_contact_id' => 0,
  //   ]
  // ];
  $activityCheck = civicrm_api3('Activity', 'get', ['id' => $params['id']]);
  if (empty($activityCheck['id'])) {
    if (array_key_exists('id', $params)) {
      unset($params['id']);
    }
  }
  else {
    $params['id'] = $activityCheck['id'];
  }

  civicrm_api3('Activity', 'create', ['target_contact_id' => $params['target_contact_id'], 'source_contact_id' => $params['source_contact_id']]);
}
