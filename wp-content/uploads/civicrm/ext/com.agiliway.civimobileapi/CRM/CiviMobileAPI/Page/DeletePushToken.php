<?php

use CRM_CiviMobileAPI_Utils_Request as Request;
use CRM_CiviMobileAPI_Utils_CmsUser as CmsUser;
use CRM_CiviMobileAPI_Utils_JsonResponse as JsonResponse;
use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_Page_DeletePushToken extends CRM_Core_Page {

  public function run() {
    $contactId = Request::getInstance()->post('contact_id', 'String');

    if (!isset($contactId) || empty($contactId)) {
      JsonResponse::sendErrorResponse(E::ts('Wrong contact_id'), 'contact_id');
    }

    $token = Request::getInstance()->post('token', 'String');

    if (!isset($token) || empty($token)) {
      JsonResponse::sendErrorResponse(E::ts('Wrong token'), 'token');
    }

    $pushNotification = new CRM_CiviMobileAPI_BAO_PushNotification();
    $pushNotification->token = $token;
    $pushNotification->contact_id = $contactId;

    $pushNotification->find(TRUE);
    if (!isset($pushNotification->id) || empty($pushNotification->id)) {
      JsonResponse::sendErrorResponse(E::ts("Token doesn't exist"), 'token_id');
    }

    CRM_CiviMobileAPI_BAO_PushNotification::del($pushNotification->id);

    JsonResponse::sendSuccessResponse();
  }

}
