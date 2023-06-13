<?php

/**
 * Deletes all venues without location
 *
 * @param array $params
 *
 * @return array
 */
function civicrm_api3_civi_mobile_lost_venues_clean($params) {
  CRM_CiviMobileAPI_BAO_LocationVenue::deleteVenuesWithoutLocation();

  return civicrm_api3_create_success([
    'message' => 'All venues without location was deleted.'
  ]);
}
