<?php

require_once CIVICRM_PLUGIN_DIR .  'civicrm' . DIRECTORY_SEPARATOR . 'api' . DIRECTORY_SEPARATOR . 'v3' . DIRECTORY_SEPARATOR . 'Generic' . DIRECTORY_SEPARATOR . 'Getlist.php';

/**
 * Get address list parameters.
 *
 * @see _civicrm_api3_generic_getlist_params
 *
 * @param array $request
 */
function _civicrm_api3_address_getlist_params(&$request) {
  if ($request['search_field'] === 'street_address') {
    $fieldsToReturn = ['street_unit', 'street_address'];
    $request['params']['return'] = array_unique(array_merge($fieldsToReturn, $request['extra']));
  }
  else {
    _civicrm_api3_generic_getlist_params($request);
  }
}

/**
 * Get List Function for address
 * @param array $apiRequest
 * @return mixed
 */
function civicrm_api3_address_getList($apiRequest) {
  $entity = 'address';
  $meta = civicrm_api3_generic_getfields(['action' => 'get'] + $apiRequest, FALSE)['values'];

  // If the user types an integer into the search
  $forceIdSearch = empty($apiRequest['id']) && !empty($apiRequest['input']) && !empty($meta['id']) && CRM_Utils_Rule::positiveInteger($apiRequest['input']) && (substr($apiRequest['input'], 0, 1) !== '0') && $apiRequest['search_field'] !== 'street_address';
  // Add an extra page of results for the record with an exact id match
  if ($forceIdSearch) {
    $apiRequest['page_num'] = ($apiRequest['page_num'] ?? 1) - 1;
    if (empty($apiRequest['page_num'])) {
      $apiRequest['id'] = $apiRequest['input'];
      unset($apiRequest['input']);
    }
  }

  // Hey api, would you like to provide default values?
  $fnName = "_civicrm_api3_{$entity}_getlist_defaults";
  $defaults = function_exists($fnName) ? $fnName($apiRequest) : [];
  _civicrm_api3_generic_getList_defaults($entity, $apiRequest, $defaults, $meta);

  // Hey api, would you like to format the search params?
  $fnName = "_civicrm_api3_{$entity}_getlist_params";
  $fnName = function_exists($fnName) ? $fnName : '_civicrm_api3_generic_getlist_params';
  $fnName($apiRequest);

  $request['params']['check_permissions'] = !empty($apiRequest['params']['check_permissions']);
  if ($apiRequest['search_field'] === 'street_address' && !isset($apiRequest['id'])) {
    $result = civicrm_api3_create_success(CRM_Biaproperty_BAO_Unit::unitAddressRetrieve($apiRequest['params']), $apiRequest['params'], 'Address', 'get');
  }
  else {
    $result = civicrm_api3($entity, 'get', $apiRequest['params']);
  }
  if (!empty($request['input']) && !empty($defaults['search_field_fallback']) && $result['count'] < $apiRequest['params']['options']['limit']) {
    // We support a field fallback. Note we don't do this as an OR query because that could easily
    // bypass an index & kill the server. We just 'pad' the results if needed with the second
    // query - this is effectively the same as what the old Ajax::getContactEmail function did.
    // Since these queries should be quick & often only one should be needed this is a simpler alternative
    // to constructing a UNION via the api.
    $apiRequest['params'][$defaults['search_field_fallback']] = $apiRequest['params'][$defaults['search_field']];
    if ($apiRequest['params']['options']['sort'] === $defaults['search_field']) {
      // The way indexing works here is that the order by field will be chosen in preference to the
      // filter field. This can result in really bad performance so use the filter field for the sort.
      // See https://github.com/civicrm/civicrm-core/pull/16993 for performance test results.
      $apiRequest['params']['options']['sort'] = $defaults['search_field_fallback'];
    }
    // Exclude anything returned from the previous query since we are looking for additional rows in this
    // second query.
    $apiRequest['params'][$defaults['search_field']] = ['NOT LIKE' => $apiRequest['params'][$defaults['search_field_fallback']]['LIKE']];
    $apiRequest['params']['options']['limit'] -= $result['count'];
    $result2 = civicrm_api3($entity, 'get', $apiRequest['params']);
    $result['values'] = array_merge($result['values'], $result2['values']);
    $result['count'] = count($result['values']);
  }
  else {
    // Re-index to sequential = 0.
    $result['values'] = array_merge($result['values']);
  }

  // Hey api, would you like to format the output?
  $fnName = "_civicrm_api3_{$entity}_getlist_output";
  $fnName = function_exists($fnName) ? $fnName : '_civicrm_api3_generic_getlist_output';
  $values = $fnName($result, $apiRequest, $entity, $meta);

  _civicrm_api3_generic_getlist_postprocess($result, $apiRequest, $values);

  $output = ['page_num' => $apiRequest['page_num']];

  if ($forceIdSearch && $apiRequest['search_field'] !== 'street_address') {
    $output['page_num']++;
    // When returning the single record matching id
    if (empty($apiRequest['page_num'])) {
      $output['more_results'] = TRUE;
      foreach ($values as $i => $value) {
        $description = ts('ID: %1', [1 => $value['id']]);
        $values[$i]['description'] = array_merge([$description], $value['description'] ?? []);
      }
    }
  }
  // Limit is set for searching but not fetching by id
  elseif (!empty($request['params']['options']['limit'])) {
    // If we have an extra result then this is not the last page
    $last = $request['params']['options']['limit'] - 1;
    $output['more_results'] = isset($values[$last]);
    unset($values[$last]);
  }

  return civicrm_api3_create_success($values, $apiRequest['params'], $entity, 'getlist', CRM_Core_DAO::$_nullObject, $output);
}

/**
 * Get address list output.
 *
 * @see _civicrm_api3_generic_getlist_output
 *
 * @param array $result
 * @param array $request
 * @param string $entity
 * @param array $getFieldsValues
 *
 * @return array
 */
function _civicrm_api3_address_getlist_output($result, $request, $entity, $getFieldsValues) {
  if ($request['search_field'] !== 'street_address') {
    return _civicrm_api3_generic_getlist_output($result, $request, $entity, $getFieldsValues);
  }
  $output = [];

  if (!empty($result['values'])) {
    foreach ($result['values'] as $row) {
      $data = [
        'id' => $row[$request['id_field']],
        'label' => (!(empty($row['street_unit'])) ? '#' .$row['street_unit'] . ' - ' : '') . $row['street_address'],
      ];
      $output[] = $data;
    }
  }

  return $output;
}
