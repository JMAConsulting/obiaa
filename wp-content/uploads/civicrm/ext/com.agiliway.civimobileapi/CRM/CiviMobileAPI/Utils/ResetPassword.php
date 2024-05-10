<?php

class CRM_CiviMobileAPI_Utils_ResetPassword {
  
  public static function resetPassword($email) {
    $currentCMS = CRM_CiviMobileAPI_Utils_CmsUser::getInstance()->getSystem();
    
    if ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_DRUPAL7
      || $currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_DRUPAL8) {
      return CRM_CiviMobileAPI_Utils_Cms_ResetPassword_Drupal::resetPassword($email);
    }
    
    if ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_WORDPRESS) {
      return CRM_CiviMobileAPI_Utils_Cms_ResetPassword_Wordpress::resetPassword($email);
    }
    
    if ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_JOOMLA) {
      return CRM_CiviMobileAPI_Utils_Cms_ResetPassword_Joomla::resetPassword($email);
    }
    
    return FALSE;
  }
}
