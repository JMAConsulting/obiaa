<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_Form_Dashboard extends CRM_Core_Form {

  /**
   * @throws \CRM_Core_Exception
   * @throws \Exception
   */
  public function preProcess() {
    parent::preProcess();

    $cid = CRM_Utils_Request::retrieve('cid', 'Integer');

    if (!($cid == CRM_Core_Session::singleton()->getLoggedInContactID() || CRM_Core_Permission::check('administer CiviCRM'))) {
      throw new Exception('Permission denied');
    }

    $this->add('hidden', 'cid', $cid);

    $this->assign('isLoggedInContactForm', CRM_Core_Session::singleton()->getLoggedInContactID() == $cid);
  }

  /**
   * Build the form object.
   *
   * @return void
   */
  public function buildQuickForm() {
    parent::buildQuickForm();
    $cid = CRM_Utils_Request::retrieve('cid', 'Integer');

    $logoutBtnAttrs = [];
    if (!CRM_CiviMobileAPI_Utils_Contact::isContactHasApiKey($cid)) {
      $logoutBtnAttrs['disabled'] = 'disabled';
    }

    $this->addButtons([
      [
        'type' => 'submit',
        'name' => E::ts('Logout from mobile'),
        'isDefault' => TRUE,
        'js' => $logoutBtnAttrs
      ]
    ]);

    $this->addElement('checkbox', 'civimobile_show_qr_popup', E::ts('Show a Website URL QR-code for me'));
  }

  /**
   * @throws \CiviCRM_API3_Exception
   */
  public function postProcess() {
    $params = $this->exportValues();

    CRM_CiviMobileAPI_Utils_Contact::logoutFromMobile($params['cid']);
  }

  /**
   * Set defaults for form.
   */
  public function setDefaultValues() {
    $defaults = [];

    $defaults['civimobile_show_qr_popup'] = !$_COOKIE["civimobile_popup_close"];

    return $defaults;
  }

}
