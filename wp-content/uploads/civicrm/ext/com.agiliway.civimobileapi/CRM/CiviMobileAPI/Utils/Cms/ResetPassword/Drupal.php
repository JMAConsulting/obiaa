<?php

class CRM_CiviMobileAPI_Utils_Cms_ResetPassword_Drupal {
  
  /**
   * @return bool
   */
  public static function resetPassword($email) {
    $account = user_load_by_mail($email);
    
    if (!$account) {
      return FALSE;
    }
    
    return _user_mail_notify('password_reset', $account);
  }
}