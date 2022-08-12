<?php

require_once 'biaproperty.civix.php';
// phpcs:disable
use CRM_Biaproperty_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function biaproperty_civicrm_config(&$config) {
  _biaproperty_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function biaproperty_civicrm_install() {
  _biaproperty_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function biaproperty_civicrm_postInstall() {
  _biaproperty_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function biaproperty_civicrm_uninstall() {
  _biaproperty_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function biaproperty_civicrm_enable() {
  _biaproperty_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function biaproperty_civicrm_disable() {
  _biaproperty_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function biaproperty_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _biaproperty_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function biaproperty_civicrm_entityTypes(&$entityTypes) {
  _biaproperty_civix_civicrm_entityTypes($entityTypes);
}

function biaproperty_civicrm_pre($op, $objectName, $id, &$params) {
  $cid = CRM_Core_Session::singleton()->getLoggedInContactID();
  if ($objectName == 'Individual' && $cid) {
    $contactSubType = \Civi\Api4\Contact::get(FALSE)
      ->addSelect('contact_sub_type:label', 'contact_sub_type:name')
      ->addWhere('id', '=', $cid)
      ->execute()->first()['contact_sub_type:label'][0] ?? '';
    if ($contactSubType == 'BIA Staff' && $op == 'create' && in_array('BIA Staff' , $params['contact_sub_type'])) {
       CRM_Core_Error::statusBounce(ts('You do not have permission to create BIA contact.'));
    }
  }
}

function biaproperty_civicrm_tabset($tabsetName, &$tabs, $context) {
    if ('civicrm/contact/view' == $tabsetName && !empty($context['contact_id'])) {
    $contactType = \Civi\Api4\Contact::get(FALSE)
      ->addSelect('contact_sub_type:label', 'contact_sub_type:name')
      ->addWhere('id', '=', $context['contact_id'])
      ->execute()->first()['contact_sub_type:label'][0];
    if ($contactType == 'Member (Business)') {
      foreach ($tabs as $key => $tab) {
        if ($tab['id'] == 'afsearchProperties') {
          unset($tabs[$key]);
        }
      }
    }
  }
}

function biaproperty_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Activity_Form_ActivityLinks') {
    $cid = CRM_Utils_Request::retrieve('cid', 'Positive', $form);
    if ($cid) {
      $subTypes = \Civi\Api4\Contact::get(FALSE)
      ->addSelect('contact_sub_type:label', 'contact_sub_type:name')
      ->addWhere('id', '=', $cid)
      ->execute()->first()['contact_sub_type:label'];
      if (in_array('Member (Business)', $subTypes)) {
      $pid = \Civi\Api4\UnitBusiness::get(FALSE)
       ->addWhere('business_id', '=', $cid)
       ->execute() 
       ->first()['property_id'];
      $at = CRM_Utils_Array::value('values', civicrm_api3('OptionValue', 'get', [
        'option_group_id' => 'activity_type',
        'is_active' => 1,
        'label' => ['IN' => ['Move Business within BIA', 'Close business']],
        'options' => ['limit' => 0, 'sort' => 'weight'],
        ]));
       $links = [];
       foreach ($at as $activity) {
         $activity['url'] = $activity['label'] == 'Close business' ? CRM_Utils_System::url('civicrm/close-business', 'bid=' . $cid) : CRM_Utils_System::url('civicrm/buisness', 'bid=' . $cid);
         $links[$activity['value']] = $activity;
       }
       $form->assign('activityTypes', $links);
      }
    }
  }
  if ($formName == 'CRM_Biaproperty_Form_ExistingProperty') {
     CRM_Core_Resources::singleton()->addScript(
      "CRM.$(function($) {
        CRM.config.entityRef.links.Property = [
         {label: 'Add Property', url: '/wp-admin/admin.php?page=CiviCRM&q=civicrm%2Fproperty%2Fform&reset=1&action=add&context=create'}
        ];
     });
    ");
  }
  if ($formName == 'CRM_Biaproperty_Form_AddBuisness' || $formName == 'CRM_Biaproperty_Form_Unit') {
    CRM_Core_Resources::singleton()->addScript(
      "CRM.$(function($) {
        if ($('#unit_status').length) {
        $('#business_id').parent().parent().hide();
        }
        var status = $('#unit_status').val();
        if (status == 1) {
          $('#business_id').parent().parent().show();
        }
        $('#unit_status').on('change', function() {
           $('#business_id').parent().parent().toggle(($(this).val() == 1));
        });
        CRM.config.entityRef.links.Contact[1]['label'] = 'New Business';
        CRM.config.entityRef.links.Contact[1]['url'] = '/wp-admin/admin.php?page=CiviCRM&q=civicrm%2Fprofile%2Fcreate&reset=1&context=dialog&gid=17'; 
     });
    ");
  }
}


// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function biaproperty_civicrm_preProcess($formName, &$form) {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
//function biaproperty_civicrm_navigationMenu(&$menu) {
//  _biaproperty_civix_insert_navigation_menu($menu, 'Mailings', [
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ]);
//  _biaproperty_civix_navigationMenu($menu);
//}
