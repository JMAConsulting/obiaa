<?php

class CRM_CiviMobileAPI_Api_CiviMobileVenue_Create extends CRM_CiviMobileAPI_Api_CiviMobileBase {

  /**
   * Returns results to api
   *
   * @return array
   * @throws api_Exception
   */
  public function getResult() {
    $geoCode = CRM_CiviMobileAPI_Utils_Agenda_Venue::getVenueGeocoderData($this->validParams);
    $this->validParams['latitude'] = $geoCode['latitude'];
    $this->validParams['longitude'] = $geoCode['longitude'];
    $venueId = CRM_CiviMobileAPI_BAO_LocationVenue::create($this->validParams)->id;
    $venue = civicrm_api3('CiviMobileVenue', 'getsingle', [
      'id' => $venueId]);

    return [$venue];
  }

  /**
   * Returns validated params
   *
   * @param $params
   *
   * @return array
   * @throws api_Exception
   */
  protected function getValidParams($params) {
    if (!CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionForCreateEventVenues()) {
      throw new api_Exception('You don`t have enough permissions.', 'do_not_have_enough_permissions');
    }

    if (!empty($params['id'])) {
      if (empty(CRM_CiviMobileAPI_BAO_LocationVenue::getAll($params['id']))) {
        throw new api_Exception('Wrong venue id');
      }
    } else {
      $this->checkLocation($params);
      $this->checkName($params);
      if (empty($params['background_color']) || empty($params['border_color'])) {
        $colorParams = CRM_CiviMobileAPI_Utils_Agenda_Venue::getNextColorInListForLocation($params['location_id']);
        $params['background_color'] = $colorParams['background'];
        $params['border_color'] = $colorParams['border'];
      } elseif (strlen($params['background_color']) > 30 || strlen($params['border_color']) > 30) {
        throw new api_Exception('Color has to contain less than 30 characters.');
      }
      if (!isset($params['is_active'])) {
        $params['is_active'] = 1;
      }
    }

    if (isset($params['is_active']) && (int) $params['is_active'] !== 1 && (int) $params['is_active'] !== 0) {
      throw new api_Exception('Wrong is active parameter');
    }
    if (!isset($params['id'])) {
      $this->checkLocation($params);
      $this->checkName($params);
    }

    $sameNameVenue = CRM_CiviMobileAPI_BAO_LocationVenue::getAll([
      'name' => !empty($params['name']) ? $params['name'] : '',
      'location_id' => !empty($params['location_id']) ? $params['location_id'] : ''
    ]);

    if (!empty($sameNameVenue)
      && empty($params["id"])
      || !empty($sameNameVenue)
      && !empty($params["id"])
      && $params["id"] != $sameNameVenue[0]['id']
      && !empty($params["name"])) {
      throw new api_Exception ('Venue with same name already exists for this location.', 'venue_with_same_name_already_exist');
    }

    return $params;
  }

  /**
   * Check venue name parameter
   *
   * @param $params
   * @throws api_Exception
   */
  private function checkName($params) {
    if (empty($params['name'])) {
      throw new api_Exception('Empty name');
    }

    if (strlen(($params['name'])) > 255) {
      throw new api_Exception('Name length must be less than 255 characters.');
    }
  }

  /**
   * Check venue location parameter
   *
   * @param $params
   * @throws api_Exception
   */
  private function checkLocation($params) {
    if (empty($params['location_id'])) {
      throw new api_Exception('Empty location id');
    }

    $event = new CRM_Event_BAO_Event();
    $event->loc_block_id = $params['location_id'];
    if (!$event->find(TRUE)) {
      throw new api_Exception('Wrong location id');
    }
  }

}
