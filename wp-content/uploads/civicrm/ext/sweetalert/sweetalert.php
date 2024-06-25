<?php

require_once 'sweetalert.civix.php';
use CRM_Sweetalert_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function sweetalert_civicrm_config(&$config) {
  _sweetalert_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function sweetalert_civicrm_install() {
  _sweetalert_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function sweetalert_civicrm_enable() {
  _sweetalert_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_coreResourceList().
 */
function sweetalert_civicrm_coreResourceList(&$items, $region) {
  if ($region === 'html-header') {
    Civi::resources()
      ->addStyleFile(E::SHORT_NAME, 'css/sweetalert2.min.css', 0, $region)
      ->addScriptFile(E::SHORT_NAME, 'js/sweetalert2.min.js', 0, $region);
  }
}
