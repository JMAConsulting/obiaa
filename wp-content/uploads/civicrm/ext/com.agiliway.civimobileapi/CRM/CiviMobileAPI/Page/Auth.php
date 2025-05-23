<?php

use CRM_CiviMobileAPI_Utils_Request as Request;
use CRM_CiviMobileAPI_Utils_JsonResponse as JsonResponse;
use CRM_CiviMobileAPI_ExtensionUtil as E;

/**
 * Provides authentication functionality for CiviMobile application
 */
class CRM_CiviMobileAPI_Page_Auth extends CRM_Core_Page {

  /**
   * Email or Username sent in request
   *
   * @var string
   */
  public $emailOrUsername;

  /**
   * Password sent in request
   *
   * @var string
   */
  public $password;

  /**
   * Drupal Id which related to email and password
   *
   * @var int
   */
  public $contactId;

  /**
   * CiviCrm contact assigns to drupal contact
   *
   * @var \CRM_Contact_BAO_Contact
   */
  public $civiContact;
  //login
  /**
   * CRM_CiviMobileAPI_Page_Auth constructor.
   */
  public function __construct() {
    CRM_CiviMobileAPI_Hook_Utils::civimobileapi_secret_validation();

    $this->emailOrUsername = $this->getEmailOrUsername();
    $this->password = $this->getPassword();
    $this->contactId = CRM_CiviMobileAPI_Authentication_AuthenticationHelper::getUserIdByMailAndPassword($this->emailOrUsername, $this->password);

    if (CRM_CiviMobileAPI_Authentication_AuthenticationHelper::isUserBlocked($this->emailOrUsername)) {
      JsonResponse::sendErrorResponse('User is blocked', 'email', 'cms_user_is_blocked');
    }

    $this->civiContact = CRM_CiviMobileAPI_Authentication_AuthenticationHelper::getCiviContact($this->contactId);

    parent::__construct();
  }

  /**
   * Gets email from request
   *
   * @return string|null
   */
  private function getEmailOrUsername() {
    $emailOrUsername = Request::getInstance()->post('email', 'String');
    if (!$emailOrUsername) {
      JsonResponse::sendErrorResponse(E::ts('Required field'), 'email');
    }

    return $emailOrUsername;
  }

  /**
   * Gets password from request
   *
   * @return string|null
   */
  private function getPassword() {
    $password = Request::getInstance()->post('password', 'String');
    if (!$password) {
      JsonResponse::sendErrorResponse(E::ts('Required field'), 'password');
    }

    return $password;
  }

  /**
   * Checks If request is valid and launch preparing user data
   */
  public function run() {
    if (CRM_CiviMobileAPI_Authentication_AuthenticationHelper::isRequestValid()) {
      (new CRM_CiviMobileAPI_Authentication_Login($this))->run();
    }
  }

}
