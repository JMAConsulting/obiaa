<?php
use CRM_Biaimport_ExtensionUtil as E;
use Civi\Api4\Address;
use Civi\Api4\Contact;
use Civi\Api4\Email;
use Civi\Api4\Phone;
use Civi\Api4\UnitBusiness;
use Civi\Api4\Unit;
use Civi\Api4\Relationship;
use Civi\Api4\Website;
use Civi\Api4\OptionValue;
use Civi\Api4\Property;

/**
 * BusinessImport.Create API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_business_import_Create_spec(&$spec) {
  $spec['property_address'] = [
    'title' => E::ts('Tax Roll Address'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $spec['property_tax_roll_unit'] = [
    'title' => E::ts('Tax Roll Unit'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $spec['property_street_address'] = [
    'title' => E::ts('Business Mailing Address'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $spec['property_unit'] = [
    'title' => E::ts('Business Mailing Unit/Suite'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $spec['unit_size'] = [
    'title' => E::ts('Unit Size (Sq Ft)'),
    'type' => CRM_Utils_Type::T_INT,
  ];
  $spec['unit_price'] = [
    'title' => E::ts('Unit Price per Sq Ft'),
    'type' => CRM_Utils_Type::T_MONEY,
  ];
  $spec['unit_status'] = [
    'title' => E::ts('Unit Status'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $spec['unit_location'] = [
    'title' => E::ts('Unit Location (Ground Floor, Floor #)'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $spec['organization_name'] = [
    'title' => E::ts('Business Name'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $spec['first_name'] = [
    'title' => E::ts('Business Contact First Name'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $spec['last_name'] = [
    'title' => E::ts('Business Contact Last Name'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $spec['contact_position'] = [
    'title' => E::ts('Business Contact Position'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $spec['phone'] = [
    'title' => E::ts('Telephone #'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $spec['contact_email'] = [
    'title' => E::ts('Business Contact E-mail'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $spec['website'] = [
    'title' => E::ts('Website URL'),
    'type' => CRM_Utils_Type::T_URL,
  ];
  $spec['email'] = [
    'title' => E::ts('Business Email'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $socialMedia = [
    'linkedin' => E::ts('LinkedIn'),
    'facebook' => E::ts('Facebook'),
    'instagram' => E::ts('Instagram'),
    'twitter' => E::ts('Twitter'),
    'ticktok' => E::ts('TickTok'),
  ];
  foreach ($socialMedia as $sm => $smTitle) {
    $spec[$sm . '_url'] = [
      'title' => E::ts('%1 URL', [1 => $smTitle]),
      'type' => CRM_Utils_Type::T_STRING,
    ];
  }
  $spec['category'] = [
    'title' => E::ts('Business Category'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $spec['sub_category'] = [
    'title' => E::ts('Business Subcategory'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $spec['local_bia'] = [
    'title' => E::ts('Local BIA Heading'),
    'type' => CRM_Utils_Type::T_STRING,
  ];
  $demographicFields = [
    'francophone' => E::ts('Francophone'),
    'women' => E::ts('Women'),
    'youth_39_under' => E::ts('Youth 39&Under'),
    'lgbtiq' => E::ts('LGBTQ+'),
    'indigenous' => E::ts('Indigenous (First Nations, Inuit or Metis)'),
    'racialized' => E::ts('Racialized group memebr'),
    'newcomers' => E::ts('Newcomers, immigrants and refugees'),
    'black' => E::ts('Black community member'),
    'disabilities' => E::ts('Persons with disabilities'),
  ];
  foreach ($demographicFields as $key => $label) {
    $spec[$key] = [
      'title' => $label,
      'type' => CRM_Utils_Type::T_BOOLEAN,
    ];
  }
}

/**
 * BusinessImport.Create API
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
function civicrm_api3_business_import_Create($params) {
  // Build a mapping of fields to the option groups they match to.
  $optionGroups = [
    'unit_status' => 'unit_status',
    'category' => 'Business_Category_Parent_Class',
    'sub_category' => 'Business_Category_Child_Class',
    'local_bia' => 'Business_Category_Child_Class_Unique',
  ];
  // Build a mapping of fields to their custom fields.
  $customGroup = [
    'category' => 'Business_Category.Parent_Class',
    'sub_category' => 'Business_Category.Child_Class',
    'local_bia' => 'Business_Category.Child_Class_Unique',
    'linkedin_url' => 'Social_Media.LinkedIn',
    'facebook_url' => 'Social_Media.Facebook',
    'instagram_url' => 'Social_Media.Instagram',
    'twitter_url' => 'Social_Media.Twitter',
    'ticktok_url' => 'Social_Media.TikTok',
    'francophone' => 'Ownership_Demographics.Francophone',
    'women' => 'Ownership_Demographics.Women',
    'youth_39_under' => 'Ownership_Demographics.Youth_39_and_under_',
    'lgbtiq' => 'Ownership_Demographics.Lesbian_gay_bisexual_transsexual_queer_LGBTQ_',
    'indigenous' => 'Ownership_Demographics.Indigenous_First_Nations_Inuit_or_Metis_',
    'racialized' => 'Ownership_Demographics.Racialized_group_member',
    'newcomers' => 'Ownership_Demographics.Newcomers_immigrants_and_refugees',
    'black' => 'Ownership_Demographics.Black_community_member',
    'disabilities'  => 'Ownership_Demographics.People_with_disabilities',
  ];
  if (empty($params['property_address']) && empty($params['property_street_address'])) {
    throw new \CRM_Core_Exception('Need either a MPAC property address or a Address of the Business');
  }
  elseif (empty($params['property_address']) && !empty($params['property_street_address'])) {
    $property = Property::get(FALSE)->addWhere('property_address', '=', $params['property_street_address'])->execute();
    if (count($property) < 1) {
      $property = Property::create(FALSE)
        ->addValue('property_address', $params['property_street_address'])
	->addValue('non_mpac_property', 1)
        ->addValue('created_id', (int) CRM_Core_Session::getLoggedInContactID())
        ->addValue('modified_id', (int) CRM_Core_Session::getLoggedInContactID())
        ->execute()
        ->first();
    }
    else {
      $property = $property->first();
    }
  }
  else {
    // Go looking for a property first.
    $property = Property::get(FALSE)->addWhere('property_address', '=', $params['property_address'])->execute()->first();
    if (empty($property)) {
      throw new \CRM_Core_Exception('Property must be created first');
    }
  }
  // If no unit status is provided set it to be unavailable for rent.
  $params['unit_status'] = !empty($params['unit_status']) ? $params['unit_status'] : 'Vacant (unavailable for rent and/or derelict)';
  $unitStatus = OptionValue::get(FALSE)->addWhere('option_group_id:name', '=', $optionGroups['unit_status'])->addWhere('label', '=', $params['unit_status'])->execute()->first()['value'];
  if (empty($params['property_street_address']) && (empty($params['property_unit']) || $params['property_unit'] === 'Ghost') && !(!empty($params['organization_name']) || !empty($params['first_name']))) {
    $unitAddress = Address::get(FALSE)->addWhere('street_address', '=', $params['property_address'])->addWhere('street_unit', 'IS NULL', '')->execute();
    if (count($unitAddress) == 0) {
      $unitAddress = Address::create(FALSE)
        ->addValue('street_address', $params['property_address'])
        ->addValue('unit', $params['property_unit'])
        ->addValue('city', $property['city'] ?? '')
        ->addValue('postal_code', $property['postal_code'] ?? '')
        ->addValue('state_province_id:label', 'Ontario')
        ->addValue('country_id:label', 'Canada')
        ->addValue('is_primary', 1)
        ->execute();
    }
    $unitCheck = Unit::get(FALSE)->addWhere('property_id', '=', $property['id'])->addWhere('address_id', '=', $unitAddress[0]['id'])->execute();
    if (count($unitCheck) == 0) {
      $unit = Unit::create(FALSE)
        ->addValue('property_id', $property['id'])
        ->addValue('address_id', $unitAddress[0]['id'])
        ->addValue('unit_status', $unitStatus)
        ->execute();
    }
    return;
  }
  // If for instance we have no organization name, first name etc just move onto the next record.
  $unitStreetAddress = empty($params['property_street_address']) ? $params['property_address'] : $params['property_street_address'];
  if (empty($params['organization_name']) && empty($params['first_name']) && empty($params['last_name'])) {
    $unitOp = empty($params['property_unit']) ? 'IS NULL' : '=';
    $unitValue = empty($params['property_unit']) ? '' : $params['property_unit'];
    // Now check for duplicate units
    $units = Unit::get(FALSE)
      ->addJoin('UnitBusiness AS unit_business', 'INNER', ['unit_business.unit_id', '=', 'id'])
      ->addJoin('Address AS address', 'INNER', ['address.id', '=', 'address_id'])
      ->addWhere('address.street_unit', $unitOp, $unitValue)
      ->addWhere('address.street_address', '=', $unitStreetAddress)
      ->addWhere('property_id', '=', $property['id'])
      ->addWhere('unit_business.business_id', 'IS NOT NULL', '')
      ->execute();
    // let us check if the unit is already occupied in the database.
    if (count($units) == 0) {
      $addressCheck = Address::get(FALSE)->addWhere('street_address', '=', $unitStreetAddress)->addWhere('street_unit', $unitOp, $unitValue)->execute();
      if (count($addressCheck) == 0) {
        $addressCheck = Address::create(FALSE)
          ->addValue('street_address', $unitStreetAddress)
          ->addValue('street_unit', (empty($params['property_unit']) ? NULL : $params['property_unit']))
          ->addValue('city', $property['city'])
          ->addValue('postal_code', $property['postal_code'])
          ->execute();
      }
      Unit::create(FALSE)
        ->addValue('address_id', $addressCheck['id'])
        ->addValue('unit_size', $params['unit_size'])
        ->addValue('unit_price', $params['unit_price'])
        ->addValue('unit_status', $unitStatus)
        ->addValue('unit_location', $params['unit_location'])
        ->addValue('property_id', $property['id'])
        ->execute();
    }
    return;
  }
  // Try and Find matching business (member) record.
  $organizationName = !empty($params['organization_name']) ? $params['organization_name'] : $params['first_name'] . '  ' . $params['last_name'];
  $organizationNameFieldDefinition = Contact::getFields(FALSE)->addWhere('name', '=', 'organization_name')->execute()->first();
  /*if (mb_strlen($organizationName) > $organizationNameFieldDefinition['input_attrs']['maxlength']) {
    $organizationName = CRM_Utils_String::ellipsify($organizationName, $organizationNameFieldDefinition['input_attrs']['maxlength']);
  }*/
  $phoneOnBiz = empty($params['organization_name']);
  $dedupeParams = [
    'contact_sub_type' => 'Members_Businesses_',
    'organization_name' => $organizationName,
  ];
  $duplicates = CRM_Contact_BAO_Contact::getDuplicateContacts($dedupeParams, 'Organization');
  // if we have not found a matching business create.
  $unitOp = empty($params['property_unit']) ? 'IS NULL' : '=';
  $unitValue = empty($params['property_unit']) ? '' : $params['property_unit'];
  if (count($duplicates) == 0) {
    $contact = Contact::create(FALSE)->addValue('contact_type', 'Organization')->addValue('organization_name', $organizationName)->addValue('contact_sub_type', ['Members_Businesses_'])->execute()->first();
  }
  elseif (count($duplicates) >= 1) {
    // Ok we have found one or more business let us try check the unit record for a business.
    $unitCheck = Unit::get(FALSE)
      ->addSelect('*')->addSelect('unit_business.*')
      ->addJoin('UnitBusiness AS unit_business', 'INNER', ['unit_business.unit_id', '=', 'id'])
      ->addJoin('Address AS address', 'INNER', ['address.id', '=', 'address_id'])
      ->addWhere('address.street_unit', $unitOp, $unitValue)
      ->addWhere('address.street_address', '=', $unitStreetAddress)
      ->addWhere('property_id', '=', $property['id'])
      ->addWhere('unit_business.business_id', 'IN', $duplicates)
      ->execute();
    if (count($unitCheck) > 0) {
      // ok we found a matching unit let us use it.
      $contact = Contact::get(FALSE)->addWhere('id', '=', $unitCheck[0]['unit_business.business_id'])->execute()->first();
    }
    elseif (count($duplicates) == 1) {
      $contact =  Contact::get(FALSE)->addWhere('id', '=', $duplicates[0])->execute()->first();
    }
  }
  // Ok now let us see if we already have a unit record in the system if we have gotten here then the unit won't have a business so it will be vacant at this point.
  $unit = Unit::get(FALSE)
    ->addSelect('*')->addSelect('unit_business.*')
    ->addJoin('UnitBusiness AS unit_business', 'INNER', ['unit_business.unit_id', '=', 'id'])
    ->addJoin('Address AS address', 'INNER', ['address.id', '=', 'address_id'])
    ->addWhere('address.street_address', '=', $unitStreetAddress)
    ->addWhere('address.street_unit', $unitOp, $unitValue)
    ->addWhere('property_id', '=', $property['id'])
    ->execute()->first();
  if (empty($unit)) {
    // Ok no unit record found lt us create it.
    $unitAddress = Address::create(FALSE)
      ->addValue('street_address', $unitStreetAddress)
      ->addValue('street_unit', (empty($params['property_unit']) ? NULL : $params['property_unit']))
      ->addValue('city', $property['city'])
      ->addValue('postal_code', $property['postal_code'])
      ->execute()
      ->first();
    $unit = Unit::create(FALSE)
      ->addValue('address_id', $unitAddress['id'])
      ->addValue('unit_size', $params['unit_size'])
      ->addValue('unit_price', $params['unit_price'])
      ->addValue('unit_status', $unitStatus)
      ->addValue('unit_location', $params['unit_location'])
      ->addValue('property_id', $property['id'])
      ->execute()
      ->first();
    UnitBusiness::create(FALSE)->addValue('unit_id', $unit['id'])->addValue('business_id', $contact['id'])->execute();
  }
  else {
    // ok we found one let us update it with the import data and update the unit business to link the unit to the business.
    Unit::update(FALSE)
      ->addValue('unit_size', $params['unit_size'])
      ->addValue('unit_price', $params['unit_price'])
      ->addValue('unit_status', $unitStatus)
      ->addValue('unit_location', $params['unit_location'])
      ->addWhere('id', '=', $unit['id'])
      ->execute();
    UnitBusiness::create(FALSE)
      ->addValue('business_id', $contact['id'])
      ->addValue('unit_id', $unit['id'])
      ->execute();
  }
  // Now Look for Business Contact / create business contact.
  if (!empty($params['first_name']) || !empty($params['last_name']) || !empty($params['contact_email'])) {
    $contactDedupeParams = [];
    $individualDedupeFields = [
      'first_name' => 'first_name',
      'last_name' => 'last_name',
      'contact_email' => 'contact_email',
    ];
    foreach ($individualDedupeFields  as $apiField => $dedupeField) {
      if (!empty($params[$apiField])) {
        $contactDedupeParams[$dedupeField] = $params[$apiField];
      }
    }
    $contactDuplicates = CRM_Contact_BAO_Contact::getDuplicateContacts($contactDedupeParams, 'Individual');
    if (count($contactDuplicates) > 0) {
      if (count($contactDuplicates) > 1) {
        $businessContactId = 0;
        $possibleContacts = Contact::get(FALSE)->addWhere('id', 'IN', $contactDuplicates)->execute();
        foreach ($possibleContacts as $possibleContact) {
          // check to see if any of them have the business as a current employer.
          if (!empty($possibleContact['employer_id']) && $possibleContact['employer_id'] === $contact['id']) {
            $businessContactId = $possibleContact['id'];
          }
        }
        if ($businessContactId === 0) {
          $possiblePhones = Phone::get(FALSE)->addWhere('phone', '=', $params['phone'])->addWhere('contact_id', 'IN', $contactDuplicates)->execute();
          if (count($possiblePhones) == 1) {
            $businessContactId = $possiblePhones[0]['contact_id'];
          }
          elseif (count($possiblePhones) > 1) {
            // Do something here not sure.
          }
          if (!empty($businessContactId)) {
            $businessContactId = $contactDuplicates[0];
          }
        }
      }
      else {
        $businessContactId = $contactDuplicates[0];
      }
      $businessContact = Contact::get(FALSE)->addWhere('id', '=', $businessContactId)->execute()->first();
      // Ensure that the individual (business contact) is linked to the employer.
      if (empty($businessContact['employer_id']) || $businessContact['employer_id'] !== $contact['id']) {
        Contact::update(FALSE)->addValue('employer_id', $contact['id'])->addWhere('id', '=', $businessContactId)->execute();
      }
    }
    else {
      $businessContactId = Contact::create(FALSE)
        ->addValue('first_name', $params['first_name'])
        ->addValue('last_name', $params['last_name'])
        ->addValue('contact_type', 'Individual')
        ->addValue('employer_id', $contact['id'])
        ->execute()->first()['id'];
    }
    // Contact Details for the Business contact
    if (!empty($params['phone'])) {
      Phone::create(FALSE)->addValue('phone', $params['phone'])->addValue('phone_type_id:label', 'Phone')->addValue('location_type_id:label', 'Work')->addValue('contact_id', $businessContactId)->execute();
    }
    if (!empty($params['contact_email'])) {
      Email::create(FALSE)->addValue('email', $params['contact_email'])->addValue('location_type_id:label', 'Work')->addValue('contact_id', $businessContactId)->execute();
    }
    $relationship = Relationship::get(FALSE)->addWhere('contact_id_a', '=', $businessContactId)->addWhere('contact_id_b', '=', $contact['id'])->execute()->first();
    // set the Position on the employer/employee relatonship.
    Relationship::update(FALSE)->addValue('Business_Contact.Business_Contact_Position', $params['contact_position'])->addWhere('id', '=', $relationship['id'])->execute();
  }
  // If we have created the business using first name and last name put the phone on the business as well.
  if ($phoneOnBiz) {
    Phone::create(FALSE)->addValue('phone', $params['phone'])->addValue('phone_type_id:label', 'Phone')->addValue('location_type_id:label', 'Work')->addValue('contact_id', $contact['id'])->execute();
  }
  // Store the Business email and website on the business record.
  if (!empty($params['email'])) {
    Email::create(FALSE)->addValue('email', $params['email'])->addValue('location_type_id:label', 'Work')->addValue('contact_id', $contact['id'])->execute();
  }
  if (!empty($params['website'])) {
    Website::create(FALSE)->addValue('url', $params['website'])->addValue('website_type_id:label', 'Work')->addValue('contact_id', $contact['id'])->execute();
  }
  // Now loop through all the fields that match to a custom field to create APIv4 Params.
  $orgValues = [];
  foreach ($customGroup as $field => $customField) {
    // If the field is linked to an option group check to see if there is a value for it in the database and if not only create it if the field is the local_bia field.
    if (!empty($optionGroups[$field]) && !empty($params[$field])) {
      $optionValue = OptionValue::get(FALSE)->addWhere('label', '=', $params[$field])->addWhere('option_group_id:name', '=', $optionGroups[$field])->execute()->first();
      if ($field !== 'local_bia' && empty($optionValue)) {
        throw new \CRM_Core_Exception(E::ts('value %1 supplied for field %2 does not exist in the database', [1 => $params[$field], 2 => $field]));
      }
      elseif (empty($optionValue)) {
        $optionValue = OptionValue::create(FALSE)->addValue('label', $params[$field])->addValue('option_group_id:name', $optionGroups[$field])->addValue('value', $params[$field])->execute()->first();
      }
      $orgValues[$customField . ':label'] = [$params[$field]];
    }
    elseif (isset($params[$field])) {
      $orgValues[$customField] = $params[$field];
    }
  }
  // Set the custom fields on the business contact.
  Contact::update(FALSE)->setValues($orgValues)->addWhere('id', '=', $contact['id'])->execute();
  return civicrm_api3_create_success([], $params, 'BusinessImport', 'Create');
}
