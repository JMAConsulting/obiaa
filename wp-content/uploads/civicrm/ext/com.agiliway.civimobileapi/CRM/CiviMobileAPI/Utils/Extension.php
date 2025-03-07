<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

/**
 * Class provide extension helper methods
 */
class CRM_CiviMobileAPI_Utils_Extension {

  const LATEST_SUPPORTED_CIVICRM_VERSION = 5.38;
  const MINIMAL_REQUIRED_CIVIMOBILE_APP_VERSION = '6.0.0';
  
  /**
   * Is extension folder is writable
   *
   * @return float
   */
  public static function directoryIsWritable() {
    $extensionPath = CRM_Core_Config::singleton()->extensionsDir . CRM_CiviMobileAPI_ExtensionUtil::LONG_NAME;

    return is_writable(CRM_Core_Config::singleton()->extensionsDir) && is_writable_r($extensionPath);
  }

  /**
   * Returns current extension path
   * Be careful when move this method
   *
   * @return bool|string
   */
  public static function getCurrentExtensionPath() {
    return realpath(__DIR__ . '/../../../');
  }

  /**
   * Returns current extension name
   *
   * @return string
   */
  public static function getCurrentExtensionName() {
    $path = static::getCurrentExtensionPath();
    $separatedPath = explode('/', $path);

    return end($separatedPath);
  }

  /**
   * Checks if is correct extension name
   *
   * @return bool
   */
  public static function isCorrectExtensionName() {
    return static::getCurrentExtensionName() == CRM_CiviMobileAPI_ExtensionUtil::LONG_NAME;
  }

  /**
   * Is allow public info api
   *
   * @return int
   */
  public static function isAllowPublicInfoApi() {
    return Civi::settings()->get('civimobile_is_allow_public_info_api') == 1 ? 1 : 0;
  }
  
  public static function isAllowResetPassword() {
    return Civi::settings()->get('civimobile_is_allow_reset_password') == 1 ? 1 : 0;
  }

  /**
   * Is a Custom Application
   *
   * @return int
   */
  public static function isCustomApp() {
    return (Civi::settings()->get('civimobile_is_custom_app') == 1) ? 1 : 0;
  }

  /**
   * Show a Website URL QR-code for Anonymous users
   *
   * @return int
   */
  public static function isAllowPublicWebisteURLQRCode() {
    return (Civi::settings()->get('civimobile_is_allow_public_website_url_qrcode') == 1) ? 1 : 0;
  }

  /**
   * Is allow public info
   *
   * @return int
   */
  public static function isAllowCmsRegistration() {
    $config = CRM_Core_Config::singleton();

    return (int)($config->userSystem->isUserRegistrationPermitted()
      && Civi::settings()->get('civimobile_is_allow_registration'));
  }

  /**
   * Get site name from settings
   *
   * @return string|null
   */
  public static function getSiteName() {
    if (Civi::settings()->get('civimobile_site_name_to_use') == 'custom_site_name') {
      return Civi::settings()->get('civimobile_custom_site_name');
    }

    return CRM_CiviMobileAPI_Utils_Cms::getSiteName();
  }

  /**
   * Has extension right folder name?
   *
   * @return bool
   */
  public static function hasExtensionRightFolderName() {
    return file_exists(E::path('civimobileapi.php'));
  }

  /**
   * Is showed events in public area
   *
   * @return int
   */
  public static function isShowedEventsInPublicArea() {
    $enabledComponents = CRM_CiviMobileAPI_Utils_CiviCRM::getEnabledComponents();
    return (Civi::settings()->get('civimobile_is_allow_public_info_api') == 1 && in_array('CiviEvent', $enabledComponents)) ? 1 : 0;
  }

  public static function isShowedDonationsInPublicArea() {
    $enabledComponents = CRM_CiviMobileAPI_Utils_CiviCRM::getEnabledComponents();
    return (Civi::settings()->get('civimobile_is_allow_public_info_api') == 1 && in_array('CiviContribute', $enabledComponents)) ? 1 : 0;
  }

  /**
   * Is showed news
   *
   * @return int
   */
  public static function isShowedNews() {
    return (Civi::settings()->get('civimobile_is_showed_news') == 1) ? 1 : 0;
  }

  /**
   * Returns news RSS feed url
   *
   * @return int
   */
  public static function newsRssFeedUrl() {
    return empty(Civi::settings()->get('civimobile_news_rss_feed_url')) ? '' : Civi::settings()->get('civimobile_news_rss_feed_url');
  }

  /**
   * Is allow public info api
   *
   * @return int
   */
  public static function isShowedNewsInPublicArea() {
    return (Civi::settings()->get('civimobile_is_allow_public_info_api') == 1 && Civi::settings()->get('civimobile_is_showed_news') == 1) ? 1 : 0;
  }

  /**
   * Is CiviCRM version supported
   *
   * @return bool
   */
  public static function isCiviCRMSupportedVersion() {
    return CRM_CiviMobileAPI_Utils_Extension::LATEST_SUPPORTED_CIVICRM_VERSION <= CRM_Utils_System::version();
  }

  /**
   * Is Server key valid
   *
   * @return bool
   */
  public static function isServerKeyValid() {
    return Civi::settings()->get('civimobile_is_server_key_valid') == 1;
  }

  /**
   * Is curl extension enabled
   */
  public static function isCurlExtensionEnabled() {
    return in_array('curl', get_loaded_extensions());
  }

  /**
   * Is Time Tracker extension enabled
   */
  public static function isTimeTrackerExtensionEnabled() {
    return CRM_CiviMobileAPI_Utils_TimeTracker::isTimeTrackerInstalled() ? 1 : 0;
  }

  /**
   * Is CiviAppointment extension enabled
   */
  public static function isCiviAppointmentExtensionEnabled() {
    return CRM_CiviMobileAPI_Utils_CiviAppointment::isCiviAppointmentInstalled() ? 1 : 0;
  }
  
  /**
   *  Sets cookie to hide QR popup
   */
  public static function hideCiviMobileQrPopup() {
    setcookie('civimobile_popup_close', true, 0, '/');
    $_COOKIE["civimobile_popup_close"] = true;
  }

  /**
   * Returns active tabs for CiviMobile applications
   *
   * @return array
   */
  public static function getActiveCiviMobileTabs() {
    try {
      $tabs = civicrm_api3('OptionValue', 'get', [
          'sequential' => 1,
          'option_group_id' => CRM_CiviMobileAPI_Install_Entity_OptionGroup::TABS,
          'is_active' => 1,
          'options' => ['limit' => 0, 'sort' => "weight ASC"],
      ])['values'];
    } catch (Exception $e){
      return [];
    }

    $preparedTabs = [];

    foreach ($tabs as $tab) {
      if ($tab['name'] === 'civi_mobile_tab_appointment' && !($tab['is_active']
        && (CRM_Core_Permission::check('access CiviAppointment') ? 1 : 0))) {
        unset($tab);
        continue;
      }
      else if ($tab['name'] === 'civi_mobile_tab_time_tracker' && !($tab['is_active']
          && (CRM_Core_Permission::check('access TimeTracker') ? 1 : 0))) {
        unset($tab);
        continue;
      }

      $preparedTabs[] = [
        'label' => $tab['label'],
        'name' => $tab['name'],
        'is_active' => $tab['is_active'],
        'weight' => $tab['weight'],
      ];
    }

    return $preparedTabs;
  }

}
