<?php

/**
 * Gives you the ability to work with CMS
 */
class CRM_CiviMobileAPI_Utils_Cms {

  /**
   * Returns site`s name in different CMS`
   *
   * @return string|null
   */
  public static function getSiteName() {
    $currentCMS = CRM_CiviMobileAPI_Utils_CmsUser::getInstance()->getSystem();

    if ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_WORDPRESS) {
      return get_bloginfo('name');
    } elseif ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_JOOMLA) {
      return JFactory::getConfig()->get('sitename');
    } elseif ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_DRUPAL6 || $currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_DRUPAL7) {
      return variable_get('site_name', '');
    }
    elseif ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_DRUPAL8) {
      return \Drupal::config('system.site')->get("name");
    }

    return null;
  }

  /**
   * Returns default rss url
   *
   * @return string
   */
  public static function getCmsRssUrl() {
    $currentCMS = CRM_CiviMobileAPI_Utils_CmsUser::getInstance()->getSystem();
    $config = CRM_Core_Config::singleton();

    if ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_WORDPRESS && function_exists('get_feed_link')) {
      return get_feed_link('rss2');
    } elseif ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_JOOMLA) {
      return str_replace("/administrator/", "/", $config->userFrameworkBaseURL) . "?format=feed&type=rss";
    }
    elseif ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_DRUPAL6 || $currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_DRUPAL7 || $currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_DRUPAL8) {
      return $config->userFrameworkBaseURL . "rss.xml";
    }

    return '';
  }

  /**
   * @return string
   */
  public static function getCMSVersion() {
    $currentCMS = CRM_CiviMobileAPI_Utils_CmsUser::getInstance()->getSystem();

    if ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_DRUPAL6 || $currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_DRUPAL7) {
      return VERSION;
    } elseif ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_WORDPRESS && function_exists('get_bloginfo')) {
      return get_bloginfo('version');
    } elseif ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_JOOMLA) {
      return defined('Joomla\CMS\Version::RELEASE') ? Joomla\CMS\Version::RELEASE : '';
    }

    return '';
  }

  /**
   * @return int
   */
  public static function getTimeZoneUTCOffset() {
    return date('Z') / 3600;
  }

}
