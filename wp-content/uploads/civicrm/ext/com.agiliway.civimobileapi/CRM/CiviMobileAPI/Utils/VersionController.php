<?php

/**
 * Class retrieve version of extension and compare
 */
class CRM_CiviMobileAPI_Utils_VersionController {

  /**
   * @var CRM_CiviMobileAPI_Utils_VersionController
   */
  private static $instance;

  /**
   * Version of current extension
   */
  private $currentVersion = '0.0.0';

  /**
   * Version of extension in remote repository
   */
  private $latestVersion = '0.0.0';

  /**
   * Is sets version from repository
   */
  private $isSetVersionFromRepository = false;

  /**
   * CRM_CiviMobileAPI_Utils_VersionController constructor.
   */
  private function __construct() {
    $this->setVersionFromExtension();
  }

  /**
   * Sets version from current extension
   */
  public function setVersionFromExtension() {
    $this->currentVersion = $this->getCurrentVersion();
  }

  /**
   * Sets version from repository
   */
  public function setVersionFromRepository() {
    if ($this->isSetVersionFromRepository) {
      return;
    }

    $this->latestVersion = $this->getLatestVersion();
    $this->isSetVersionFromRepository = true;
  }

  /**
   * Gets current version of extension
   *
   * @return float
   */
  private function getCurrentVersion() {
    $extensionPath = CRM_Core_Config::singleton()->extensionsDir . CRM_CiviMobileAPI_ExtensionUtil::LONG_NAME;
    $infoFilePath = $extensionPath . '/info.xml';

    try {
      $extensionInfo = CRM_Extension_Info::loadFromFile($infoFilePath);

      return (string) $extensionInfo->version;
    }
    catch (Exception $e) {
      return '';
    }
  }

  /**
   * Gets latest version of extension
   *
   * @return string
   */
  private function getLatestVersion() {
    if (!function_exists('curl_init')) {
      return '';
    }
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, 'https://lab.civicrm.org/api/v4/projects/460/releases/');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
      'User-Agent: Awesome-Octocat-App',
    ]);
    $response = json_decode(curl_exec($ch), TRUE);

    return (string) $response[0]['tag_name'];
  }

  /**
   * Is current version lower than repository version
   * Is need to update extension
   *
   * @return bool
   */
  public function isCurrentVersionLowerThanRepositoryVersion() {
    $this->setVersionFromRepository();

    return version_compare($this->currentVersion, $this->latestVersion, '<');
  }

  /**
   * Gets the instance
   */
  public static function getInstance()
  {
    if (null === static::$instance) {
      static::$instance = new static();
    }

    return static::$instance;
  }

  /**
   * Gets Latest full version of extension in remote repository
   *
   * @return string
   */
  public function getLatestFullVersion() {
    $this->setVersionFromRepository();
    return $this->latestVersion;
  }

  /**
   * Gets current full version of extension in remote repository
   *
   * @return string
   */
  public function getCurrentFullVersion() {
    return $this->currentVersion;
  }

  private function __clone() {}

  private function __wakeup() {}

}
