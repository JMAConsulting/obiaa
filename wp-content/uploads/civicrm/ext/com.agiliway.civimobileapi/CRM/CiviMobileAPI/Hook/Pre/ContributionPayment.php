<?php

class CRM_CiviMobileAPI_Hook_Pre_ContributionPayment {

  public static function run($formName, &$form) {
    if ($formName == 'CRM_Contribute_Form_Contribution_Main' && CRM_CiviMobileAPI_Hook_Utils::is_mobile_request()) {
      if (!CRM_Utils_System::authenticateKey(FALSE)) {
        Civi::log()->warning("Contributor's authorization failed: Failed to authenticate key");
        return;
      }

      $store = NULL;
      $apiKey = CRM_Utils_Request::retrieve('api_key', 'String', $store, FALSE, NULL, 'REQUEST');
      if (empty($apiKey)) {
        Civi::log()->warning("Contributor's authorization failed: Mandatory param 'api_key' (user key) missing");
        return;
      }
      $contactId = CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_Contact', $apiKey, 'id', 'api_key');

      if (!empty($contactId)) {
        $session = CRM_Core_Session::singleton();
        $session->set('userID', $contactId);
      }
      else {
        Civi::log()->warning("Contributor's authorization failed: No CMS user associated with given api-key");
      }
    }
  }
}
