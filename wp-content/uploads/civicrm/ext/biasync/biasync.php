<?php

require_once 'biasync.civix.php';
// phpcs:disable
use CRM_Biasync_ExtensionUtil as E;
// phpcs:enable
Use Civi\Api4\Contact;
use Civi\Api4\Property;
use Civi\Api4\Unit;
use Civi\Api4\PropertyLog;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function biasync_civicrm_config(&$config) {
  _biasync_civix_civicrm_config($config);
  \Civi::$statics['biasync']['post_sync_contact_update'] = \Civi::$statics['biasync']['post_sync_property_update'] = \Civi::$statics['biasync']['post_sync_activity_update'] = FALSE;
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
* This hook is called after a db write on entities.
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
  $modified = ['edit','create','delete','update','merge'];

  // Check if contact has been modified
  if ($objectName === 'Contact' && in_array($op,$modified) && !\Civi::$statics['biasync']['post_sync_contact_update']) {
    $results = Contact::update(TRUE)
      ->addValue('Is_Synced_Contacts.is_synced', 0)
      ->addWhere('id', '=', $objectId)
      ->execute();
  }

  // Check if relationship has been modified
  if ($objectName === 'Relationship' && in_array($op,$modified)) {
    $results = \Civi\Api4\Relationship::get(TRUE)
      ->addWhere('id', '=', $objectId)
      ->execute();

    foreach ($results as $result) {
      // Mark both contacts in relationship as not synced
      $contactsToUpdate = [$result['contact_id_a'],$result['contact_id_b']];

      $results = Contact::update(TRUE)
        ->addValue('Is_Synced_Contacts.is_synced', 0)
        ->addWhere('id', 'IN', $contactsToUpdate)
        ->execute();
    }
  }
  // Check if activity has been created
  if ($objectName === 'Activity' && $op == 'create') {
      $results = \Civi\Api4\Activity::update(TRUE)
        ->addValue('Is_Synced_Activites.is_synced', 0)
        ->addWhere('id', '=', $objectId)
        ->execute();
  }

  if (($objectName === 'PropertyOwner' || $objectName === 'UnitBusiness') && in_array($op, $modified)) {
    if ($objectName === 'UnitBusiness') {
      /* @var CRM_Biaproperty_DAO_UnitBusiness $objectRef */
      $objectRef->find(TRUE);
      $propertyId = Property::get(FALSE)
        ->addSelect('id')
        ->addJoin('Unit AS unit', 'INNER', ['id', '=', 'unit.property_id'])
        ->addWhere('unit.id', '=', $objectRef->unit_id)
        ->execute()
        ->first()['id'];
    }
    else {
      $propertyId = Property::get(FALSE)
        ->addSelect('id')
        ->addJoin('PropertyOwner AS property_owner', 'INNER', ['id', '=', 'property_owner.property_id'])
        ->addWhere('property_owner.id', '=', $objectId)
        ->execute()
        ->first()['id'];
    }
    PropertyLog::save(FALSE)
      ->setRecords(['property_id' => $propertyId, 'is_synced' => 0])
      ->setMatch(['property_id'])
      ->execute();
  }
  if ($objectName === 'Property' && in_array($op, $modified) && !\Civi::$statics['biasync']['post_sync_property_update']) {
    PropertyLog::save(FALSE)
      ->setRecords(['property_id' => $objectId, 'is_synced' => 0])
      ->setMatch(['property_id'])
      ->execute();
  }
  if ($objectName === 'Unit' && in_array($op, $modified)) {
    $objectRef->find(TRUE);
    PropertyLog::save(FALSE)
      ->setRecords(['property_id' => $objectRef->property_id, 'is_synced' => 0])
      ->setMatch(['property_id'])
      ->execute();
  }
}

function biasync_civicrm_custom($op, $groupID, $entityID, &$params) {
  // Check if custom contact fields have been modified
  $modified = ['edit','create','delete','update','merge'];
  $customContactGroups = \Civi\Api4\CustomGroup::get(TRUE)
    ->addSelect('id')
    ->addWhere('extends', '=', 'Contact')
    ->addWhere('name', '!=', 'Is_Synced_Contacts')
    ->execute();

  $groupFound = FALSE;
  foreach ($customContactGroups as $group) {
    if (isset($group['id']) && $group['id'] == $groupID) {
      $groupFound = TRUE;
      break; // Break out of the loop as soon as the number is found
    }
  }
  if ($groupFound && in_array($op,$modified) && !\Civi::$statics['biasync']['post_sync_contact_update']) {
    Contact::update(TRUE)
      ->addValue('Is_Synced_Contacts.is_synced', 0)
      ->addWhere('id', '=', $entityID)
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
