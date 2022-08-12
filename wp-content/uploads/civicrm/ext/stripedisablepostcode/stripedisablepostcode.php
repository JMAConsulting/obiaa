<?php

require_once 'stripedisablepostcode.civix.php';
// phpcs:disable
use CRM_Stripedisablepostcode_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function stripedisablepostcode_civicrm_config(&$config) {
  _stripedisablepostcode_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function stripedisablepostcode_civicrm_install() {
  _stripedisablepostcode_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function stripedisablepostcode_civicrm_postInstall() {
  _stripedisablepostcode_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function stripedisablepostcode_civicrm_uninstall() {
  _stripedisablepostcode_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function stripedisablepostcode_civicrm_enable() {
  _stripedisablepostcode_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function stripedisablepostcode_civicrm_disable() {
  _stripedisablepostcode_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function stripedisablepostcode_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _stripedisablepostcode_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function stripedisablepostcode_civicrm_entityTypes(&$entityTypes) {
  _stripedisablepostcode_civix_civicrm_entityTypes($entityTypes);
}

function stripedisablepostcode_civicrm_buildForm($formName, &$form) {
  if ($formName === 'CRM_Contribute_Form_Contribution_Main') {
    $resources = CRM_Core_Region::instance('billing-block')->getAll();
    foreach ($resources as $resource) {
      if (strpos($resource['name'], 'civicrmStripe') !== FALSE) {
        $test = CRM_Core_Region::instance('billing-block')->update($resource['name'], [
          'scriptUrl' => \Civi::service('asset_builder')->getUrl(
            'civicrmStripeDisablePostcode.js',
            [
              'path' => \Civi::resources()->getPath(E::LONG_NAME, 'js/civicrm_stripe.js'),
              'mimetype' => 'application/javascript',
            ]
          ),
        ]);
      }
    }
    $resources = CRM_Core_Region::instance('billing-block')->getAll();
    $stripeVars = $form->get_template_vars('stripeJSVars');
    $stripeVars['profilePostCodeFieldId'] = 1;
    \Civi::resources()->addVars('stripe', ['profilePostCodeFieldId' => "1"]);
    $form->assign('stripeJSVars', $stripeVars);
  }
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function stripedisablepostcode_civicrm_preProcess($formName, &$form) {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
//function stripedisablepostcode_civicrm_navigationMenu(&$menu) {
//  _stripedisablepostcode_civix_insert_navigation_menu($menu, 'Mailings', [
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ]);
//  _stripedisablepostcode_civix_navigationMenu($menu);
//}
