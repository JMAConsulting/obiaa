<?php

require_once 'obiaatemplate.civix.php';
// phpcs:disable
use CRM_Obiaatemplate_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function obiaatemplate_civicrm_config(&$config) {
  _obiaatemplate_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function obiaatemplate_civicrm_install() {
  _obiaatemplate_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function obiaatemplate_civicrm_enable() {
  _obiaatemplate_civix_civicrm_enable();
}

function obiaatemplate_civicrm_mosaicoBaseTemplates(&$templates) {
  $templates['obiaa-newsletter'] = [
    'name' => 'obiaa-newsletter',
    'title' => 'obiaa-newsletter',
    'path' => E::url('obiaa-newsletter/template-obiaa-newsletter.html'),
    'thumbnail' => E::url('obiaa-newsletter/edres/_full.png'),
  ];
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function obiaatemplate_civicrm_preProcess($formName, &$form) {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
//function obiaatemplate_civicrm_navigationMenu(&$menu) {
//  _obiaatemplate_civix_insert_navigation_menu($menu, 'Mailings', [
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ]);
//  _obiaatemplate_civix_navigationMenu($menu);
//}
