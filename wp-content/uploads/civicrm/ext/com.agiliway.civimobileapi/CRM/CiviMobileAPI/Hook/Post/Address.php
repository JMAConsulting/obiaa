<?php

class CRM_CiviMobileAPI_Hook_Post_Address {

  public static function run($op, $objectName, $objectId) {
    if ($objectName == 'Address') {
      $locBlocks = civicrm_api3('LocBlock', 'get', [
        'address_id' => $objectId,
        'options' => ['limit' => 0],
      ])['values'];

      foreach ($locBlocks as $locBlock) {
        CRM_CiviMobileAPI_Utils_Agenda_Venue::rebuildVenueGeoDate($locBlock['id']);
      }
    }
  }
}
