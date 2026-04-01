<?php
/**
 * Gives you the ability to work with CMS accounts
 */

class CRM_CiviMobileAPI_Utils_CmsUser {

  /**
   * Drupal 8 CMS
   */
  const CMS_DRUPAL8 = 'Drupal8';

  /**
   * Drupal 7 CMS
   */
  const CMS_DRUPAL7 = 'Drupal';

  /**
   * Drupal 6 CMS
   */
  const CMS_DRUPAL6 = 'Drupal6';

  /**
   * WordPress CMS
   */
  const CMS_WORDPRESS = 'WordPress';

  /**
   * Joomla CMS
   */
  const CMS_JOOMLA = 'Joomla';

  /**
   * Backdrop CMS
   */
  const CMS_BACKDROP = 'Backdrop';

  const CMS_STANDALONE = 'Standalone';

  /**
   * Singleton pattern
   */
  private static $instance;

  /**
   * Current CMS system
   */
  private $system;

  private function __construct() {
    $this->system = defined('CIVICRM_UF') ? CIVICRM_UF : '';
  }

  private function __clone() {}

  /**
   * Gets instance of CRM_CiviMobileAPI_Utils_CmsUser
   *
   * @return \CRM_CiviMobileAPI_Utils_CmsUser
   */
  public static function getInstance() {
    if (self::$instance === NULL) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  /**
   * Validate CMS
   *
   * @return bool
   */
  public function validateCMS() {
    return in_array($this->system, [
      self::CMS_DRUPAL8,
      self::CMS_DRUPAL7,
      self::CMS_WORDPRESS,
      self::CMS_JOOMLA,
      self::CMS_STANDALONE,
    ]);
  }

  /**
   * @return array|false|mixed|string
   */
  public function getSystem() {
    return $this->system;
  }

}
