<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_Utils_Checklist {

  /**
   * @var array
   */
  private $checkedItems = [];

  /**
   * @var array
   */
  private $systemInfo = [];

  /**
   * @var array
   */
  private $infoItems = [];

  /**
   * Returns all checked items in object
   *
   * @return array
   */
  public function getCheckedItemsResult() {
    return $this->checkedItems;
  }

  /**
   * Calls all available methods to check
   * Runs all methods which name starts from '_check'
   */
  public function checkAllAvailableItems() {
    $classMethods = get_class_methods($this);

    foreach ($classMethods as $method) {
      if (preg_match('/^_check/', $method)) {
        $this->$method();
      }
    }
  }

  /**
   * Calls all available system info methods and returns report
   * Runs all methods which name starts from '_si'(System Info)
   *
   * @return array
   */
  public function getSystemInfoReport() {
    $classMethods = get_class_methods($this);

    foreach ($classMethods as $method) {
      if (preg_match('/^_si/', $method)) {
        $this->$method();
      }
    }

    return $this->systemInfo;
  }

  /**
   * Calls all available info methods and returns report
   * Runs all methods which name starts from '_info'
   *
   * @return array
   */
  public function getInfoItems() {
    $classMethods = get_class_methods($this);

    foreach ($classMethods as $method) {
      if (preg_match('/^_info/', $method)) {
        $this->$method();
      }
    }

    return $this->infoItems;
  }

  /**
   * Checks Extension Version
   */
  public function _checkExtensionVersion() {
    $version = CRM_CiviMobileAPI_Utils_VersionController::getInstance();
    $isOlderVersion = $version->isCurrentVersionLowerThanRepositoryVersion();
    $this->checkedItems['latest_version']['title'] = 'Do you have last extension version?';

    if ($isOlderVersion) {
      $this->checkedItems['latest_version']['message'] = E::ts('You are using CiviMobileAPI <strong>%1</strong>. The latest version is CiviMobileAPI <strong>%2</strong>', [
        1 => 'v' . $version->getCurrentFullVersion(),
        2 => 'v' . $version->getLatestFullVersion(),
      ]);
      $this->checkedItems['latest_version']['status'] = 'warning';
    } else {
      $this->checkedItems['latest_version']['message'] = E::ts('Your extension version is up to date - CiviMobile <strong>%1</strong>', [1 => 'v' . $version->getCurrentFullVersion()]);
      $this->checkedItems['latest_version']['status'] = 'success';
    }
  }

  /**
   * Checks CiviCRM Supported version
   */
  public function _checkCiviCRMSupportedVersion() {
    $isCiviCRMSupported = CRM_CiviMobileAPI_Utils_Extension::isCiviCRMSupportedVersion();
    $this->checkedItems['is_civicrm_version_supported'] = [
      'title' => 'Is CiviCRM version supported by CiviMobileAPI?',
      'message' => $isCiviCRMSupported ? 'Your CiviCRM version is supported' : 'You should to install CiviCRM with minimum version ' . CRM_CiviMobileAPI_Utils_Extension::LATEST_SUPPORTED_CIVICRM_VERSION,
      'status' => $isCiviCRMSupported ? 'success' : 'warning',
    ];
  }

  /**
   * Checks Is valid Extension folder name
   */
  public function _checkExtensionFolderName() {
    $isRightExtensionFolderName = CRM_CiviMobileAPI_Utils_Extension::hasExtensionRightFolderName();

    $this->checkedItems['is_civimobile_ext_has_right_folder_name'] = [
      'title' => 'Is CivimobileAPI`s folder name correct?',
      'message' => $isRightExtensionFolderName ? 'Folder name is correct.' : 'You should rename CivimobileAPI extension folder to <b>"' . CRM_CiviMobileAPI_ExtensionUtil::LONG_NAME . '"</b> and then reinstall extension.',
      'status' => $isRightExtensionFolderName ? 'success' : 'error',
    ];
  }

  /**
   * Checks is extension folder writable
   */
  public function _checkIsExtensionFolderWritable() {
    if (CRM_CiviMobileAPI_Utils_Extension::hasExtensionRightFolderName()) {
      $isDirectoryWritable = CRM_CiviMobileAPI_Utils_Extension::directoryIsWritable();
      $this->checkedItems['is_directory_writable'] = [
        'title' => 'Is CiviMobileAPI extension directory writable?',
        'message' => $isDirectoryWritable ? 'Extension directory is writable.' : 'Please give permissions to write to CiviMobileAPI extension directory.',
        'status' => $isDirectoryWritable ? 'success' : 'warning',
      ];
    }
  }

  /**
   * Checks is additional Wordpress plugin installed
   */
  public function _checkIsAdditionWpRestPluginInstalled() {
    if (CRM_CiviMobileAPI_Utils_CmsUser::getInstance()->getSystem() == CRM_CiviMobileAPI_Utils_CmsUser::CMS_WORDPRESS) {
      $this->checkedItems['is_wp_rest_plugin_active']['title'] = 'Do you have the additional plugin for Wordpress?';

      $isWpRestPluginActive = (new CRM_CiviMobileAPI_Utils_RestPath())->isWpRestPluginActive();

      if (version_compare(CRM_Utils_System::version(), '5.25.0', '>=')) {
        if ($isWpRestPluginActive) {
          $this->checkedItems['is_wp_rest_plugin_active']['message'] = 'For CiviCRM version 5.25.0 or higher you do not need to install CiviCRM WP REST API Wrapper plugin on WordPress. You should uninstall it for CiviMobile to work properly.';
          $this->checkedItems['is_wp_rest_plugin_active']['status'] = 'warning';
        } else {
          $this->checkedItems['is_wp_rest_plugin_active']['message'] = 'For CiviCRM version 5.25.0 or higher you do not need to install CiviCRM WP REST API Wrapper plugin on WordPress.';
          $this->checkedItems['is_wp_rest_plugin_active']['status'] = 'success';
        }
      } else {
        if ($isWpRestPluginActive) {
          $this->checkedItems['is_wp_rest_plugin_active']['message'] = 'You have the additional plugin for Wordpress.';
          $this->checkedItems['is_wp_rest_plugin_active']['status'] = 'success';
        } else {
          $this->checkedItems['is_wp_rest_plugin_active']['message'] = 'You don`t have the additional plugin for Wordpress. CivimobileAPI cannot work without this plugin. You can read about this plugin here <a href="https://github.com/mecachisenros/civicrm-wp-rest">https://github.com/mecachisenros/civicrm-wp-rest</a>';
          $this->checkedItems['is_wp_rest_plugin_active']['status'] = 'error';
        }
      }
    }
  }

  /**
   * Checks is Server key valid
   */
  public function _checkIsServerKeyValid() {
    $isServerKeyValid = CRM_CiviMobileAPI_Utils_Extension::isServerKeyValid();

    $this->checkedItems['is_server_key_valid'] = [
      'title' => 'Is server key valid?',
      'message' => $isServerKeyValid ? 'Your server key is valid.' : 'Your server key is invalid. Please add correct server key to <a href="' . CRM_Utils_System::url('civicrm/civimobile/settings') . '" target="_blank">CiviMobile Settings</a> to activate Push Notifications.',
      'status' => $isServerKeyValid ? 'success' : 'warning',
    ];
  }

  /**
   * Checks is php-extension enabled
   */
  public function _checkIsCurlEnabled() {
    $isCurlEnabled = CRM_CiviMobileAPI_Utils_Extension::isCurlExtensionEnabled();

    $this->checkedItems['is_curl_enabled'] = [
      'title' => 'Is curl php-extension enabled?',
      'message' => $isCurlEnabled ? 'Curl php-extension is available on your web server.' : 'Curl php-extension is not available on your web server. It`s required for PushNotifications.',
      'status' => $isCurlEnabled ? 'success' : 'error',
    ];
  }

  /**
   * Checks is Cron running
   */
  public function _checkCron() {
    $checkCron = (new CRM_Utils_Check_Component_Env())->checkLastCron();
    $this->checkedItems['last_cron']['title'] = 'Is CRON running correctly?';

    switch ($checkCron[0]->getLevel()) {
      case CRM_Utils_Check::severityMap(\Psr\Log\LogLevel::INFO):
        $this->checkedItems['last_cron']['status'] = 'success';
        $this->checkedItems['last_cron']['message'] = 'CRON is running correctly.';
        break;
      case CRM_Utils_Check::severityMap(\Psr\Log\LogLevel::WARNING):
      case CRM_Utils_Check::severityMap(\Psr\Log\LogLevel::ERROR):
        $this->checkedItems['last_cron']['status'] = 'warning';
        $this->checkedItems['last_cron']['message'] = 'CRON isn`t running. If CRON isn`t enabled, it can clog your database.';
        break;
    }
  }

  /**
   * Checks is conflict WP plugins installed
   */
  public function _checkConflictWPPlugins() {
    if (CRM_CiviMobileAPI_Utils_CmsUser::getInstance()->getSystem() == CRM_CiviMobileAPI_Utils_CmsUser::CMS_WORDPRESS) {
      include_once(ABSPATH . 'wp-admin/includes/plugin.php');

      if (function_exists('is_plugin_active')) {
        foreach (CRM_CiviMobileAPI_Utils_Cms_Wordpress::getConflictPlugins() as $path => $name) {
          if (is_plugin_active($path)) {
            $this->checkedItems[] = [
              'title' => E::ts('Is <strong>"%1"</strong> plugin active?', [1 => $name]),
              'message' => E::ts('CiviMobile may not work properly if <strong>"%1"</strong> plugin is installed.', [1 => $name]),
              'status' => 'warning',
            ];
          }
        }
      }
    }
  }

  public function _infoConflictWPPlugins() {
    if (CRM_CiviMobileAPI_Utils_CmsUser::getInstance()->getSystem() == CRM_CiviMobileAPI_Utils_CmsUser::CMS_WORDPRESS) {
      $this->infoItems[] = E::ts('If you have a problems with authorization on CiviMobile application please try to deactivate plugins one by one to detect which plugin blocks CiviMobile.');
    }
  }

  /**
   *  Adds CiviCRM version to $systemInfo
   */
  public function _siCiviCRMVersion() {
    $this->systemInfo[] = [
      'title' => 'CiviCRM version',
      'message' => CRM_Utils_System::version(),
    ];
  }

  /**
   *  Adds CMS to $systemInfo
   */
  public function _siCMS() {
    $this->systemInfo[] = [
      'title' => 'CMS',
      'message' => CRM_CiviMobileAPI_Utils_CmsUser::getInstance()->getSystem(),
    ];
  }

  /**
   *  Adds CMS version to $systemInfo
   */
  public function _siCMSVersion() {
    $this->systemInfo[] = [
      'title' => 'CMS version',
      'message' => CRM_CiviMobileAPI_Utils_Cms::getCMSVersion(),
    ];
  }

  /**
   *  Adds current and latest available versions to $systemInfo
   */
  public function _siCiviMobileVersions() {
    $version = CRM_CiviMobileAPI_Utils_VersionController::getInstance();

    $this->systemInfo[] = [
      'title' => 'Your CiviMobileAPI extension version',
      'message' => $version->getCurrentFullVersion(),
    ];
    $this->systemInfo[] = [
      'title' => 'Latest available CiviMobileAPI extension version',
      'message' => $version->getLatestFullVersion(),
    ];
  }

  /**
   *  Adds rest path to $systemInfo
   */
  public function _siRestPath() {
    $this->systemInfo[] = [
      'title' => 'Rest path',
      'message' => (new CRM_CiviMobileAPI_Utils_RestPath())->get(),
    ];
  }

  /**
   *  Adds absolute rest url to $systemInfo
   */
  public function _siAbsoluteRestUrl() {
    $this->systemInfo[] = [
      'title' => 'Absolute rest url',
      'message' => (new CRM_CiviMobileAPI_Utils_RestPath())->getAbsoluteUrl(),
    ];
  }

  /**
   *  Adds baseURL to $systemInfo
   */
  public function _siBaseUrl() {
    $config = CRM_Core_Config::singleton();
    $this->systemInfo[] = [
      'title' => 'userFrameworkBaseURL(Config)',
      'message' => $config->userFrameworkBaseURL,
    ];
  }

}
