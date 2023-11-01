<?php

require_once 'biasync.civix.php';
// phpcs:disable
use CRM_Biasync_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function biasync_civicrm_config(&$config) {
  _biasync_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function biasync_civicrm_install() {
  _biasync_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function biasync_civicrm_enable() {
  _biasync_civix_civicrm_enable();
}

/**
* This hook is called after a db write on property entities.
*
* @param string $op
*   The type of operation being performed.
* @param string $objectName
*   The name of the object.
* @param int $objectId
*   The unique identifier for the object.
* @param object $objectRef
*   The reference to the object.
*/
function biasync_civicrm_post(string $op, string $objectName, int $objectId, &$objectRef) {
  $modified = ['edit','create','delete'];
  if ($objectName === 'Property') {
    if (in_array($op,$modified)) {
      $log = \Civi\Api4\PropertyLog::update(TRUE)
        ->addJoin('Property AS property', 'LEFT', ['property_id', '=', 'property.id'])
        ->addWhere('property_id','=',$objectId)
        ->addValue('is_synced',FALSE)
        ->execute();
    }
  }
  if ($objectName === 'Contact') {
    $results = \Civi\Api4\Contact::update(TRUE)
      ->addValue('Synced.is_synced', 0)
      ->addWhere('id', '=', $objectId)
      ->execute();
  }
}


// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function biasync_civicrm_preProcess($formName, &$form) {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
//function biasync_civicrm_navigationMenu(&$menu) {
//  _biasync_civix_insert_navigation_menu($menu, 'Mailings', [
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ]);
//  _biasync_civix_navigationMenu($menu);
//}
