<?php

//TODO: match existing business/properties when provided an id

/**
 * associative array of acf field => civi field
 */
$fields = [
  'organization_name' => 'organization_name',
  'email' => 'email_primary.email',
  'phone' => 'phone_primary.phone',
  'website' => 'website.url',
  'category' => 'Business_Category.Parent_Class',
  'sub_category' => 'Business_Category.Child_Class',
  'local_bia' => 'Business_Category.Child_Class_Unique',
  'date_of_opening' => 'Business_Details.Open_Date',
  'number_of_employees' => 'Business_Details.Full_Time_Employees_at_this_location',
  'linkedin_url' => 'Social_Media.LinkedIn',
  'facebook_url' => 'Social_Media.Facebook',
  'instagram_url' => 'Social_Media.Instagram',
  'twitter_url' => 'Social_Media.Twitter',
  'ticktok_url' => 'Social_Media.TikTok',
  'google_maps_link' => 'Social_Media.Google_Business_Profile',
];

$demographicFields = [
  'francophone' => 'Ownership_Demographics.Francophone',
  'women' => 'Ownership_Demographics.Women',
  'youth' => 'Ownership_Demographics.Youth_39_and_under_',
  'lgbtiq' => 'Ownership_Demographics.Lesbian_gay_bisexual_transsexual_queer_LGBTQ_',
  'indigenous' => 'Ownership_Demographics.Indigenous_First_Nations_Inuit_or_Metis_',
  'racialized' => 'Ownership_Demographics.Racialized_group_member',
  'newcomers' => 'Ownership_Demographics.Newcomers_immigrants_and_refugees',
  'black' => 'Ownership_Demographics.Black_community_member',
  'disabilities'  => 'Ownership_Demographics.People_with_disabilities',
];

foreach ($fields as $acfField => $civiField) {
  add_filter("acf/load_field/name=$acfField", function ($field) use ($civiField) {
    if (getBusinessDetails() !== null) {
      $field['default_value'] = getBusinessDetails()[$civiField];
    }
    return $field;
  });
}

$fieldOptions = [
  true => 1, // Yes
  false => 0, // No
  null => 2, // Unknown
];

foreach ($demographicFields as $acfField => $civiField) {
  add_filter("acf/load_field/name=$acfField", function ($field) use ($civiField, $fieldOptions) {
    if (getBusinessDetails() !== null) {
      $field['default_value'] = $fieldOptions[getBusinessDetails()[$civiField]];
    }
    return $field;
  });
}

add_filter('acf/load_field/name=opt_out_of_public_listings', 'set_default_opt_out');
function set_default_opt_out($field) {
  if (getBusinessDetails() !== null) {
    $isOptOut = getBusinessDetails()['Business_Category.Opt_out_of_Public_Listing_'];
    $field['default_value'] = $isOptOut ? 'Yes' : null;
  }
  return $field;
}

$contactFields = [
  'first_name' => 'first_name',
  'last_name' => 'last_name',
  'contact_email' => 'email_primary.email',
  'contact_phone' => 'phone_primary.phone',
  'contact_position' => 'job_title',
];

foreach ($contactFields as $acfField => $civiField) {
  add_filter("acf/load_field/name=$acfField", function ($field) use ($civiField) {
    if (getContactDetails() !== null) {
      $field['default_value'] = getContactDetails()[$civiField];
    }
    return $field;
  });
}



add_filter('acf/load_value/name=property_&_unit_details', function ($value, $postId, $field) {
  $units = \Civi\Api4\Unit::get(FALSE)
    ->addSelect('*', 'address.street_address')
    ->addJoin('UnitBusiness AS unit_business', 'LEFT', ['id', '=', 'unit_business.unit_id'])
    ->addJoin('Address AS address', 'LEFT', ['address_id', '=', 'address.id'])
    ->addWhere('unit_business.business_id', '=', $_GET['bid'])
    ->execute();

  // Mapping of property id => index of unit in $units
  $propertyMap = [];

  foreach ($units as $i => $unit) {
    $propertyMap[$unit['property_id']][] = $i;
  }
  $value = array();
  foreach ($propertyMap as $propertyId => $unitIndices) {
    // $property = \Civi\Api4\Property::get(FALSE)
    //   ->addWhere('id', '=', $propertyId)
    //   ->execute()[0];
    $newValue = [
      // Tax roll address
      'field_66967535e6284' => [
        'field_669679f71b1b0' => $propertyId,
        'field_66a7cf3944bf8' => FALSE, // Is New Property?
      ],
    ];
    foreach ($unitIndices as $i) {
      $newValue['field_66967511a2d57'][] = [
        'field_66968109025e6' => $units[$i]['id'],
      ];
    }
    $value[] = $newValue;
  }
  return $value;
}, 20, 3);

add_filter('acf/load_value/name=business_address', function ($value, $postId, $field) {
  if (empty($_GET['bid'])) return $value;
  $units = \Civi\Api4\Unit::get(false)
    ->addSelect('*', 'address.*')
    ->addJoin('UnitBusiness AS unit_business', 'LEFT', ['id', '=', 'unit_business.unit_id'])
    ->addJoin('Address AS address', 'LEFT', ['address_id', '=', 'address.id'])
    ->addWhere('unit_business.business_id', '=', $_GET['bid'])
    ->execute();
  foreach ($units as $unit) {
    $newValue = [
      get_acf_key('business_address_group') => [
        get_acf_key('unitsuite') => $unit['address.street_unit'],
        get_acf_key('street_address') => $unit['address.street_address'],
        get_acf_key('city') => $unit['address.city'],
        get_acf_key('postal_code') => $unit['address.postal_code'],
        get_acf_key('unit_location') => $unit['unit_location'],
        get_acf_key('unit_id') => $unit['id'],
      ],
    ];
    $value[] = $newValue;
  }
  return $value;
}, 20, 3);

function getContactDetails(): array|null {
  $cid = $_GET['cid'] ?? null;
  return $cid ? \Civi\Api4\Contact::get(FALSE)
    ->addSelect('*', 'email_primary.email', 'phone_primary.phone')
    ->addWhere('id', '=', $cid)
    ->single() : null;
}

/**
 * Gets the contact record for a business, including custom fields
 */
function getBusinessDetails(): array|null {
  $bid = $_GET['bid'] ?? null;
  return $bid ? \Civi\Api4\Contact::get(FALSE)
    ->addWhere('id', '=', $bid)
    ->addWhere('contact_sub_type', '=', 'Members_Businesses_')
    ->addJoin('Website AS website', 'LEFT', ['id', '=', 'website.contact_id'])
    ->addSelect('*', 'custom.*', 'email_primary.email', 'phone_primary.phone', 'website.url')
    ->execute()[0] : null;
}
