<?php

require_once 'biamailingreplyto.civix.php';
// phpcs:disable
use CRM_Biamailingreplyto_ExtensionUtil as E;
// phpcs:enable
use Civi\Api4\Contact;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function biamailingreplyto_civicrm_config(&$config): void {
  _biamailingreplyto_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function biamailingreplyto_civicrm_install(): void {
  _biamailingreplyto_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function biamailingreplyto_civicrm_enable(): void {
  _biamailingreplyto_civix_civicrm_enable();
}

function biamailingreplyto_civicrm_pre($op, $objectName, $id, &$params) {
  if ($objectName === 'Mailing' && ($op === 'create' || $op === 'edit')) {
    $primaryContactId = Civi::settings()->get('biamailingreplyto_primary_contact_id');
    $contactDetails = Contact::get(FALSE)
      ->addSelect('first_name')
      ->addSelect('last_name')
      ->addSelect('email.email')
      ->addJoin('Email AS email', 'INNER', ['email.contact_id', '=', 'id'], ['email.is_primary', '=', 1])
      ->addWhere('id', '=', $primaryContactId)
      ->execute()
      ->first();
    if (!empty($contactDetails)) {
      $params['replyto_email'] = '"' . $contactDetails['first_name'] . ' ' . $contactDetails['last_name'] . '" <' . $contactDetails['email.email'] . '>';
    }
  }
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function biamailingreplyto_civicrm_preProcess($formName, &$form): void {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
function biamailingreplyto_civicrm_navigationMenu(&$menu): void {
  _biamailingreplyto_civix_insert_navigation_menu($menu, 'Administer/CiviMail', [
    'label' => E::ts('BIA Mailing Reply To Extension Settings'),
    'name' => 'biamailingreplyto_settings',
    'url' => 'civicrm/admin/setting/biamailingreplyto',
    'permission' => 'administer CiviCRM',
    'operator' => 'OR',
    'separator' => 0,
  ]);
  _biamailingreplyto_civix_navigationMenu($menu);
}
