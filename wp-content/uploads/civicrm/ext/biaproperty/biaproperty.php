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
      ->execute()->first()['contact_sub_type:label'] ?? [];
    if ($op == 'create' && in_array('BIA Staff' , $contactSubType) && $params['contact_sub_type'] == 'OBIAA_Staff') {
       CRM_Core_Error::statusBounce(ts('You do not have permission to create BIA contact.'));
    }
  }
}

function biaproperty_civicrm_tabset($tabsetName, &$tabs, $context) {
  if ('civicrm/contact/view' == $tabsetName && !empty($context['contact_id'])) {
    $contactType = \Civi\Api4\Contact::get(FALSE)
      ->addSelect('contact_sub_type:label', 'contact_sub_type:name')
      ->addWhere('id', '=', $context['contact_id'])
      ->execute()->first()['contact_sub_type:label'] ?? [];
    $userType = (!in_array('Member (Business)', $contactType) && !in_array('Member (Property Owner)', $contactType));
    $businessct = (in_array('Member (Business)', $contactType) && !in_array('Member (Property Owner)', $contactType));
    $propertyOwnerct = (!in_array('Member (Business)', $contactType) && in_array('Member (Property Owner)', $contactType));
    $bothct = (in_array('Member (Business)', $contactType) && in_array('Member (Property Owner)', $contactType));
    $staffct = in_array('BIA Staff', $contactType);
    if (($userType || $businessct || $propertyOwnerct || $staffct) && !$bothct) {
      foreach ($tabs as $key => $tab) {
        if ($userType || $businessct || $staffct) {
          if ($tab['id'] == 'afsearchProperties') {
            unset($tabs[$key]);
          }
        }
        if ($userType || $propertyOwnerct || $staffct) {
          if ($tab['id'] == 'afsearchUnit1') {
            unset($tabs[$key]);
          }
        }
      }
    }
  }
}

function biaproperty_civicrm_links($op, $objectName, $objectId, &$links, &$mask, &$values) {
  if ($objectName == 'Contact' && $op == 'view.contact.activity') {
    $contactDetails = \Civi\Api4\Contact::get(FALSE)
      ->addSelect('contact_sub_type:label', 'contact_sub_type:name', 'contact_type:name')
      ->addWhere('id', '=', $objectId)
      ->execute()->first();
    $subTypes = (array) $contactDetails['contact_sub_type:label'];
    $contactType = $contactDetails['contact_type:name'];
    // 'Add / Buy Property' should be available for all types of contact
    $links[] = [
      'title' => 'Add / Buy Property',
      'name' => 'Add / Buy Property',
      'weight' => 110,
      'ref' => 'new-note',
      'class' => 'no-popup',
      'url' => CRM_Utils_System::url('civicrm/existing/property', 'reset=1&action=add&context=create&oid=' . $objectId),
    ];
    if (in_array('Member (Business)', $subTypes)) {
      $links[] = [
        'title' => 'Add Unit',
        'name' => 'Add Unit',
        'weight' => 95,
        'ref' => 'new-note',
        'class' => 'no-popup',
        'url' => CRM_Utils_System::url('civicrm/unit/form', 'reset=1&action=add&context=create&pid=0&bid=' . $objectId),
      ];
      $links[] = [
        'title' => 'Move business within BIA',
        'name' => 'Move business within BIA',
        'weight' => 100,
        'ref' => 'new-note',
        'class' => 'no-popup',
        'url' => CRM_Utils_System::url('civicrm/add-business', 'bid=' . $objectId . '&change_title=1'),
      ];
      $links[] = [
        'title' => 'Close Business',
        'name' => 'Close Business',
        'weight' => 105,
        'ref' => 'new-note',
        'class' => 'no-popup',
        'url' => CRM_Utils_System::url('civicrm/close-business', 'bid=' . $objectId),
      ];
    }
    if (in_array('Member (Property Owner)', $subTypes) || empty($subTypes) || $contactType == 'Individual') {
      $links[] = [
        'title' => 'Become Member (Business)',
        'name' => 'Become Member (Business)',
        'weight' => 100,
        'ref' => 'new-note',
        'class' => 'no-popup',
        'url' => CRM_Utils_System::url('civicrm/add-business', 'bid=' . $objectId),
      ];
    }
  }
}

function biaproperty_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Activity_Form_ActivityLinks') {
    // hide activity type actions for all type of contacts
    $form->assign('activityTypes', []);
    CRM_Core_Resources::singleton()->addScript(
      "CRM.$(function($) {
        $('li.crm-activity-tab').hide();
      });"
    );
  }
  if ($formName == 'CRM_Biaproperty_Form_ExistingProperty') {
     $url = CRM_Utils_System::url('civicrm/property/form', ['reset' => 1, 'action' => 'add', 'context' => 'create'], FALSE, NULL, TRUE, FALSE, TRUE);
     CRM_Core_Resources::singleton()->addScript(
      "CRM.$(function($) {
        CRM.config.entityRef.links.Property = [
         {label: 'Add Property', url: '{$url}'}
        ];
     });
    ");
  }
  if ($formName == 'CRM_Biaproperty_Form_SellProperty') {
    $url = CRM_Utils_System::url('civicrm/profile/create', ['reset' => 1, 'action' => 'add', 'context' => 'dialog', 'gid' => 18], FALSE, NULL, TRUE, FALSE, TRUE);
    CRM_Core_Resources::singleton()->addScript(
     "CRM.$(function($) {
         CRM.config.entityRef.links.Contact = [];
         CRM.config.entityRef.links.Contact[0] = [];
         CRM.config.entityRef.links.Contact[0]['label'] = 'New Member (Property Owner)';
         CRM.config.entityRef.links.Contact[0]['url'] = '{$url}';
    });
   ");
  }

  if ($formName == 'CRM_Biaproperty_Form_AddBuisness' || $formName == 'CRM_Biaproperty_Form_Unit') {
    $url = CRM_Utils_System::url('civicrm/profile/create', ['reset' => 1, 'action' => 'add', 'context' => 'dialog', 'gid' => 17], FALSE, NULL, TRUE, FALSE, TRUE);
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
        CRM.config.entityRef.links.Contact = [];
        CRM.config.entityRef.links.Contact[0] = [];
        CRM.config.entityRef.links.Contact[0]['label'] = 'New Business';
        CRM.config.entityRef.links.Contact[0]['url'] = '{$url}';
      });
    ");
  }
  // Add in Address Entity Reference link
  if ($formName === 'CRM_Biaproperty_Form_Unit') {
    $url = CRM_Utils_System::url('civicrm/unit-address', ['reset' => 1, 'action' => 'add', 'context' => 'create', 'pid' => $form->getVar('_pid'), 'uid' => $form->getVar('_id')], FALSE, NULL, TRUE, FALSE, FALSE);
    CRM_Core_Resources::singleton()->addScript("
      CRM.$(function($) {
        CRM.config.entityRef.links.Address = [
          {label: 'Add unit address', url: '{$url}'}
        ];
      });"
    );
  }
  if ('CRM_Contact_Form_Contact' == $formName) {
    $options = $form->getVar('_editOptions');
    foreach ([
      'Members_Businesses_' => 'Unit',
      'Members_Property_Owners_' => 'Property',
    ] as $ctype => $entity) {
      if (strstr($form->_contactSubType, $ctype)) {
        if ($form->getVar('_contactId')) {
          continue;
        }
        if ($ctype === 'Members_Businesses_') {
          unset($options['Address']);
        }
        $options = [$entity => ts($entity)] + $options;
        $className = 'CRM_Contact_Form_Edit_' . $entity;
        $className::buildQuickForm($form);
        $form->setVar('_editOptions', $options);
        $form->assign('editOptions', $options);
      }
    }
  }
}

function biaproperty_civicrm_postProcess($formName, &$form) {
  if ('CRM_Contact_Form_Contact' == $formName) {
    $values = $form->controller->exportValues($form->getVar('_name'));
    $options = $form->getVar('_editOptions');
    if (!empty($form->_contactId) && in_array('Unit', $options)) {
      if (!empty($values['unit_id'])) {
        \Civi\Api4\UnitBusiness::create(FALSE)
          ->addValue('business_id', $form->_contactId)
          ->addValue('unit_id', $values['unit_id'])
          ->execute();
        \Civi\Api4\Unit::update(FALSE)->addValue('unit_status', 1)->addWhere('id', '=',  $values['unit_id'])->execute();
      }
    }
    if (!empty($form->_contactId) && in_array('Property', $options)) {
      if (!empty($values['is_voter']) && $values['is_voter'] == 1) {
        // if the owner is going to be primary voter then clear all previous is_voter values to 0/No
        CRM_Core_DAO::executeQuery('UPDATE civicrm_property_owner SET is_voter = 0 WHERE is_voter = 1 AND property_id = ' . $values['property_id']);
      }
      if (!empty($values['property_id'])) {
        \Civi\Api4\PropertyOwner::create(FALSE)
          ->addValue('property_id', $values['property_id'])
          ->addValue('owner_id', $form->_contactId)
          ->addValue('is_voter', $values['is_voter'])
          ->execute();
      }
    }
  }
  if ($formName == 'CRM_Contact_Form_Task_Delete' && !empty($form->_contactIds)) {
    $businessContacts = \Civi\Api4\UnitBusiness::get(FALSE)->addWhere('business_id', 'IN', $form->_contactIds)->execute();
    foreach ($businessContacts as $businessContact) {
      \Civi\Api4\UnitBusiness::delete(FALSE)
      ->addWhere('id', '=', $businessContact['id'])
      ->execute();

      \Civi\Api4\Activity::create(FALSE)
        ->addValue('activity_type_id:name', 'Business closed')
        ->addValue('target_contact_id', $businessContact['business_id'])
        ->addValue('assignee_contact_id', $businessContact['business_id'])
        ->addValue('source_contact_id', CRM_Core_Session::getLoggedInContactID())
        ->addValue('status_id:name', 'Completed')
        ->addValue('subject', 'Business closed')
        ->execute();


      // if no business account found then change the unit status to Vacant
      if (\Civi\Api4\UnitBusiness::get(FALSE)->addWhere('unit_id', '=', $businessContact['unit_id'])->execute()->count() == 0) {
        \Civi\Api4\Unit::update(FALSE)->addValue('unit_status', 2)->addWhere('id', '=', $businessContact['unit_id'])->execute();
      }
    }
  }
}

function biaproperty_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {
  if ($formName == 'CRM_Contact_Form_Task_Delete') {
    $addresses = \Civi\Api4\PropertyOwner::get(FALSE)
      ->addSelect('GROUP_CONCAT(DISTINCT property_id.property_address) AS addresses')
      ->addWhere('owner_id', 'IN', $form->_contactIds)
      ->execute()->first()['addresses'];
    if (!empty($addresses)) {
      $text = count($addresses) === 1 ? 'property' : 'properties';
      $errors['_qf_default'] = E::ts('Please transfer the %1 %2 to a different contact prior to deleting them.', [1 => $text, 2 => implode($addresses)]);
    }
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
