<?php

/**
 * Plugin Name: ACF Add Business Form Handler
 * Description: Provides custom handling to create contacts, businesses, properties, and units for the Add a Business form.
 * Version: 1.0
 * Author: JMA
 * Author URI: https://jmaconsulting.biz
 */

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
use Civi\Api4\PropertyOwner;
use Civi\Api4\Organization;

//TODO: save the street address of the units corretly

defined('ABSPATH') or die('No script kiddies please!');

// Load ACF hooks to fill in form defaults
require_once 'field-defaults.php';

function redirect_invalid_checksum($template) {
  if (!is_page('update-business')) return; // Not an update business form so not our problem
  if (!empty($_GET['cs'])) {
    $cs = $_GET['cs'];
  }
  if (!empty($_GET['cid'])) {
    $cid = $_GET['cid'];
  }
  if (!empty($_GET['cs']) && !empty($_GET['cid'])) {
    if (CRM_Contact_BAO_Contact_Utils::validChecksum($cid, $cs)) {
      // Valid request - no redirect
      return;
    }
  }
  // Not a valid request so we redirect them to WP home
  // NOTE: we might want to redirect to a custom notice page saying it was a 
  // invalid request. This is where we would indicate the url
  wp_redirect(home_url());
  exit();
}

add_filter('acf/load_field/name=property_address', 'acf_load_property_choices');

function acf_load_property_choices($field) {
  // reset choices
  $field['choices'] = array();

  // Initialize CiviCRM
  civicrm_initialize();
  $choices = Property::get(FALSE)
    ->addSelect('id', 'property_address')
    ->addOrderBy('property_address', 'ASC')
    ->execute();

  foreach ($choices as $choice) {
    $field['choices'][$choice['id']] = $choice['property_address'];
  }

  return $field;
}

add_action('wp_enqueue_scripts', 'enqueue_acf_custom_js');

function enqueue_acf_custom_js() {
  wp_register_script(
    'acf-custom-js',
    plugin_dir_url(__FILE__) . 'js/acf-custom.js',
    array('jquery'),
    '1.0',
    true
  );
  wp_enqueue_script('acf-custom-js');

  wp_localize_script('acf-custom-js', 'acf_ajax_object', [
    'ajax_url' => admin_url('admin-ajax.php'),
    'security' => wp_create_nonce('acf_nonce')
  ]);
}

add_action('wp_ajax_get_sub_categories', 'get_sub_categories');
add_action('wp_ajax_nopriv_get_sub_categories', 'get_sub_categories');

function get_sub_categories() {
  global $wpdb;

  // Check for nonce security
  check_ajax_referer('acf_nonce', 'security');

  if (!isset($_POST['categories'])) {
    wp_send_json_error('No categories provided');
  }

  $table_name = 'civicrm_fieldcondition_3';

  // Category IDs to check against contact_custom_7
  $ids = array_map('intval', $_POST['categories']);

  $like_conditions = [];
  foreach ($ids as $id) {
    $like_conditions[] = $wpdb->prepare('contact_custom_7 LIKE %s', '%' . $wpdb->esc_like($id) . '%');
  }

  $like_query = implode(' OR ', $like_conditions);

  $query = "SELECT DISTINCT contact_custom_8 FROM $table_name WHERE $like_query";

  // Execute the query and fetch the results
  $results = $wpdb->get_results($query, ARRAY_A);

  // Extract contact_custom_8 values
  $contact_custom_8_values = [];
  if (!empty($results)) {
    foreach ($results as $row) {
      if (!empty($row['contact_custom_8'])) {
        $contact_custom_8_values[] = $row['contact_custom_8'];
      }
    }
  } else {
    wp_send_json_error("No subcategories found");
  }

  // Remove duplicates
  $contact_custom_8_values = array_unique($contact_custom_8_values);

  // Convert the array to JSON
  $contact_custom_8_json = json_encode($contact_custom_8_values);

  wp_send_json_success($contact_custom_8_json);
}


add_action('wp_ajax_get_property_fields', 'get_property_fields');
add_action('wp_ajax_nopriv_get_property_fields', 'get_property_fields');

function get_property_fields() {
  // Check for nonce security
  check_ajax_referer('acf_nonce', 'security');

  if (!isset($_POST['property_id'])) {
    wp_send_json_error('No property ID provided');
  }

  $property_id = intval($_POST['property_id']);

  civicrm_initialize();

  $property = Property::get(FALSE)
    ->addWhere('id', '=', $property_id)
    ->execute();

  wp_send_json_success($property[0]);
}

add_action('wp_ajax_get_units_by_property', 'get_units_by_property');
add_action('wp_ajax_nopriv_get_units_by_property', 'get_units_by_property');

function get_units_by_property() {
  // Check for nonce security
  check_ajax_referer('acf_nonce', 'security');

  if (!isset($_POST['property_id'])) {
    wp_send_json_error('No property ID provided');
  }

  $property_id = intval($_POST['property_id']);

  civicrm_initialize();

  $units = Unit::get(FALSE)
    ->addSelect('address.street_unit', 'address.street_address', '*', 'property.property_address')
    ->addJoin('Address AS address', 'LEFT', ['address_id', '=', 'address.id'])
    ->addJoin('Property AS property', 'LEFT', ['property_id', '=', 'property.id'])
    ->addWhere('property_id', '=', $property_id)
    ->addOrderBy('address.street_unit', 'ASC')
    ->execute();

  $choices = [];
  foreach ($units as $unit) {
    $choice = [
      'id' => $unit['id'],
      'label' => 'Unit #' . $unit['address.street_unit'] . ' ' . $unit['address.street_address'],
      'unit_size' => $unit['unit_size'],
      'unit_location' => $unit['unit_location'],
      'unit_price' => $unit['unit_price'],
      'unit_status' => $unit['unit_status'],
      'mls_listing_link' => $unit['mls_listing_link'],
      'unit_suite' => $unit['address.street_unit'],
      'default_address' => $unit['property.property_address']
    ];
    $choices[$unit['id']] = $choice;
  }

  wp_send_json_success($choices);
}

add_filter('acf/load_field/name=unit_address', 'acf_load_unit_choices');

function acf_load_unit_choices($field) {
  $choices = Unit::get(FALSE)
    ->addSelect('id', 'address_id.street_address', 'address_id.street_unit')
    ->addOrderBy('address_id.street_address', 'ASC')
    ->execute();

  foreach ($choices as $choice) {
    // $field['choices'][$choice['id']] = '';
    $field['choices'][$choice['id']] = 'Unit #' . $choice['address_id.street_unit'] . ' ' . $choice['address_id.street_address'];
  }

  // reset choices to an empty array
  // $field['choices'] = array();
  return $field;
}


add_filter('acf/validate_value/name=property_address', 'validate_tax_roll', 10, 4);

function validate_tax_roll($valid, $value, $field, $input) {
  $keys = preg_split('/[\[\]]+/', $input, -1, PREG_SPLIT_NO_EMPTY);

  // Check if a new property is being created in this row
  foreach ($keys as $key) {
    if (isset($_POST[$key])) {
      if ($key != get_acf_key('property_address')) {
        $is_new_property = !empty($_POST[$key]);
      } else {
        $is_new_property = $POST[get_acf_key('is_new_property')] ?? false;
      }
    } else {
      $is_new_property = false;
      break;
    }
  }

  // If not a new property, make sure tax roll is filled
  if (!$is_new_property) {
    if (empty($value)) {
      $valid = 'Tax Roll Address is required.';
    }
  }

  return $valid;
}

add_filter('acf/validate_value/name=tax_roll_address', 'validate_new_address', 10, 4);

function validate_new_address($valid, $value, $field, $input) {
  $keys = preg_split('/[\[\]]+/', $input, -1, PREG_SPLIT_NO_EMPTY);

  // Check if a new property is being created in this row
  foreach ($keys as $key) {
    if (isset($is_new_property[$key])) {
      if ($key != get_acf_key('tax_roll_address')) {
        $is_new_property = !empty($_POST[$key]);
      } else {
        $is_new_property = $_POST[get_acf_key('is_new_property')] ?? false;
      }
    } else {
      $is_new_property = false;
      break;
    }
  }

  // If a new property, make sure new tax roll address is filled
  if ($is_new_property) {
    if (empty($value)) {
      $valid = 'New Tax Roll Address is required.';
    }
  }

  return $valid;
}

add_filter('acf/validate_value/name=unit_address', 'validate_unit_address', 10, 4);

function validate_unit_address($valid, $value, $field, $input) {
  $keys = preg_split('/[\[\]]+/', $input, -1, PREG_SPLIT_NO_EMPTY);
  $is_new_unit = $_POST;

  // Check if a new unit is being created in this row
  foreach ($keys as $key) {
    if (isset($is_new_unit[$key])) {
      if ($key != get_acf_key('unit_address')) {
        $is_new_unit = $_POST[$key];
      } else {
        $is_new_unit = $_POST[get_acf_key('is_new_unit')] ?? false;
      }
    } else {
      $is_new_unit = false;
      break;
    }
  }

  // If not a new unit, require original unit address selection
  if (!$is_new_unit) {
    if (empty($value)) {
      $valid = 'Unit Address is required.';
    }
  }

  return $valid;
}

add_filter('acf/validate_value/name=new_unit_address', 'validate_new_unit', 10, 4);

function validate_new_unit($valid, $value, $field, $input) {
  $keys = preg_split('/[\[\]]+/', $input, -1, PREG_SPLIT_NO_EMPTY);

  // Check if a new unit is being created in this row
  foreach ($keys as $key) {
    if (isset($is_new_unit[$key])) {
      if ($key != get_acf_key('new_unit_address')) {
        $is_new_unit = $_POST[$key];
      } else {
        $is_new_unit = $_POST[get_acf_key('is_new_unit')] ?? false;
      }
    } else {
      $is_new_unit = false;
      break;
    }
  }

  // If new unit, require new unit address selection
  if ($is_new_unit) {
    if (empty($value)) {
      $valid = 'New Unit Address is required.';
    }
  }

  return $valid;
}

add_filter('acf/validate_value', 'validate_property_present', 20, 4);

function validate_property_present($valid, $value, $field, $input) {
  if ($valid !== TRUE) {
    return $value;
  }
  if (isset($field['name']) && $field['name'] == 'property_&_unit_details') {
    $propertyEntered = $unitEntered = FALSE;
    foreach ($value as $row => $sections) {
      foreach ($sections as $key => $fields) {
        // Existing Tax Roll Address Selected
        if (array_key_exists(get_acf_key('property_address'), $fields) && !empty($fields[get_acf_key('property_address')])) {
          $propertyEntered = TRUE;
        } elseif (array_key_exists(get_acf_key('roll_no'), $fields) && !empty($fields[get_acf_key('roll_no')])) {
          // New tax Roll address is present.
          $propertyEntered = TRUE;
        }
        if ($key === get_acf_key('unit_details')) {
          foreach ($fields as $unitRow => $unitFields) {
            if (array_key_exists(get_acf_key('unit_address'), $unitFields) && !empty($unitFields[get_acf_key('unit_address')])) {
              $unitEntered = TRUE;
            } elseif (array_key_exists(get_acf_key('new_unit_address'), $unitFields) && !empty($unitFields[get_acf_key('new_unit_address')])) {
              $unitEntered = TRUE;
            }
          }
        }
      }
    }
    if (!$propertyEntered || !$unitEntered) {
      return __('Need to link this business to at least one property and a unit');
    }
  }
  return $valid;
}

add_action('acf/save_post', 'add_business_form_handler_save_post');

function add_business_form_handler_save_post($post_id) {
  // Check if this is an ACF form and either an Add a Business form or Update Business form
  if (isset($_POST['acf']) && ($_POST['_acf_post_id'] == 443 || $_POST['_acf_post_id'] == 491)) {

    // Process submitted ACF fields
    $form_data = $_POST['acf'];

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
      'google_maps_link' => 'Social_Media.Google_Business_Profile',
      'francophone' => 'Ownership_Demographics.Francophone',
      'women' => 'Ownership_Demographics.Women',
      'youth' => 'Ownership_Demographics.Youth_39_and_under_',
      'lgbtiq' => 'Ownership_Demographics.Lesbian_gay_bisexual_transsexual_queer_LGBTQ_',
      'indigenous' => 'Ownership_Demographics.Indigenous_First_Nations_Inuit_or_Metis_',
      'racialized' => 'Ownership_Demographics.Racialized_group_member',
      'newcomers' => 'Ownership_Demographics.Newcomers_immigrants_and_refugees',
      'black' => 'Ownership_Demographics.Black_community_member',
      'disabilities' => 'Ownership_Demographics.People_with_disabilities',
    ];

    $fields = [
      'organization_name',
      'email',
      'phone',
      'website',
      'category',
      'sub_category',
      'local_bia',
      'date_of_opening',
      'opt_out_of_public_listings',
      'number_of_employees',
      'linkedin_url',
      'google_maps_link',
      'facebook_url',
      'instagram_url',
      'twitter_url',
      'ticktok_url',
      'francophone',
      'women',
      'youth',
      'lgbtiq',
      'indigenous',
      'racialized',
      'newcomers',
      'black',
      'disabilities',
      'first_name',
      'last_name',
      'contact_position',
      'contact_email',
      'contact_phone',
    ];
    $params = [];
    foreach ($fields as $field) {
      $params[$field] = array_find_key_recursive($form_data, get_acf_key($field));
    }
    if (empty($params['opt_out_of_public_listings'])) {
      $params['opt_out_of_public_listings'] = 'No';
    }

    if ($_POST['_acf_post_id'] == 443) { // Only applicable for Add business form
      $allPropertyAndUnitDetails = array_find_key_recursive($form_data, get_acf_key('property_&_unit_details'));
    }

    $properties = [];
    $unitsToUpdate = [];

    if ($allPropertyAndUnitDetails !== null) { // found property and unit details; this is an Add Business form
      foreach ($allPropertyAndUnitDetails as $propertyAndUnitDetails) {
        if (isset($propertyAndUnitDetails[get_acf_key('property_details')])) {
          $property = $propertyAndUnitDetails[get_acf_key('property_details')];

          // Create an array to store the property details
          $propertyDetails = [];
          $propertyFields = [
            'roll_no',
            'property_address',
            'new_property_address',
            'city',
            'postal_code',
            'is_new_property'
          ];
          foreach ($propertyFields as $field) {
            $propertyDetails[$field] = $property[get_acf_key($field)] ?? '';
          }
          $propertyDetails['units'] = [];

          // Check if there are unit details
          if (isset($propertyAndUnitDetails[get_acf_key('unit_details')])) {
            foreach ($propertyAndUnitDetails[get_acf_key('unit_details')] as $unit) {
              $unitDetails = [];
              $unitFields = [
                'unit_status',
                'unit_address',
                'new_unit_address',
                'unit_size',
                'unit_price',
                'unit_location',
                'mls_listing_link',
                'unitsuite',
                'is_new_unit',
              ];
              foreach ($unitFields as $field) {
                $unitDetails[$field] = $unit[get_acf_key($field)] ?? '';
              }
              $propertyDetails['units'][] = $unitDetails;
            }
          }
          $properties[] = $propertyDetails;
        }
      }
    } else { // Update business form
      // HACK: the properties array is just formatted like the add business form expects rather than anything more semantic
      $addresses = array_find_key_recursive($form_data, get_acf_key('business_address'));
      foreach ($addresses as $address) {
        $addressDetails = [];
        $addressFields = [
          'unitsuite',
          'street_address',
          'city',
          'postal_code',
          'unit_location',
          'unit_id'
        ];
        foreach ($addressFields as $field) {
          $addressDetails[$field] = array_find_key_recursive($address, get_acf_key($field)) ?? '';
        }
        $unitsToUpdate[] = $addressDetails;
      }
    }

    // Try and Find matching business (member) record.
    $organizationName = !empty($params['organization_name']) ? $params['organization_name'] : $params['first_name'] . ' ' . $params['last_name'];
    $organizationNameFieldDefinition = Contact::getFields(FALSE)->addWhere('name', '=', 'organization_name')->execute()->first();
    if (mb_strlen($organizationName) > $organizationNameFieldDefinition['input_attrs']['size']) {
      $organizationName = CRM_Utils_String::ellipsify($organizationName, $organizationNameFieldDefinition['input_attrs']['size']);
    }

    $businessId = isset($_GET['bid']) ? $_GET['bid'] : null;
    if (empty($businessId)) {
      $dedupeParams = [
        'contact_sub_type' => 'Members_Businesses_',
        'organization_name' => $organizationName,
      ];
      $duplicates = CRM_Contact_BAO_Contact::getDuplicateContacts($dedupeParams, 'Organization', checkPermissions: FALSE);
      if (count($duplicates) > 0)
        $businessId = $duplicates[0];
    } else {
      // business id provided so we should update the name if needed
      Contact::update(FALSE)
        ->addWhere('id', '=', $businessId)
        ->addValue('organization_name', $organizationName)
        ->execute();
    }

    if (empty($businessId)) {
      $contact = Contact::create(FALSE)
        ->addValue('contact_type', 'Organization')
        ->addValue('organization_name', $organizationName)
        ->addValue('contact_sub_type', ['Members_Businesses_'])
        ->execute()
        ->first();
    } else {
      $contact = Contact::get(FALSE)
        ->addSelect('*', 'website.id', 'email.id', 'phone.id')
        ->addJoin(
          'Website AS website',
          'LEFT',
          ['website.contact_id', '=', 'id'],
          ['website.website_type_id:label', '=', '"Work"']
        )
        ->addJoin(
          'Email AS email',
          'LEFT',
          ['email.contact_id', '=', 'id'],
          ['email.location_type_id:label', '=', '"Work"']
        )
        ->addJoin(
          'Phone AS phone',
          'LEFT',
          ['phone.contact_id', '=', 'id'],
          ['phone.phone_type_id:label', '=', '"Phone (landline)"'],
          ['phone.location_type_id:label', '=', '"Work"']
        )
        ->addWhere('id', '=', $businessId)
        ->execute()->first();
    }

    if (!empty($properties)) {
      $submittedUnitBusiness = createPropertiesAndUnits($properties, $optionGroups, $contact);

      // Remove any links ot units not submitted on this form
      UnitBusiness::delete(FALSE)
        ->addWhere('business_id', '=', $contact['id'])
        ->addWhere('id', 'NOT IN', $submittedUnitBusiness)
        ->execute();
    } else if (!empty($unitsToUpdate)) {
      updateUnits($unitsToUpdate);
    }

    // Now Look for Business Contact / create business contact.
    if (!empty($params['first_name']) || !empty($params['last_name']) || !empty($params['contact_email'])) {
      if (!empty($_GET['cid']) && is_numeric($_GET['cid'])) {
        $contactDuplicates = [$_GET['cid']];
      } else {
        $contactDups = \Civi\Api4\Individual::getDuplicates(FALSE)
          ->setDedupeRule('Individual.Supervised')
          ->addValue('first_name', $params['first_name'])
          ->addValue('last_name', $params['last_name'])
          ->addValue('email_primary.email', $params['contact_email'])
          ->execute();

        $contactDuplicates = [];

        // Loop through the input array
        foreach ($contactDups as $dup) {
          $contactDuplicates[] = (int)$dup['id'];
        }
      }

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
            // None of the contacts have the business as their current employer
            $possiblePhones = Phone::get(FALSE)
              ->addWhere('phone', '=', $params['phone'])
              ->addWhere('contact_id', 'IN', $contactDuplicates)
              ->execute();

            if (count($possiblePhones) == 1) {
              $businessContactId = $possiblePhones[0]['contact_id'];
            } else {
              $businessContactId = $contactDuplicates[0];
            }
          }
        } else {
          $businessContactId = $contactDuplicates[0];
        }
        $businessContact = Contact::update(FALSE)
          ->addWhere('id', '=', $businessContactId)
          ->addValue('first_name', $params['first_name'])
          ->addValue('last_name', $params['last_name'])
          ->addValue('email_primary.email', $params['contact_email'])
          ->execute()
          ->first();

        // Ensure that the individual (business contact) is linked to the employer.
        if (empty($businessContact['employer_id']) || $businessContact['employer_id'] !== $contact['id']) {
          Contact::update(FALSE)
            ->addValue('employer_id', $contact['id'])
            ->addValue('job_title', $params['contact_position'])
            ->addWhere('id', '=', $businessContactId)
            ->execute();
        }
        $businessContact = Contact::get(FALSE)
          ->addSelect('email.id', 'phone.id')
          ->addWhere('id', '=', $businessContactId)
          ->addJoin(
            'Email AS email',
            'LEFT',
            ['email.contact_id', '=', 'id'],
            ['email.location_type_id:label', '=', '"Work"']
          )
          ->addJoin(
            'Phone AS phone',
            'LEFT',
            ['phone.contact_id', '=', 'id'],
            ['phone.phone_type_id:label', '=', '"Phone (landline)"'],
            ['phone.location_type_id:label', '=', '"Work"']
          )
          ->execute()
          ->first();
      } else {
        $businessContactId = Contact::create(FALSE)
          ->addValue('first_name', $params['first_name'])
          ->addValue('last_name', $params['last_name'])
          ->addValue('contact_type', 'Individual')
          ->addValue('employer_id', $contact['id'])
          ->addValue('job_title', $params['contact_position'])
          ->execute()->first()['id'];
      }
      // Contact Details for the Business contact
      if (!empty($params['contact_phone'])) {
        $phone = [
          'phone' => $params['contact_phone'],
          'contact_id' => $businessContactId,
          'phone_type_id:label' => 'Phone (landline)',
          'location_type_id:label' => 'Work',
        ];
        if (isset($businessContact['phone.id'])) {
          $phone['id'] = $businessContact['phone.id'];
        }
        Phone::save(FALSE)
          ->addRecord($phone)
          ->execute();
      }
      if (!empty($params['contact_email'])) {
        $email = [
          'email' => $params['contact_email'],
          'contact_id' => $businessContactId,
          'location_type_id:label' => 'Work',
        ];
        if (isset($businessContact['email.id'])) {
          $email['id'] = $businessContact['email.id'];
        }
        Email::save(FALSE)
          ->addRecord($email)
          ->execute();
      }
      $relationship = Relationship::get(FALSE)
        ->addWhere('contact_id_a', '=', $businessContactId)
        ->addWhere('contact_id_b', '=', $contact['id'])
        ->execute()
        ->first();
      // set the Position on the employer/employee relatonship.
      Relationship::update(FALSE)
        ->addValue('Business_Contact.Business_Contact_Position', $params['contact_position'])
        ->addWhere('id', '=', $relationship['id'])
        ->execute();
    }

    // If we have created the business using first name and last name put the phone on the business as well.
    if ($params['phone']) {
      $phone = [
        'phone' => $params['phone'],
        'contact_id' => $contact['id'],
        'phone_type_id:label' => 'Phone (landline)',
        'location_type_id:label' => 'Work',
      ];
      if (isset($contact['phone.id'])) {
        $phone['id'] = $contact['phone.id'];
      }
      Phone::save(FALSE)
        ->addRecord($phone)
        ->execute();
    }
    // Store the Business email and website on the business record.
    if (!empty($params['email'])) {
      $email = [
        'email' => $params['email'],
        'contact_id' => $contact['id'],
        'location_type_id:label' => 'Work',
      ];
      if (isset($contact['email.id'])) {
        $email['id'] = $contact['email.id'];
      }
      Email::save(FALSE)
        ->addRecord($email)
        ->execute();
    }
    if (!empty($params['website'])) {
      $website = [
        'url' => $params['website'],
        'contact_id' => $contact['id'],
        'website_type_id:label' => 'Work'
      ];
      if (isset($contact['website.id'])) {
        $website['id'] = $contact['website.id'];
      }
      Website::save(FALSE)
        ->addRecord($website)
        ->execute();
    }
    // Now loop through all the fields that match to a custom field to create APIv4 Params.
    $orgValues = [];

    foreach ($customGroup as $field => $customField) {
      // If the field is linked to an option group check to see if there is a value for it in the database and if not only create it if the field is the local_bia field.
      if (!empty($optionGroups[$field]) && !empty($params[$field])) {
        if (is_array($params[$field])) {
          // Handle array of values
          foreach ($params[$field] as $value) {
            $optionValue = OptionValue::get(FALSE)
              ->addWhere('value', '=', $value)
              ->addWhere('option_group_id:name', '=', $optionGroups[$field])
              ->execute()
              ->first();

            if ($field !== 'local_bia' && empty($optionValue)) {
              throw new \CRM_Core_Exception(ts('Value %1 supplied for field %2 does not exist in the database', [1 => $value, 2 => $field]));
            } elseif (empty($optionValue)) {
              $optionValue = OptionValue::create(FALSE)
                ->addValue('value', $value)
                ->addValue('option_group_id:name', $optionGroups[$field])
                ->addValue('value', $value)
                ->execute()
                ->first();
            }
            $orgValues[$customField][] = $value; // Collect array of values
          }
        } else {
          $optionValue = OptionValue::get(FALSE)->addWhere('value', '=', $params[$field])->addWhere('option_group_id:name', '=', $optionGroups[$field])->execute()->first();
          if ($field !== 'local_bia' && empty($optionValue)) {
            throw new \CRM_Core_Exception(ts('value %1 supplied for field %2 does not exist in the database', [1 => $params[$field], 2 => $field]));
          } elseif (empty($optionValue)) {
            $optionValue = OptionValue::create(FALSE)->addValue('value', $params[$field])->addValue('option_group_id:name', $optionGroups[$field])->addValue('value', $params[$field])->execute()->first();
          }
          $orgValues[$customField . ':label'] = [$params[$field]];
        }
      } elseif (isset($params[$field])) {
        $orgValues[$customField] = $params[$field];
      }
    }

    // Set the custom fields on the business contact.
    Contact::update(FALSE)
      ->setValues($orgValues)
      ->addValue('Business_Details.Open_Date', formatDateString($params['date_of_opening']))
      ->addValue('Business_Category.Opt_out_of_Public_Listing_:label', $params['opt_out_of_public_listings'])
      ->addValue('Business_Details.Full_Time_Employees_at_this_location', $params['number_of_employees'])
      ->addWhere('id', '=', $contact['id'])
      ->execute();
  }
}

/**
 * Recursively searches an array for a key
 *
 * @param array $array the array to search
 * @param mixed $key the key to find in `$array`
 * @return mixed the value associated with `$key`. If multiple entries match `$key` (for example at different depths) one of them will be returned.
 * If there are no matching entries, returns `null`
 */
function array_find_key_recursive(array $array, mixed $key): mixed {
  if (!is_array($array)) {
    return null;
  }

  foreach ($array as $k => $value) {
    // If the key matches, return the value
    if ($k === $key) {
      return $value;
    }

    // If the value is an array, recursively search it
    if (is_array($value)) {
      $result = array_find_key_recursive($value, $key);
      // Return found match
      if ($result !== null) {
        return $result;
      }
    }
  }
  return null;
}

// NOTE: if performance is an issue we should cache found values here
/**
 * Gets the key associated with a given field name in acf
 *
 * @param string $field_name the name of the field to get the key for
 * @return string|null the key of the field or `null` if it is not found
 */
function get_acf_key(string $field_name): ?string {
  $field = acf_get_field($field_name);
  return $field ? $field['key'] : null;
}

function formatDateString($dateString): string {
  if ($dateString != '') {
    $dateFormatted = DateTime::createFromFormat('Ymd', $dateString);
    return $dateFormatted->format('Y-m-d');
  }
  return '';
}

function createPropertiesAndUnits(array $properties, $optionGroups, $contact): array {
  $submittedUnitBusiness = [];
  foreach ($properties as $propertyDeets) {
    // Go looking for a property first.
    if ((!isset($propertyDeets['is_new_unit']) || !$propertyDeets['is_new_unit']) && is_numeric($propertyDeets['property_address'])) {
      // If property was autofilled and ID is present
      $property = Property::get(FALSE)
        ->addWhere('id', '=', $propertyDeets['property_address'])
        ->execute()
        ->first();
    } else {
      $property = Property::get(FALSE)
        ->addWhere('property_address', '=', $propertyDeets['new_property_address'])
        ->execute()
        ->first();
    }

    // If no property is found, create a new one
    if (empty($property)) {
      $property = Property::create(FALSE)
        ->addValue('roll_no', $propertyDeets['roll_no'])
        ->addValue('property_address', $propertyDeets['new_property_address'])
        ->addValue('city', $propertyDeets['city'])
        ->addValue('postal_code', $propertyDeets['postal_code'])
        ->execute()
        ->first();
    }

    // Check to see if there is a Property Owner attached to the property
    $propertyOwner = PropertyOwner::get(FALSE)
      ->addWhere('property_id', '=', $property['id'])
      ->execute()
      ->first();

    // Create default property owner (contact = Empty Property Owner)
    if (empty($propertyOwner) || !isset($propertyOwner['owner_id'])) {

      $dummyOrg = Organization::get(FALSE)
        ->addSelect('id')
        ->addWhere('organization_name', '=', 'Empty Property Owner')
        ->execute()
        ->first();

      $propertyOwner = PropertyOwner::create(FALSE)
        ->addValue('property_id', $property['id'])
        ->addValue('owner_id', $dummyOrg['id'])
        ->addValue('is_voter', TRUE)
        ->execute()
        ->first();
    }

    foreach ($propertyDeets['units'] as $unitDeets) {
      $unitStatus = OptionValue::get(FALSE)
        ->addWhere('option_group_id:name', '=', $optionGroups['unit_status'])
        ->addWhere('value', '=', $unitDeets['unit_status'])
        ->execute()
        ->first()['value'];

      if ($unitDeets['is_new_unit']) {
        $unitStreetAddress = empty($unitDeets['new_unit_address']) ? $property['property_address'] : $unitDeets['new_unit_address'];
      } else {
        $unitStreetAddress = $property['property_address'];
      }

      $unitOp = empty($unitDeets['unitsuite']) ? 'IS NULL' : '=';
      $unitValue = empty($unitDeets['unitsuite']) ? '' : $unitDeets['unitsuite'];

      if (!$unitDeets['is_new_unit'] && is_numeric($unitDeets['unit_address'])) {
        $unit = Unit::get(FALSE)
          ->addSelect('*')->addSelect('unit_business.*')
          ->addJoin('UnitBusiness AS unit_business', 'INNER', ['unit_business.unit_id', '=', 'id'])
          ->addJoin('Address AS address', 'INNER', ['address.id', '=', 'address_id'])
          ->addWhere('id', '=', $unitDeets['unit_address'])
          ->addWhere('property_id', '=', $property['id'])
          ->execute()
          ->first();
      } else {
        // Ok now let us see if we already have a unit record in the system if we have gotten here then the unit won't have a business so it will be vacant at this point.
        $unit = Unit::get(FALSE)
          ->addSelect('*')->addSelect('unit_business.*')
          ->addJoin('UnitBusiness AS unit_business', 'INNER', ['unit_business.unit_id', '=', 'id'])
          ->addJoin('Address AS address', 'INNER', ['address.id', '=', 'address_id'])
          ->addWhere('address.street_address', '=', $unitStreetAddress)
          ->addWhere('address.street_unit', $unitOp, $unitValue)
          ->addWhere('property_id', '=', $property['id'])
          ->execute()->first();
      }

      if (empty($unit)) {
        // Ok no unit record found let us create it.
        $unitAddress = Address::create(FALSE)
          ->addValue('street_address', $unitStreetAddress)
          ->addValue('street_unit', (empty($unitDeets['unitsuite']) ? NULL : $unitDeets['unitsuite']))
          ->addValue('city', $property['city'])
          ->addValue('postal_code', $property['postal_code'])
          ->execute()
          ->first();
        $unit = Unit::create(FALSE)
          ->addValue('address_id', $unitAddress['id'])
          ->addValue('unit_size', $unitDeets['unit_size'])
          ->addValue('unit_price', $unitDeets['unit_price'])
          ->addValue('unit_status', 1) // Set status to occupied as default
          ->addValue('unit_location', $unitDeets['unit_location'])
          ->addValue('property_id', $property['id'])
          ->addValue('mls_listing_link', $unitDeets['mls_listing_link'])
          ->execute()
          ->first();
        $unitBusinesses = UnitBusiness::create(FALSE)
          ->addValue('unit_id', $unit['id'])
          ->addValue('business_id', $contact['id'])
          ->execute();
      } else {
        // ok we found one let us update it with the import data and update the unit business to link the unit to the business.
        Unit::update(FALSE)
          ->addValue('unit_size', $unitDeets['unit_size'])
          ->addValue('unit_price', $unitDeets['unit_price'])
          ->addValue('unit_status', $unitStatus)
          ->addValue('unit_location', $unitDeets['unit_location'])
          ->addValue('mls_listing_link', $unitDeets['mls_listing_link'])
          ->addWhere('id', '=', $unit['id'])
          ->execute();

        $unitBusinesses = UnitBusiness::get(FALSE)
          ->addWhere('business_id', '=', $contact['id'])
          ->addWhere('unit_id', '=', $unit['id'])
          ->execute();

        if (!count($unitBusinesses)) {
          $unitBusinesses = UnitBusiness::create(FALSE)
            ->addValue('business_id', $contact['id'])
            ->addValue('unit_id', $unit['id'])
            ->execute();
        }
      }
      $submittedUnitBusiness[] = $unitBusinesses->first()['id'];
    }
  }
  return $submittedUnitBusiness;
}

// NOTE: this does **not** allow users to create new units!!
function updateUnits($unitsToUpdate) {
  foreach ($unitsToUpdate as $unit) {
    $streetUnit = $unit['unitsuite'];
    $addressQuery = Address::get(FALSE)
      ->addSelect('id')
      ->addWhere('street_address', '=', $unit['street_address'])
      ->addWhere('city', '=', $unit['city'])
      ->addWhere('postal_code', '=', $unit['postal_code']);
    if (empty($streetUnit)) {
      $addressQuery->addWhere('street_unit', 'IS NULL');
    } else {
      $addressQuery->addWhere('street_unit', '=', $streetUnit);
    }
    $address = $addressQuery->execute()->first();
    if ($address == null) {
      // No address matches what the user inputted so they must have changed the address of the unit; we should create a new address
      $address = Address::create(FALSE)
        ->addValue('street_address', $unit['street_address'])
        ->addValue('city', $unit['city'])
        ->addValue('postal_code', $unit['postal_code'])
        ->addValue('street_unit', empty($streetUnit) ? NULL : $streetUnit) // NOTE: using ternary instead of null coalescing operator because streetUnit could be empty string
        ->execute()->first();
    }
    \Civi::log()->debug('Address', [$address]);
    \Civi::log()->debug('Unit', [$unit]);
    Unit::update(FALSE)
      ->addWhere('id', '=', $unit['unit_id'])
      ->addValue('unit_size', $unit['unit_size'])
      ->addValue('unit_price', $unit['unit_price'])
      ->addValue('unit_location', $unit['unit_location'])
      // ->addValue('unit_status', 1) // occupied
      ->addValue('address_id', $address['id'])
      ->execute();
    // Since we are only updating units we don't change the UnitBusinesses
  }
}
