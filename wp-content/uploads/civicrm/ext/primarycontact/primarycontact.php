<?php

require_once 'primarycontact.civix.php';
require_once 'primarycontact.constants.inc';
// phpcs:disable
use CRM_Primarycontact_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function primarycontact_civicrm_config(&$config) {
  _primarycontact_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function primarycontact_civicrm_install() {
  _primarycontact_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function primarycontact_civicrm_enable() {
  _primarycontact_civix_civicrm_enable();
}

function primarycontact_civicrm_custom( $op, $groupID, $entityID, &$params ) {
  if ($groupID == PRIMARY_GROUP) {
    foreach ($params as $param) {
      if (!empty($param['value']) && $param['custom_field_id'] == PRIMARY_FIELD) {
        // Check to see if there are other contacts set as primary.
        $contacts = \Civi\Api4\Contact::get(FALSE)
          ->addSelect('id')
          ->addWhere('Staff_Information.Primary_Staff_Member_', '=', 1)
          ->addWhere('id', '!=', $entityID)
          ->execute();
        if (!empty($contacts)) {
          foreach ($contacts as $contact) {
            \Civi\Api4\Contact::update(FALSE)
              ->addValue('Staff_Information.Primary_Staff_Member_', 0)
              ->addWhere('id', '=', $contact['id'])
              ->execute();
          }
        }
      }
    }
  }
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function primarycontact_civicrm_preProcess($formName, &$form) {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
//function primarycontact_civicrm_navigationMenu(&$menu) {
//  _primarycontact_civix_insert_navigation_menu($menu, 'Mailings', array(
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ));
//  _primarycontact_civix_navigationMenu($menu);
//}
