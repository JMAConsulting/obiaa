<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_Hook_Tabset_CiviMobile {

  public static function run($tabsetName, &$tabs, $context) {
    if ($tabsetName == 'civicrm/contact/view' && !empty($context['contact_id'])) {
      if (CRM_Contact_BAO_Contact::getContactType($context['contact_id']) == 'Individual' &&
        (CRM_Core_Permission::check('administer CiviCRM') || CRM_Core_Session::singleton()->getLoggedInContactID() == $context['contact_id'])
      ) {
        $tabs[] = [
          'id' => 'civimobile',
          'url' => CRM_Utils_System::url('civicrm/civimobile/dashboard', 'reset=1&cid=' . $context['contact_id']),
          'title' => E::ts('CiviMobile'),
          'weight' => 99,
        ];
      }
    }
  }
}
