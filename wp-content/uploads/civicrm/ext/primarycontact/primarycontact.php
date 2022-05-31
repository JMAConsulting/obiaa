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
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function primarycontact_civicrm_xmlMenu(&$files) {
  _primarycontact_civix_civicrm_xmlMenu($files);
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
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function primarycontact_civicrm_postInstall() {
  _primarycontact_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function primarycontact_civicrm_uninstall() {
  _primarycontact_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function primarycontact_civicrm_enable() {
  _primarycontact_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function primarycontact_civicrm_disable() {
  _primarycontact_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function primarycontact_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _primarycontact_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function primarycontact_civicrm_managed(&$entities) {
  _primarycontact_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function primarycontact_civicrm_caseTypes(&$caseTypes) {
  _primarycontact_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function primarycontact_civicrm_angularModules(&$angularModules) {
  _primarycontact_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function primarycontact_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _primarycontact_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function primarycontact_civicrm_entityTypes(&$entityTypes) {
  _primarycontact_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function primarycontact_civicrm_themes(&$themes) {
  _primarycontact_civix_civicrm_themes($themes);
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
