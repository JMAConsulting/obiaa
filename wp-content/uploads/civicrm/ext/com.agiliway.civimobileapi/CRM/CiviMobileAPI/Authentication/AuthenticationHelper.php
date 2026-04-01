<?php

use CRM_CiviMobileAPI_Utils_CmsUser as CmsUser;
use CRM_CiviMobileAPI_Utils_JsonResponse as JsonResponse;
use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_Authentication_AuthenticationHelper {

  /**
   * Checks if Request is valid
   *
   * @return bool
   */
  public static function isRequestValid() {
    return self::validateCms();
  }

  /**
   * Checks if CMS is valid
   *
   * @return bool
   */
  private static function validateCms() {
    if (CmsUser::getInstance()->validateCMS()) {
      return TRUE;
    } else {
      JsonResponse::sendErrorResponse(E::ts('Sorry, but CiviMobile are not supporting your system yet.'), NULL, 'cms_not_supported');
      return FALSE;
    }
  }

}
