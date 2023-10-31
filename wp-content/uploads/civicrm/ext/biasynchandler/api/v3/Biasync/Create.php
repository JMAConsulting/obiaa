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
function _civicrm_api3_biasync_Create_spec(&$spec) {
  #$spec['magicword']['api.required'] = 1;
}

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
    //get entity name from the parameter
    $entity = $request['entity'];
    $params = $request['params'];
    $options = [];
    //check if this id exists in the entity
    $response = civicrm_api3($entity, 'get', ['id' => $params['source_record_id']]);
    if (!empty($response)) {
      // If we receive a $response['id'], then entity exists and we proceed to update   the entity with the params sent.
      switch ($entity) {
        case "Property":
          //***************************************** */
          //get property entity changes
          $prop = wpcmrf_api('Property', 'create', ['source_record_id' => $params['source_record_id'], 'source_record' => $params['source_record']], $options, WPCMRF_ID)->getReply();
          $units = unit::get()->addWhere('property_id', '=', $params['source_record_id'])->execute();
          $unitIds = [];
          foreach ($units as $unit) {
            $unitArray = (array) $unit;
            $unitIds[] = $unit['id'];
            $unitAddress = civicrm_api3('Address', 'get', ['id' => $unit['address_id']])['values'][$unit['address_id']];
            $remoteUnit = wpcmrf_api('Unit', 'get', ['source_record_id' => $unit['id'], 'source_record' => $params['source_record'], 'sequential' => 1], $options, WPCMRF_ID)->getReply();
            // If we have a remote unit replace the id field in unitArray and the id of the unitAddress array with the relevant id from the remote unit record.
            $unitAddress['contact_id'] = 'Null';
            if (!empty($remoteUnit['values'])) {
              $unitAddress['id'] = $remoteUnit['values'][0]['address_id'];
              $unitArray['id'] = $remoteUnit['id'];
            }
            else {
              // Otherwise we are going to be creating a unit so unset the id fields. 
              unset($unitAddress['id']);
              unset($unitArray['id']);
            }
            $unitArray['property_id'] = $propertyCheck['id'];
            $remoteAddress = wpcmrf_api('Address', 'create', $unitAddress, $options, WPCMRF_ID)->getReply();
            $unitArray['address_id'] = $remoteAddress['id'];
            $unitArray['source_record_id'] = $unit['id'];
            $unitArray['source_record'] = $params['source_record'];
            wpcmrf_api('Unit', 'create', $unitArray, $options, WPCMRF_ID)->getReply();
          }
          $missingUnits = wpcmrf_api('Unit', 'get', ['property_id' => $params['source_record_id'], 'source_recor_id' => ['NOT IN' => $unitIds], 'source_record' => $params['source_record']], $options, WPCMRF_ID)->getReply();
          foreach ($missingUnits['values'] as $missingUnit) {
            $businesses = wpcmrf_api('UnitBusiness', 'get', ['unit_id' => $missingUnit['id']], $options, WPCMRF_ID)->getReply();
            foreach ($businesses['values'] as $business) {
              wpcmrf_api('UnitBusiness', 'delete', ['id' => $business['id']], $options, WPCMRF_ID);
            }
            wpcmrf_api('Unit', 'delete', ['id' => $missingUnit['id']], $options, WPCMRF_ID);
          }

          break;
        case "Contact":
          //***************************************** */
          //get contact entity changes
          break;
        default:
          echo "No changes!";
      }
    } else {
      // If we do not receive anything in $response['id'], then we proceed to create the entity using the params.
      switch ($entity) {
        case "Property":
          //***************************************** */
          //get property entity changes
          $prop = wpcmrf_api('Property', 'create', ['source_record_id' => $params['source_record_id'], 'source_record' => $params['source_record']], $options, WPCMRF_ID)->getReply();
          $units = unit::get()->addWhere('property_id', '=', $params['source_record_id'])->execute();
          foreach ($units as $unit) {
            $unitArray = (array) $unit;
            $unitAddress = civicrm_api3('Address', 'get', ['id' => $unit['address_id']])['values'][$unit['address_id']];
            $unitArray['source_record_id'] = $unit['id'];
            unset($unitAddress['id']);
            unset($unitArray['id']);
            $unitAddress['contact_id'] = 'Null';
            $remoteUnitAddress = wpcmrf_api('Address', 'create', $unitAddress, $options, WPCMRF_ID)->getReply();
            $unitArray['address_id'] = $remoteUnitAddress['id'];
            $unitArray['source_record'] = $params['source_record'];
            $unitArray['property_id'] = $prop['id'];
            wpcmrf_api('Unit', 'create', $unitArray, $options, WPCMRF_ID)->getReply();
          }
  
          break;
        case "Contact":
          //***************************************** */
          //get contact entity changes
          
          break;
        default:
          echo "No changes!";
      }
    }
    
    // Spec: civicrm_api3_create_success($values = 1, $params = [], $entity = NULL, $action = NULL)
    return civicrm_api3_create_success($response, $request, 'Biasync', 'Create');
  }
}
