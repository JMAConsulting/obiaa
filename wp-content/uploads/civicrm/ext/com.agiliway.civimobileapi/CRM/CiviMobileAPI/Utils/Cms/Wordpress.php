<?php

class CRM_CiviMobileAPI_Utils_Cms_Wordpress {

  /**
   * @return string[]
   */
  public static function getConflictPlugins() {
    return [
      'wp-security-hardening/wp-hardening.php' => 'WP Hardening',
      'better-wp-security/better-wp-security.php' => 'iThemes Security'
    ];
  }

}