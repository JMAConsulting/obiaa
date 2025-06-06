<?php

/**
 * Class retrieve 'rest path' for CiviCRM API
 */
class CRM_CiviMobileAPI_Utils_RestPath {

  /**
   * Gets 'rest path' for CiviCRM API
   */
  public function get() {
    $currentCMS = CRM_CiviMobileAPI_Utils_CmsUser::getInstance()->getSystem();
    $restPath = $this->getStandardRestPath();

    if ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_WORDPRESS) {
      $restPath = $this->getWordpressRestPath();
    }

    if ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_JOOMLA) {
      $restPath = $this->getJoomlaRestPath();
    }

    if ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_DRUPAL8 || $currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_STANDALONE) {
      $restPath = $this->getDrupal8RestPath();
    }

    return $restPath;
  }

  /**
   * Gets standard 'rest path' for CiviCRM API
   *
   * @return mixed
   */
  private function getStandardRestPath() {
    return Civi::paths()->getUrl("[civicrm.root]/extern/rest.php");
  }

  /**
   * Gets Wordpress 'rest path' for CiviCRM API
   *
   * @return string
   */
  private function getWordpressRestPath() {
    $restPath = $this->getStandardRestPath();
    $endpoint = $this->getWordpressApiEndpoint();

    if (!empty($endpoint) && function_exists('get_rest_url')) {
      $restPath = str_replace(home_url(), '', get_rest_url()) . $endpoint;
    }

    return $restPath;
  }

  /**
   * Gets Drupal8+ 'rest path' for CiviCRM API
   *
   * @return string
   */
  private function getDrupal8RestPath() {
    return Civi::paths()->getUrl("civicrm/ajax/rest");
  }

  /**
   * Is 'civicrm-wp-rest' plugin active
   *
   * @return bool
   */
  public function isWpRestPluginActive() {
    if (CRM_CiviMobileAPI_Utils_CmsUser::getInstance()->getSystem() !== CRM_CiviMobileAPI_Utils_CmsUser::CMS_WORDPRESS) {
      return false;
    }

    include_once(ABSPATH . 'wp-admin/includes/plugin.php');

    if (function_exists('is_plugin_active')) {
      $pathPlugin = 'civicrm-wp-rest/civicrm-wp-rest.php';
      if (is_plugin_active($pathPlugin)) {
        return true;
      }
    }

    return false;
  }

  /**
   * Gets Joomla 'rest path' for CiviCRM API
   *
   * Information about civicrm/ajax/rest
   * https://docs.civicrm.org/dev/en/latest/api/v3/rest/
   *
   * @return string
   */
  private function getJoomlaRestPath() {
    if (version_compare('5.47', CRM_Utils_System::version(), '<=')) {
      return "/index.php?option=com_civicrm&task=civicrm/ajax/rest";
    }

    return '/administrator' . str_replace('/administrator', '', Civi::paths()->getUrl("[civicrm.root]/extern/rest.php"));
  }

  /**
   * Returns absolute rest URL
   *
   * @return string
   */
  public function getAbsoluteUrl() {
    $currentCMS = CRM_CiviMobileAPI_Utils_CmsUser::getInstance()->getSystem();
    $restPath = $this->getStandardAbsoluteUrl();

    if ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_WORDPRESS) {
      $restPath = $this->getWordpressAbsoluteUrl();
    }

    if ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_JOOMLA) {
      $restPath = $this->getJoomlaAbsoluteUrl();
    }

    if ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_DRUPAL8 || $currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_STANDALONE) {
      $restPath = $this->getDrupal8AbsoluteUrl();
    }

    return $restPath;
  }

  /**
   * Returns standard absolute rest URL
   *
   * @return string
   */
  private function getStandardAbsoluteUrl() {
    $config = CRM_Core_Config::singleton();
    return $config->userFrameworkResourceURL . 'extern/rest.php';
  }

  /**
   * Returns Wordpress absolute rest URL
   *
   * @return string
   */
  private function getWordpressAbsoluteUrl() {
    $restUrl = $this->getStandardAbsoluteUrl();
    $endpoint = $this->getWordpressApiEndpoint();

    if (!empty($endpoint)) {
      if (function_exists('get_rest_url')) {
        $restUrl = get_rest_url() . $endpoint;
      }
    }

    return $restUrl;
  }

  /**
   * Gets Drupal8+ absolute rest URL
   *
   * @return string
   */
  private function getDrupal8AbsoluteUrl() {
    return Civi::paths()->getUrl("civicrm/ajax/rest", 'absolute');
  }

  /**
   * Returns Joomla absolute rest URL
   *
   * @return string
   */
  private function getJoomlaAbsoluteUrl() {
    return JUri::root() . substr($this->getJoomlaRestPath(), 1);
  }

  /**
   * Returns endpoint for Wordpress
   *
   * @return string
   */
  private function getWordpressApiEndpoint() {
    $endpoint = '';

    if ($this->isWpRestPluginActive()) {
      $endpoint = 'civicrm/v3/rest';
    }

    if (class_exists('CiviCRM_WP_REST\Controller\Rest')) {
      $restController = new CiviCRM_WP_REST\Controller\Rest();
      if (method_exists($restController, 'get_endpoint')) {
        $endpoint = substr((string)$restController->get_endpoint(), 1);
      }
    }

    return $endpoint;
  }

  /**
   * Returns absolute url path for API V4
   *
   * @return string
   */
  public function getAbsoluteUrlApiV4() {
    $currentCMS = CRM_CiviMobileAPI_Utils_CmsUser::getInstance()->getSystem();
    $url = CRM_Utils_System::url('civicrm/ajax/api4', NULL, TRUE, NULL, FALSE);

    if ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_JOOMLA) {
      $url = preg_replace('/[?&]Itemid=\d+\/?/', '', $url);
    }

    return rtrim($url, '/');
  }

}
