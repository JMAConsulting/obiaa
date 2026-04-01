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
   * CiviCrm contact
   *
   * @var \CRM_Contact_BAO_Contact
   */
  public $contact;

  /**
   * CRM_CiviMobileAPI_Page_Auth constructor.
   */
  public function __construct() {
    CRM_CiviMobileAPI_Hook_Utils::civimobileapi_secret_validation();

    $this->emailOrUsername = $this->getEmailOrUsername();
    $this->password = $this->getPassword();
    $this->contact = (new CRM_CiviMobileAPI_Authentication_CiviMobileAuthX())->authenticate($this->emailOrUsername, $this->password);

    if (!$this->contact) {
      JsonResponse::sendErrorResponse(E::ts('Wrong email or password'), NULL, 'wrong_credentials');
    }

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
