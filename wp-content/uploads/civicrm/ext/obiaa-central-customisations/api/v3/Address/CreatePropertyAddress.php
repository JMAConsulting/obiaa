<?php

/**
 * Adjust Metadata for Create Property Address action.
 *
 * @param array $params
 *   Array of parameters determined by getfields.
 */
function _civicrm_api3_address_create_property_address_spec(&$params) {
  $params = civicrm_api3('address', 'getfields', ['action' => 'create'])['values'];
  unset($params['contact_id']['api.required']);
}

function civicrm_api3_address_create_property_address($params) {
  /**
   * If street_parsing, street_address has to be parsed into
   * separate parts
   */
  if (array_key_exists('street_parsing', $params)) {
    if ($params['street_parsing'] == 1) {
      if (array_key_exists('street_address', $params)) {
        if (!empty($params['street_address'])) {
          $parsedItems = CRM_Core_BAO_Address::parseStreetAddress(
            $params['street_address']
          );
          if (array_key_exists('street_name', $parsedItems)) {
            $params['street_name'] = $parsedItems['street_name'];
          }
          if (array_key_exists('street_unit', $parsedItems)) {
            $params['street_unit'] = $parsedItems['street_unit'];
          }
          if (array_key_exists('street_number', $parsedItems)) {
            $params['street_number'] = $parsedItems['street_number'];
          }
          if (array_key_exists('street_number_suffix', $parsedItems)) {
            $params['street_number_suffix'] = $parsedItems['street_number_suffix'];
          }
        }
      }
    }
  }

  $params['check_permissions'] = 0;
  if (!isset($params['fix_address']) || $params['fix_address']) {
    CRM_Core_BAO_Address::fixAddress($params);
  }

  return _civicrm_api3_basic_create('CRM_Core_BAO_Address', $params, 'Address');
}
