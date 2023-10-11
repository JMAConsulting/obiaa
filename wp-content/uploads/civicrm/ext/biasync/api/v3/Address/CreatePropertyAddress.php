<?php

/**
 * Adjust Metadata for Create Property Address action.
 *
 * @param array $params
 *   Array of parameters determined by getfields.
 */
function _civicrm_api3_address_create_property_address_spec(&$params) {
  $params['location_type_id']['api.required'] = 1;
  $params['street_parsing'] = [
    'title' => 'Street Address Parsing',
    'description' => 'Optional param to indicate you want the street_address field parsed into individual params',
    'type' => CRM_Utils_Type::T_BOOLEAN,
  ];
  $params['skip_geocode'] = [
    'title' => 'Skip geocode',
    'description' => 'Optional param to indicate you want to skip geocoding (useful when importing a lot of addresses
      at once, the job \'Geocode and Parse Addresses\' can execute this task after the import)',
    'type' => CRM_Utils_Type::T_BOOLEAN,
  ];
  $params['fix_address'] = [
    'title' => ts('Fix address'),
    'description' => ts('When true, apply various fixes to the address before insert. Default true.'),
    'type' => CRM_Utils_Type::T_BOOLEAN,
    'api.default' => TRUE,
  ];
  $params['world_region'] = [
    'title' => ts('World Region'),
    'name' => 'world_region',
    'type' => CRM_Utils_Type::T_TEXT,
  ];
  $defaultLocation = CRM_Core_BAO_LocationType::getDefault();
  if ($defaultLocation) {
    $params['location_type_id']['api.default'] = $defaultLocation->id;
  }
}

function civicrm_api3_address_create_property_address($params) {
  return civicrm_api3_address_create($params);
}