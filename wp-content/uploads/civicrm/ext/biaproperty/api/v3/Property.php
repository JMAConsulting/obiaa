<?php


/**
 * Returns array of assignments matching a set of one or more group properties
 *
 * @param array $params  Associative array of property name/value pairs
 *                       describing the assignments to be retrieved.
 * @example
 * @return array ID-indexed array of matching assignments
 * {@getfields assignment_get}
 * @access public
 */
function civicrm_api3_property_get($params) {
  return civicrm_api3_create_success(CRM_Biaproperty_BAO_Property::retrieve($params), $params, 'Property', 'get');
}


function _civicrm_api3_property_getlist_output ($result, $request, $entity, $fields) {
  $output = [];
  if (!empty($result['values'])) {
    foreach ($result['values'] as $key => $row) {
      $data = [
        'id' => $row['id'],
        'label' => $row['address_id.name']  ? $row['address_id.name'] . ' - ' . $row['address_id.street_address'] : $row['address_id.street_address'],
      ];
      $output[] = $data;
    }
  }

  return $output;
}
