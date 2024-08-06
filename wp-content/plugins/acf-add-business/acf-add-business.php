<?php

/*
Plugin Name: ACF Add Business Form Handler
Description: Provides custom handling to create contacts, businesses, properties, and units for the Add a Business form.
Version: 1.0
Author: JMA
Author URI: https://jmaconsulting.biz
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

defined('ABSPATH') or die('No script kiddies please!');

add_filter('acf/load_field/name=property_address', 'acf_load_property_choices');

function acf_load_property_choices( $field ) {
    // reset choices
    $field['choices'] = array();

    // Initialize CiviCRM
    civicrm_initialize();
    $choices = Property::get(FALSE)
      ->addSelect('id', 'property_address')
      ->addOrderBy('property_address', 'ASC')
      ->execute();

    foreach($choices as $choice) {
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
        $like_conditions[] = $wpdb->prepare('contact_custom_7 LIKE %s', '%'. $wpdb->esc_like($id) . '%');
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
    }
    else {
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
    // reset choices to an empty array
    $field['choices'] = array();
    return $field;
}


add_filter('acf/validate_value/key=field_669679f71b1b0', 'validate_tax_roll', 10, 4);

function validate_tax_roll($valid, $value, $field, $input) {
    $keys = preg_split('/[\[\]]+/', $input, -1, PREG_SPLIT_NO_EMPTY);
    $is_new_property = $_POST;

    // Check if a new property is being created in this row
    foreach ($keys as $key) {
        if (isset($is_new_property[$key])) {
            if($key != 'field_669679f71b1b0') {
                $is_new_property = $is_new_property[$key];
            }
            else {
                if (isset($is_new_property['field_66a7cf3944bf8'])) {
                    $is_new_property = $is_new_property['field_66a7cf3944bf8'];
                }
                else {
                    $is_new_property = '';
                }
            }

        } else {
            $is_new_property = '';
            break;
        }
    }

    // If not a new property, make sure tax roll is filled
    if ($is_new_property == 0) {
        if (empty($value)) {
            $valid = 'Tax Roll Address is required.';
        }
    }

    return $valid;
}

add_filter('acf/validate_value/key=field_66a3f9f05f9bb', 'validate_new_address', 10, 4);

function validate_new_address($valid, $value, $field, $input) {
    $keys = preg_split('/[\[\]]+/', $input, -1, PREG_SPLIT_NO_EMPTY);
    $is_new_property = $_POST;

    // Check if a new property is being created in this row
    foreach ($keys as $key) {
        if (isset($is_new_property[$key])) {
            if($key != 'field_66a3f9f05f9bb') {
                $is_new_property = $is_new_property[$key];
            }
            else {
                if (isset($is_new_property['field_66a7cf3944bf8'])) {
                    $is_new_property = $is_new_property['field_66a7cf3944bf8'];
                }
                else {
                    $is_new_property = '';
                }
            }

        } else {
            $is_new_property = '';
            break;
        }
    }

    // If a new property, make sure new tax roll address is filled
    if ($is_new_property == 1) {
        if (empty($value)) {
            $valid = 'New Tax Roll Address is required.';
        }
    }

    return $valid;
}

add_filter('acf/validate_value/key=field_66968109025e6', 'validate_unit_address', 10, 4);

function validate_unit_address($valid, $value, $field, $input) {
    $keys = preg_split('/[\[\]]+/', $input, -1, PREG_SPLIT_NO_EMPTY);
    $is_new_unit = $_POST;

    // Check if a new unit is being created in this row
    foreach ($keys as $key) {
        if (isset($is_new_unit[$key])) {
            if($key != 'field_66968109025e6') {
                $is_new_unit = $is_new_unit[$key];
            }
            else {
                if (isset($is_new_unit['field_66a7cb3396664'])) {
                    $is_new_unit = $is_new_unit['field_66a7cb3396664'];
                }
                else {
                    $is_new_unit = '';
                }
            }

        } else {
            $is_new_unit = '';
            break;
        }
    }

    // If not a new unit, require original unit address selection
    if ($is_new_unit == 0) {
        if (empty($value)) {
            $valid = 'Unit Address is required.';
        }
    }

    return $valid;
}

add_filter('acf/validate_value/key=field_66a4007826665', 'validate_new_unit', 10, 4);

function validate_new_unit($valid, $value, $field, $input) {
    $keys = preg_split('/[\[\]]+/', $input, -1, PREG_SPLIT_NO_EMPTY);

    // Check if a new unit is being created in this row
    $is_new_unit = $_POST;
    foreach ($keys as $key) {
        if (isset($is_new_unit[$key])) {
            if($key != 'field_66a4007826665') {
                $is_new_unit = $is_new_unit[$key];
            }
            else {
                if (isset($is_new_unit['field_66a7cb3396664'])) {
                    $is_new_unit = $is_new_unit['field_66a7cb3396664'];
                }
                else {
                    $is_new_unit = '';
                }
            }

        } else {
            $is_new_unit = '';
            break;
        }
    }

    // If new unit, require new unit address selection
    if ($is_new_unit == 1) {
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
                if (array_key_exists('field_669679f71b1b0', $fields) && !empty($fields['field_669679f71b1b0'])) {
                    $propertyEntered = TRUE;
                }
                elseif (array_key_exists('field_669679ed1b1af', $fields) && !empty($fields['field_669679ed1b1af'])) {
                    // New tax Roll address is present.
                    $propertyEntered = TRUE;
                }
                if ($key === 'field_66967511a2d57') {
                    foreach ($fields as $unitRow => $unitFields) {
                        if (array_key_exists('field_66968109025e6', $unitFields) && !empty($unitFields['field_66968109025e6'])) {
                            $unitEntered = TRUE;
                        }
                        elseif (array_key_exists('field_66a4007826665', $unitFields) && !empty($unitFields['field_66a4007826665'])) {
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
    // Check if this is an Add a Business ACF form submission
    if (isset($_POST['acf']) && $_POST['_acf_post_id'] == 443) {

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
            'google_maps_url' => 'Social_Media.Google_Business_Profile',
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

        $params = [
            'organization_name' => find_field_value($form_data,'field_66957220aad77'),
            'email' => find_field_value($form_data,'field_6695737bea222'),
            'phone' => find_field_value($form_data,'field_66980be820bb3'),
            'website' => find_field_value($form_data,'field_66957386ea223'),
            'category' => find_field_value($form_data,'field_6695739bea224'),
            'sub_category' => find_field_value($form_data,'field_669573c0ea225'),
            'local_bia' => find_field_value($form_data,'field_669573cdea226'),
            'opened_date' => find_field_value($form_data,'field_669573dbea227'),
            'opt_in' => find_field_value($form_data,'field_669573f6ea229')[0],
            'number_of_employees' => find_field_value($form_data,'field_669573e9ea228'),
            'linkedin_url' => find_field_value($form_data,'field_6696803713c32'),
            'google_maps_url' => find_field_value($form_data,'field_6696805c13c36'),
            'facebook_url' => find_field_value($form_data,'field_6696804d13c34'),
            'instagram_url' => find_field_value($form_data,'field_6696805413c35'),
            'twitter_url' => find_field_value($form_data,'field_6696804513c33'),
            'ticktok_url' => find_field_value($form_data,'field_6696809813c37'),
            'francophone' => find_field_value($form_data,'field_66967cef2ceaa'),
            'women' => find_field_value($form_data,'field_66967d0b2ceac'),
            'youth_39_under' => find_field_value($form_data,'field_66967d9f82049'),
            'lgbtiq' => find_field_value($form_data,'field_66967db58204a'),
            'indigenous' => find_field_value($form_data,'field_66967df28204b'),
            'racialized' => find_field_value($form_data,'field_66967e198204c'),
            'newcomers' => find_field_value($form_data,'field_66967e318204d'),
            'black' => find_field_value($form_data,'field_66967e5b10bc3'),
            'disabilities'  => find_field_value($form_data,'field_66967e7810bc4'),
            'first_name' => find_field_value($form_data,'field_669678fd8537e'),
            'last_name' => find_field_value($form_data,'field_669679098537f'),
            'contact_position' => find_field_value($form_data,'field_6696793985382'),
            'contact_email' => find_field_value($form_data,'field_6696791485380'),
            'contact_phone' => find_field_value($form_data,'field_6696792385381'),
        ];

        $propAndUnitDeets = find_field_value($form_data, 'field_669674ee2ea21');

        $properties = [];

        foreach($propAndUnitDeets as $propDeets) {
            if (isset($propDeets['field_66967535e6284'])) {
                $property = $propDeets['field_66967535e6284'];

                // Create an array to store the property details
                $propertyDetails = [
                    'roll_no' => $property['field_669679ed1b1af'] ?? '',
                    'property_address' => $property['field_669679f71b1b0'] ?? '',
                    'new_property_address' =>$property['field_66a3f9f05f9bb'] ?? '',
                    'city' => $property['field_66967a011b1b1'] ?? '',
                    'postal_code' => $property['field_66967a0b1b1b2'] ?? '',
                    'is_new_property' => $property['field_66a7cf3944bf8'] ?? '',
                    'units' => []
                ];

                // Check if there are unit details under 'field_66967511a2d57'
                if (isset($propDeets['field_66967511a2d57'])) {
                    foreach ($propDeets['field_66967511a2d57'] as $unit) {
                        $unitDetails = [
                            'unit_status' => $unit['field_669678b28537a'] ?? '',
                            'unit_address' => $unit['field_66968109025e6'] ?? '',
                            'new_unit_address' => $unit['field_66a4007826665'] ?? '',
                            'unit_size' => $unit['field_6696811e025e8'] ?? '',
                            'unit_price' => $unit['field_6696812c025e9'] ?? '',
                            'unit_location' => $unit['field_66968146025eb'] ?? '',
                            'mls_listing_link' => $unit['field_66968138025ea'] ?? '',
                            'property_unit' => $unit['field_66968111025e7'] ?? '',
                            'is_new_unit' => $unit['field_66a7cb3396664'] ?? '',
                        ];
                        $propertyDetails['units'][] = $unitDetails;
                    }
                }
                $properties[] = $propertyDetails;
            }
        }

        // Try and Find matching business (member) record.
        $organizationName = !empty($params['organization_name']) ? $params['organization_name'] : $params['first_name'] . '  ' . $params['last_name'];
        $organizationNameFieldDefinition = Contact::getFields(FALSE)->addWhere('name', '=', 'organization_name')->execute()->first();
        if (mb_strlen($organizationName) > $organizationNameFieldDefinition['input_attrs']['maxlength']) {
            $organizationName = CRM_Utils_String::ellipsify($organizationName, $organizationNameFieldDefinition['input_attrs']['maxlength']);
        }

        $dedupeParams = [
            'contact_sub_type' => 'Members_Businesses_',
            'organization_name' => $organizationName,
        ];
        $duplicates = CRM_Contact_BAO_Contact::getDuplicateContacts($dedupeParams, 'Organization');

        if (count($duplicates) == 0) {
            $contact = Contact::create(FALSE)
                ->addValue('contact_type', 'Organization')
                ->addValue('organization_name', $organizationName)
                ->addValue('contact_sub_type', ['Members_Businesses_'])
                ->execute()
                ->first();
        }
        elseif (count($duplicates) >= 1) {
            $contact =  Contact::get(FALSE)->addWhere('id', '=', $duplicates[0])->execute()->first();
        }

        foreach($properties as $propertyDeets) {
            // Go looking for a property first.
            if((!isset($propertyDeets['is_new_unit']) || !$propertyDeets['is_new_unit']) && is_numeric($propertyDeets['property_address']))
            {
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
            if(empty($propertyOwner) || !isset($propertyOwner['owner_id'])) {

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

            foreach($propertyDeets['units'] as $unitDeets) {
                $unitStatus = OptionValue::get(FALSE)
                    ->addWhere('option_group_id:name', '=', $optionGroups['unit_status'])
                    ->addWhere('value', '=', $unitDeets['unit_status'])
                    ->execute()
                    ->first()['value'];

                if($unitDeets['is_new_unit']) {
                    $unitStreetAddress = empty($unitDeets['new_unit_address']) ? $property['property_address'] : $unitDeets['new_unit_address'];
                }

                else {
                    $unitStreetAddress = $property['property_address'];
                }

                $unitOp = empty($unitDeets['property_unit']) ? 'IS NULL' : '=';
                $unitValue = empty($unitDeets['property_unit']) ? '' : $unitDeets['property_unit'];

                if(!$unitDeets['is_new_unit'] && is_numeric($unitDeets['unit_address'])) {
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
                        ->addValue('street_unit', (empty($unitDeets['property_unit']) ? NULL : $unitDeets['property_unit']))
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
                    UnitBusiness::create(FALSE)->addValue('unit_id', $unit['id'])->addValue('business_id', $contact['id'])->execute();
                }
                else {
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
                        ->addWhere('business_id','=', $contact['id'])
                        ->addWhere('unit_id', '=', $unit['id'])
                        ->execute();

                    if(!count($unitBusinesses)) {
                        UnitBusiness::create(FALSE)
                            ->addValue('business_id', $contact['id'])
                            ->addValue('unit_id', $unit['id'])
                            ->execute();
                    }
                }
            }
        }

        // Now Look for Business Contact / create business contact.
        $phoneOnBiz = !empty($params['organization_name']);
        if (!empty($params['first_name']) || !empty($params['last_name']) || !empty($params['contact_email'])) {
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
                        $possiblePhones = Phone::get(FALSE)
                            ->addWhere('phone', '=', $params['phone'])
                            ->addWhere('contact_id', 'IN', $contactDuplicates)
                            ->execute();

                        if (count($possiblePhones) == 1) {
                            $businessContactId = $possiblePhones[0]['contact_id'];
                        }
                        else {
                            $businessContactId = $contactDuplicates[0];
                        }
                    }
                }
                else {
                    $businessContactId = $contactDuplicates[0];
                }

                $businessContact = Contact::get(FALSE)
                    ->addWhere('id', '=', $businessContactId)
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
            }
            else {
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
                $phones = Phone::get(FALSE)->addWhere('contact_id', '=', $businessContactId)->addWhere('phone', '=', $params['contact_phone'])->execute();
                if(!count($phones)) {
                    Phone::create(FALSE)->addValue('phone', $params['contact_phone'])->addValue('phone_type_id:label', 'Phone')->addValue('location_type_id:label', 'Work')->addValue('contact_id', $businessContactId)->execute();
                }
            }
            if (!empty($params['contact_email'])) {
                $emails = Email::get(FALSE)->addWhere('contact_id', '=', $businessContactId)->addWhere('email', '=', $params['contact_email'])->execute();
                if(!count($emails)) {
                    Email::create(FALSE)->addValue('email', $params['contact_email'])->addValue('location_type_id:label', 'Work')->addValue('contact_id', $businessContactId)->execute();
                }
            }
            $relationship = Relationship::get(FALSE)->addWhere('contact_id_a', '=', $businessContactId)->addWhere('contact_id_b', '=', $contact['id'])->execute()->first();
            // set the Position on the employer/employee relatonship.
            Relationship::update(FALSE)->addValue('Business_Contact.Business_Contact_Position', $params['contact_position'])->addWhere('id', '=', $relationship['id'])->execute();
        }

        // If we have created the business using first name and last name put the phone on the business as well.
        if ($phoneOnBiz) {
            $phones = Phone::get(FALSE)->addWhere('contact_id', '=', $contact['id'])->addWhere('phone', '=', $params['phone'])->execute();
            if(!count($phones)) {
                Phone::create(FALSE)->addValue('phone', $params['phone'])->addValue('phone_type_id:label', 'Phone')->addValue('location_type_id:label', 'Work')->addValue('contact_id', $contact['id'])->execute();
            }
        }
        // Store the Business email and website on the business record.
        if (!empty($params['email'])) {
            $emails = Email::get(FALSE)->addWhere('contact_id', '=', $contact['id'])->addWhere('email', '=', $params['email'])->execute();
            if(!count($emails)) {
                Email::create(FALSE)->addValue('email', $params['email'])->addValue('location_type_id:label', 'Work')->addValue('contact_id', $contact['id'])->execute();
            }
        }
        if (!empty($params['website'])) {
            $websites = Website::get(FALSE)->addWhere('contact_id', '=', $contact['id'])->addWhere('url', '=', $params['website'])->execute();
            if(!count($emails)) {
                Website::create(FALSE)->addValue('url', $params['website'])->addValue('website_type_id:label', 'Work')->addValue('contact_id', $contact['id'])->execute();
            }
        }
        // Now loop through all the fields that match to a custom field to create APIv4 Params.
        $orgValues = [];

        foreach ($customGroup as $field => $customField) {
            // If the field is linked to an option group check to see if there is a value for it in the database and if not only create it if the field is the local_bia field.
            if (!empty($optionGroups[$field]) && !empty($params[$field])) {
                if(is_array($params[$field])) {
                    // Handle array of values
                    foreach ($params[$field] as $value) {
                        $optionValue = OptionValue::get(FALSE)
                            ->addWhere('value', '=', $value)
                            ->addWhere('option_group_id:name', '=', $optionGroups[$field])
                            ->execute()
                            ->first();

                        if ($field !== 'local_bia' && empty($optionValue)) {
                            throw new \CRM_Core_Exception(E::ts('Value %1 supplied for field %2 does not exist in the database', [1 => $value, 2 => $field]));
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
                        throw new \CRM_Core_Exception(E::ts('value %1 supplied for field %2 does not exist in the database', [1 => $params[$field], 2 => $field]));
                    }
                    elseif (empty($optionValue)) {
                        $optionValue = OptionValue::create(FALSE)->addValue('value', $params[$field])->addValue('option_group_id:name', $optionGroups[$field])->addValue('value', $params[$field])->execute()->first();
                    }
                    $orgValues[$customField . ':label'] = [$params[$field]];
                }
            }
            elseif (isset($params[$field])) {
                $orgValues[$customField] = $params[$field];
            }
        }

        // Set the custom fields on the business contact.
        Contact::update(FALSE)
            ->setValues($orgValues)
            ->addValue('Business_Details.Open_Date', formatDateString($params['opened_date']))
            ->addValue('Business_Category.Opt_out_of_Public_Listing_:label', $params['opt_in'])
            ->addValue('Business_Details.Full_Time_Employees_at_this_location', $params['number_of_employees'])
            ->addWhere('id', '=', $contact['id'])
            ->execute();
    }
}

// Recursively search array for a given key
function find_field_value($array, $key) {
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
            $result = find_field_value($value, $key);
            // Return found match
            if ($result !== null) {
                return $result;
            }
        }
    }
    return null;
}

function formatDateString($dateString) {
    if($dateString != '') {
        $dateFormatted = DateTime::createFromFormat('Ymd', $dateString);
        return $dateFormatted->format('Y-m-d');
    }
    return '';
}
