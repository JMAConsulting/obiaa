<?php

class CRM_CiviMobileAPI_Hook_BuildForm_ContributionPayment {

  /**
   * @param $formName
   * @param $form
   * @throws CRM_Core_Exception
   * @throws api_Exception
   */
  public function run($formName, &$form) {
    $session = CRM_Core_Session::singleton();

    $customizeForms = [
      'CRM_Contribute_Form_Contribution_Main',
      'CRM_Contribute_Form_Contribution_Confirm',
      'CRM_Contribute_Form_Contribution_ThankYou'
    ];

    if ($formName == 'CRM_Contribute_Form_Contribution_Main' && CRM_Utils_Request::retrieve('civimobile', 'Integer')) {
      CRM_CiviMobileAPI_Utils_Extension::hideCiviMobileQrPopup();
      $session->set('contribution_is_civimobile', 1);
    }

    if ($session->get('contribution_is_civimobile') && in_array($formName, $customizeForms)) {
      $this->customizeContributionPayment();
    }

    if (($formName != 'CRM_Contribute_Form_Contribution_Main' && $formName != 'CRM_Contribute_Form_Contribution_Confirm' && $formName != 'CRM_Financial_Form_Payment')
      || $formName == 'CRM_Contribute_Form_Contribution_ThankYou') {
      $isCivimobile = $session->get('contribution_is_civimobile');

      if ($isCivimobile) {
        $session->set('contribution_is_civimobile', NULL);
      }
    }

  }

  /**
   * Include scripts and styles to Contribution page
   *
   * @throws CRM_Core_Exception
   */
  private function customizeContributionPayment() {
    $session = CRM_Core_Session::singleton();
    $isCivimobile = $session->get('contribution_is_civimobile');

    if ($isCivimobile) {
      $template = CRM_Core_Smarty::singleton();

      $absURL = Civi::paths()->getUrl('[civicrm.root]/', 'absolute');

      $template->assign('absURL', $absURL);
      $template->assign('buttonColor', (!empty(Civi::settings()->get('civimobile_event_registration_button_color'))) ? Civi::settings()->get('civimobile_event_registration_button_color') : "#5589B7");

      CRM_Core_Region::instance('page-body')->add([
        'template' => 'CRM/CiviMobileAPI/CustomizeContributionPayment.tpl',
      ]);
    }
  }

}
