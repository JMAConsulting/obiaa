<?php
use CRM_Biaimport_ExtensionUtil as E;
use Civi\Api4\Address;
use Civi\Api4\Contact;
use Civi\Api4\Email;
use Civi\Api4\Relationship;
use Civi\Api4\Phone;
use Civi\Api4\Property;
use Civi\Api4\PropertyOwner;

/**
 * PropertyOwner %1.Create API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_property_owner_import_create_spec(&$spec) {
  $spec['roll_no'] = [
    'title' => E::ts('Assessment Roll Number'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $spec['property_name'] = [
    'title' => E::ts('Property Name'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $spec['property_address'] = [
    'title' => E::ts('Property Address'),
    'type' => CRM_Utils_Type::T_STRING,
    'api.required' => 1,
  ];
  $spec['city'] = [
    'title' => E::ts('City'),
    'type' => CRM_Utils_Type::T_STRING,
    'api.required' => 1,
  ];
  $spec['postal_code'] = [
    'title' => E::ts('Postal Code'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $owners = [1, 2, 3, 4];
  foreach ($owners as $owner) {
    $spec['owner_' . $owner . '_first_name'] = [
      'title' => E::ts('Individual Owner %1 First Name', [1 => $owner]),
      'type' => CRM_Utils_Type::T_STRING,
    ];
    $spec['owner_' . $owner . '_last_name'] = [
      'title' => E::ts('Individual Owner %1 Last Name', [1 => $owner]),
      'type' => CRM_Utils_Type::T_STRING,
    ];
    $spec['owner_' . $owner . '_mobile_phone'] = [
      'title' => E::ts('Individual Owner %1 Mobile Phone', [1 => $owner]),
      'type' => CRM_Utils_Type::T_STRING,
    ];
    $spec['owner_' . $owner . '_company_name'] = [
      'title' => E::ts('Owner %1 Company Name', [1 => $owner]),
      'type' => CRM_Utils_Type::T_STRING,
    ];
    $spec['owner_' . $owner . '_email'] = [
      'title' => E::ts('Owner %1 Business Email', [1 => $owner]),
      'type' => CRM_Utils_Type::T_STRING,
    ];
    $spec['owner_' . $owner . '_phone'] = [
      'title' => E::ts('Owner %1 Business Phone', [1 => $owner]),
      'type' => CRM_Utils_Type::T_STRING,
    ];
    $spec['owner_' . $owner . '_street_address'] = [
      'title' => E::ts('Owner %1 Street Address', [1 => $owner]),
      'type' => CRM_Utils_Type::T_STRING,
    ];
    $spec['owner_' . $owner . '_unit'] = [
      'title' => E::ts('Owner %1 Unit', [1 => $owner]),
      'type' => CRM_Utils_Type::T_STRING,
    ];
    $spec['owner_' . $owner . '_supplemental_address_1'] = [
      'title' => E::ts('Owner %1 Supplemental Address', [1 => $owner]),
      'type' => CRM_Utils_Type::T_STRING,
    ];
    $spec['owner_' . $owner . '_city'] = [
      'title' => E::ts('Owner %1 City', [1 => $owner]),
      'type' => CRM_Utils_Type::T_STRING,
    ];
    $spec['owner_' . $owner . '_province'] = [
      'title' => E::ts('Owner %1 province', [1 => $owner]),
      'type' => CRM_Utils_Type::T_STRING,
    ];
    $spec['owner_' . $owner . '_postal_code'] = [
      'title' => E::ts('Owner %1 Postal Code', [1 => $owner]),
      'type' => CRM_Utils_Type::T_STRING,
    ];
    $spec['owner_' . $owner . '_country'] = [
      'title' => E::ts('Owner %1 Country', [1 => $owner]),
      'type' => CRM_Utils_Type::T_STRING,
    ];
  }
  $spec['property_manager_first_name'] = [
    'title' => E::ts('Property Manager first name'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $spec['property_manager_last_name'] = [
    'title' => E::ts('Property Manager last name'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $spec['property_manager_email'] = [
    'title' => E::ts('Property Manger email'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $spec['property_manager_phone'] = [
    'title' => E::ts('Property Manager Phone'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
}

/**
 * PropertyOwner %1.Create API
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
function civicrm_api3_property_owner_import_create($params) {
  $votingContactId = 0;
  // Try and find a property based on the property address otherwise create it
  $property = Property::get(FALSE)
    ->addWhere('property_address', '=', $params['property_address'])
    ->execute()
    ->first();
  if (empty($property)) {
    $property = Property::create(FALSE)
      ->addValue('roll_no', $params['roll_no'])
      ->addValue('property_address', $params['property_address'])
      ->addValue('name', $params['property_name'])
      ->addValue('city', $params['city'])
      ->addValue('postal_code', $params['postal_code'])
      ->addValue('created_id', (int) CRM_Core_Session::getLoggedInContactID())
      ->addValue('modified_id', (int) CRM_Core_Session::getLoggedInContactID())
      ->execute()
      ->first();
  }
  else {
    throw new CRM_Core_Exception('Cannot import property as it already exists in the database');
  }
  $contactFields = ['first_name', 'last_name', 'company_name'];
  $owners = [1, 2, 3, 4];
  foreach ($owners as $owner) {
    if (empty($params['owner_' . $owner . '_company_name']) && empty($params['owner_' . $owner . '_first_name']) && empty($params['owner_' . $owner . '_last_name'])) {
      continue;
    }
    // lets see if the owner is in the system or not already.
    $contactParams = [];
    $dedupeParams = [
      'contact_sub_type' => 'Members_Property_Owners_',
    ];
    foreach ($contactFields as $contactField) {
      if ($contactField == 'company_name') {
        $dedupeParams['organization_name'] = !empty($params['owner_' . $owner . '_' . $contactField]) ? $params['owner_' . $owner . '_' . $contactField] : $params['owner_' . $owner . '_first_name'] . ' ' . $params['owner_' . $owner . '_last_name'];
        $contactParams['organization_name'] = $dedupeParams['organization_name'];
      }
    }
    $contactIds = CRM_Contact_BAO_Contact::getDuplicateContacts($dedupeParams, 'Organization');
    if (count($contactIds) == 1) {
      $contactParams['id'] = $contactIds[0];
    }
    else {
      $newContact = Contact::create(FALSE)
        ->addValue('contact_type', 'Organization')
        ->addValue('contact_sub_type:name', 'Members_Property_Owners_')
        ->addValue('organization_name', $contactParams['organization_name'])
        ->execute();
      $contactParams['id'] = $newContact[0]['id'];
    }
    if ($owner === 1) {
      $votingContactId = $contactParams['id'];
    }
    if (!empty($params['owner_' . $owner . '_company_name']) && !empty($params['owner_' . $owner . '_first_name']) && !empty($params['owner_' . $owner . '_last_name'])) {
      $individualContactDedupeParams = [
        'first_name' => $params['owner_' . $owner . '_first_name'],
        'last_name' => $params['owner_' . $owner . '_last_name'],
      ];
      $individualOwnerIds = CRM_Contact_BAO_Contact::getDuplicateContacts($individualContactDedupeParams, 'Individual');
      $individualOwnerContactId = 0;
      $individualPhonePresent = FALSE;
      if (count($individualOwnerIds) > 1 && !empty($params['owner_' . $owner . '_mobile_phone'])) {
        $phones = Phone::get(FALSE)
          ->addSelect('contact_id')
          ->addWhere('contact_id', 'IN', $individualOwnerIds)
          ->addWhere('phone', '=', $params['owner_' . $owner . '_mobile_phone'])
          ->addGroupBy('contact_id')
          ->execute();
        if (count($phones) == 1) {
          $individualOwnerContactId = $phones[0]['contact_id'];
          $individualPhonePresent = TRUE;
        }
        elseif (count($phones) > 1) {
          $contactIds = $phones->column('contact_id');
          $contactChecks = Contact::get(FALSE)
            ->addWhere('id', 'IN', $contactIds)
            ->addWhere('employer_id', '=', $contactParams['id'])
            ->execute();
          if (count($contactChecks) >= 1) {
            $individualOwnerContactId = $contactChecks[0]['id'];
            $individualPhonePresent = TRUE;
          }
        }
      }
      elseif (count($individualOwnerIds) == 0 || empty($individualOwnerContactId)) {
        $individualOwnerContactId = Contact::create(FALSE)
          ->addValue('contact_type', 'Individual')
          ->addValue('first_name', $individualContactDedupeParams['first_name'])
          ->addValue('last_name', $individualContactDedupeParams['last_name'])
          ->addValue('employer_id', $contactParams['id'])
          ->execute()->first()['id'];
      }
      if (!$individualPhonePresent && !empty($params['owner_' . $owner . '_mobile_phone'])) {
        Phone::create(FALSE)
         ->addValue('phone', $params['owner_' . $owner . '_mobile_phone'])
         ->addValue('phone_type_id:label', 'Mobile')
         ->addValue('locaton_Type_id:label', 'Work')
         ->addValue('contact_id', $individualOwnerContactId)
         ->execute();
      }
    }
    // Now that we have a property and an owner created now create the Property Owner record.
    $propertyOwnerRecord = PropertyOwner::get(FALSE)->addWhere('property_id', '=', $property['id'])->addWhere('owner_id', '=', $contactParams['id'])->execute();
    if (count($propertyOwnerRecord) == 0) {
      PropertyOwner::create(FALSE)
        ->addValue('property_id', $property['id'])
        ->addValue('owner_id', $contactParams['id'])
        ->addValue('is_voter', ($owner === 1 ? 1 : 0))
        ->execute();
    }
    // Now go to create the address if applicable
    $currentPrimaryAddress = Address::get(FALSE)->addWhere('is_primary', '=', 1)->addWhere('contact_id', '=', $contactParams['id'])->execute();
    $currentAddress = Address::get(FALSE)
      ->addWhere('street_address', '=', $params['owner_' . $owner . '_street_address'])
      ->addWhere('supplemental_address_1', '=', $params['owner_' . $owner . '_supplemental_address_1'])
      ->addWhere('city', '=', $params['owner_' . $owner . '_city'])
      ->addWhere('postal_code', '=', $params['owner_' . $owner . '_postal_code'])
      ->addWhere('country_id:label', '=', $params['owner_' . $owner . '_country'])
      ->addWhere('state_province_id:label', '=', $params['owner_' . $owner . '_province'])
      ->addWhere('contact_id', '=', $contactParams['id'])
      ->execute();
    if (count($currentAddress) == 0) {
      Address::create(FALSE)
        ->addValue('street_address', $params['owner_' . $owner . '_street_address'])
        ->addValue('supplemental_address_1', $params['owner_' . $owner . '_supplemental_address_1'])
        ->addValue('city', $params['owner_' . $owner . '_city'])
        ->addValue('postal_code', $params['owner_' . $owner . '_postal_code'])
        ->addValue('country_id:label', $params['owner_' . $owner . '_country'])
        ->addValue('state_province_id:label', $params['owner_' . $owner . '_province'])
        ->addValue('contact_id', $contactParams['id'])
        ->addValue('is_primray', (count($currentPrimaryAddress) == 0 ? 1 : 0))
        ->execute();
    }
    if (!empty($params['owner_' . $owner . '_email'])) {
      // now create the email address against the property record
      $currentEmails = Email::get(FALSE)->addWhere('contact_id', '=', $contactParams['id'])->addWhere('email', '=', $params['owner_' . $owner . '_email'])->execute();
      $currentPrimaryEmail = Email::get(FALSE)->addWhere('contact_id', '=', $contactParams['id'])->addWhere('is_primary', '=', 1)->execute();
      if (count($currentEmails) == 0) {
        Email::create(FALSE)
          ->addValue('email', $params['owner_' . $owner . '_email'])
          ->addValue('is_primary', (count($currentPrimaryEmail) == 0 ? 1 : 0))
          ->addValue('contact_id', $contactParams['id'])
          ->execute();
      }
    }
    if (!empty($params['owner_' . $owner . '_phone'])) {
      // now create the email address against the property record
      $currentPhones = Phone::get(FALSE)->addWhere('contact_id', '=', $contactParams['id'])->addWhere('email', '=', $params['owner_' . $owner . '_phone'])->execute();
      $currentPrimaryPhone = Phone::get(FALSE)->addWhere('contact_id', '=', $contactParams['id'])->addWhere('is_primary', '=', 1)->execute();
      if (count($currentPhones) == 0) {
        Phone::create(FALSE)
          ->addValue('phone', $params['owner_' . $owner . '_phone'])
          ->addValue('is_primary', (count($currentPrimaryEmail) == 0 ? 1 : 0))
          ->addValue('contact_id', $contactParams['id'])
          ->execute();
      }
    }
  }
  if (!empty($params['property_manager_first_name'])) {
    $propertyManagerDedupeParams = [
      'first_name' => $params['property_manager_first_name'],
      'last_name' => $params['property_manager_last_name'],
      'email' => $params['property_manager_email'],
    ];
    $propertyManagerContactIds = CRM_Contact_BAO_Contact::getDuplicateContacts($propertyManagerDedupeParams, 'Individual');
    $relationshipFound = FALSE;
    if (count($propertyManagerContactIds) == 1) {
      $propertyManagerContactId = $propertyManagerContactIds[0];
      $relatonshipCheck = Relationship::get(FALSE)
        ->addWhere('contact_id_a', '=', $propertyManagerContactId)
        ->addWhere('contact_id_b', '=', $contactParams['id'])
        ->addWhere('relationship_type_id:name', '=', 'Property_Manager_for')
        ->execute();
      if (count($relatonshipCheck) == 1) {
        $relationshipFound = TRUE;
      }
    }
    else {
      if (count($propertyManagerContactIds) > 1) {
        $foundMatch = FALSE;
        foreach ($propertyManagerContactIds as $potentialPropertyManagerContactId) {
          if (!$foundMatch) {
            $relatonshipCheck = Relationship::get(FALSE)
              ->addWhere('contact_id_a', '=', $potentialPropertyManagerContactId)
              ->addWhere('contact_id_b', '=', $contactParams['id'])
              ->addWhere('relationship_type_id:name', '=', 'Property_Manager_for')
              ->execute();
            if (count($relatonshipCheck) == 1) {
              $relationshipFound = TRUE;
              $foundMatch = TRUE;
              $propertyManagerContactId = $potentialPropertyManagerContactId;
            }
          }
        }
        if (!$foundMatch) {
          $propertyManagerContactId = Contact::create(FALSE)
            ->addValue('contact_type', 'Individual')
            ->addValue('first_name', $params['property_manager_first_name'])
            ->addValue('last_name', $params['property_manager_last_name'])
            ->execute()->first()['id'];
        }
      }
      else {
        $propertyManagerContactId = Contact::create(FALSE)
          ->addValue('contact_type', 'Individual')
          ->addValue('first_name', $params['property_manager_first_name'])
          ->addValue('last_name', $params['property_manager_last_name'])
          ->execute()->first()['id'];
      }
    }
    if (!empty($params['property_manager_email'])) {
      Email::create(FALSE)
        ->addValue('contact_id', $propertyManagerContactId)
        ->addValue('email', $params['property_manager_email'])
        ->addValue('location_type_id:label', 'Work')
        ->addValue('is_primary', TRUE)
        ->execute();
    }
    if (!empty($params['property_manager_phone'])) {
      Phone::create(FALSE)
        ->addValue('contact_id', $propertyManagerContactId)
        ->addValue('phone', $params['property_manager_phone'])
        ->addValue('is_primary', TRUE)
        ->addValue('location_type_id:label', 'Work')
        ->addValue('phone_type_id:label', 'Phone')
        ->execute();
    }
    if (!$relationshipFound) {
      Relationship::create(FALSE)
        ->addValue('contact_id_a', $propertyManagerContactId)
        ->addValue('contact_id_b', $votingContactId)
        ->addValue('relationship_type_id:name', 'Property_Manager_for')
        ->addValue('is_active', TRUE)
        ->execute();
    }
  }
  // Spec: civicrm_api3_create_success($values = 1, $params = [], $entity = NULL, $action = NULL)
  return civicrm_api3_create_success([], $params, 'PropertyOwnerImport', 'Create');
}
