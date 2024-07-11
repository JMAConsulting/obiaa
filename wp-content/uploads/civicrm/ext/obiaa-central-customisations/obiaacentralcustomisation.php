<?php

require_once 'obiaacentralcustomisation.civix.php';
require_once 'obiaacentralcustomisation.variables.php';
// phpcs:disable
use CRM_Obiaacentralcustomisation_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function obiaacentralcustomisation_civicrm_config(&$config) {
  _obiaacentralcustomisation_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function obiaacentralcustomisation_civicrm_install() {
  _obiaacentralcustomisation_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function obiaacentralcustomisation_civicrm_enable() {
  _obiaacentralcustomisation_civix_civicrm_enable();
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function obiaacentralcustomisation_civicrm_preProcess($formName, &$form) {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
//function obiaacentralcustomisation_civicrm_navigationMenu(&$menu) {
//  _obiaacentralcustomisation_civix_insert_navigation_menu($menu, 'Mailings', [
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ]);
//  _obiaacentralcustomisation_civix_navigationMenu($menu);
//}

function obiaacentralcustomisation_civicrm_tabset($tabsetName, &$tabs, $context) {
  if ($tabsetName === 'civicrm/contact/view') {
    foreach ($tabs as $key => $tab) {
      if ($tab['id'] === OBIAA_SYNC_CUSTOMGROUP) {
        unset($tabs[$key]);
      }
    }
  }
}
