<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_Hook_NavigationMenu {
  public static function run(&$menu) {
    $civiMobile = [
      'name' => E::ts('CiviMobile'),
      'permission' => 'administer CiviCRM',
      'operator' => NULL,
      'separator' => NULL,
    ];
    _civimobileapi_civix_insert_navigation_menu($menu, 'Administer/', $civiMobile);

    $civiMobileSettings = [
      'name' => E::ts('CiviMobile Settings'),
      'url' => 'civicrm/civimobile/settings',
      'permission' => 'administer CiviCRM',
      'operator' => NULL,
      'separator' => NULL,
    ];
    _civimobileapi_civix_insert_navigation_menu($menu, 'Administer/CiviMobile/', $civiMobileSettings);

    $civiMobileCalendarSettings = [
      'name' => E::ts('CiviMobile Calendar Settings'),
      'url' => 'civicrm/civimobile/calendar/settings',
      'permission' => 'administer CiviCRM',
      'operator' => NULL,
      'separator' => NULL,
    ];
    _civimobileapi_civix_insert_navigation_menu($menu, 'Administer/CiviMobile/', $civiMobileCalendarSettings);

    $civiMobileEventLocations = [
      'name' => E::ts('CiviMobile Event Locations'),
      'url' => 'civicrm/civimobile/event-locations',
      'permission' => 'administer CiviCRM',
      'operator' => NULL,
      'separator' => NULL,
    ];
    _civimobileapi_civix_insert_navigation_menu($menu, 'Administer/CiviEvent/', $civiMobileEventLocations);

    $civiMobileSettings = [
      'name' => E::ts('CiviMobile Checklist'),
      'url' => 'civicrm/civimobile/checklist',
      'permission' => 'administer CiviCRM',
      'operator' => NULL,
      'separator' => NULL,
    ];
    _civimobileapi_civix_insert_navigation_menu($menu, 'Administer/CiviMobile/', $civiMobileSettings);

    $civiMobileTabs = [
      'name' => E::ts('CiviMobile Tabs'),
      'url' => 'civicrm/admin/options/civi_mobile_tabs',
      'permission' => 'administer CiviCRM',
      'operator' => NULL,
      'separator' => NULL,
    ];
    _civimobileapi_civix_insert_navigation_menu($menu, 'Administer/CiviMobile/', $civiMobileTabs);

    $civiAiSettings = [
      'name' => E::ts('CiviAI Settings'),
      'url' => 'civicrm/civimobile/civiai-settings',
      'permission' => 'administer CiviCRM',
      'operator' => NULL,
      'separator' => NULL,
    ];
    _civimobileapi_civix_insert_navigation_menu($menu, 'Administer/CiviMobile/', $civiAiSettings);
  }
}
