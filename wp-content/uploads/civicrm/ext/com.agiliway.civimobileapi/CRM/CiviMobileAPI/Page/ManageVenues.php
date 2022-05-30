<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

/**
 * Provides token disabling functionality for CiviMobile application
 */
class CRM_CiviMobileAPI_Page_ManageVenues extends CRM_Core_Page {

  public function run() {
    $null = NULL;
    $isActive = [
      1 => 'Yes',
      0 => 'No',
    ];

    $location_id = CRM_Utils_Request::retrieve('location_id', 'Positive');
    $this->assign('location_id', $location_id);
    if (empty($location_id)) {
      return parent::run();
    }
    elseif (!in_array($location_id, array_keys(CRM_Event_BAO_Event::getLocationEvents()))) {
      CRM_Core_Error::fatal(E::ts('Wrong location id'));
    }
    $venues = civicrm_api3('CiviMobileVenue', 'get', [
      'location_id' => $location_id,
      'options' => ['sort' => "weight asc"],
    ])['values'];

    $returnURL = CRM_Utils_System::url("civicrm/civimobile/manage-venues",
      "reset=1&location_id=" . $location_id
    );
    $filter = "location_id = " . $location_id;

    CRM_Utils_Weight::addOrder($venues, 'CRM_CiviMobileAPI_DAO_LocationVenue', 'id', $returnURL, $filter);

    $this->assign('venues', $venues);
    $this->assign('is_active', $isActive);
    $this->assign('use_back_button', CRM_Utils_Request::retrieve('use_back_button', 'Boolean', $null, FALSE, 1));
    $this->assign('can_edit_venue', CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionForCreateEventVenues());
    $this->assign('can_delete_venue', CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionForDeleteEventVenues());
    return parent::run();
  }

}
