<?php

/**
 * Class provide File helper methods
 */
class CRM_CiviMobileAPI_Utils_File {

  public static function getUploadDirPath() {
    return Civi::paths()->getPath(Civi::settings()->get('customFileUploadDir'));
  }

  /**
   * Removes uploaded file from server
   *
   * @param $filename
   *
   * @return bool
   */
  public static function removeUploadFile($filename) {
    $uploadDirPath = self::getUploadDirPath();
    $filePath = $uploadDirPath . $filename;

    if (!file_exists($filePath)) {
      return false;
    }

    if (unlink($filePath)) {
      return true;
    }

    return false;
  }

  /**
   * Gets Contact's avatar file name
   *
   * @param $contactId
   *
   * @return bool
   */
  public static function getContactAvatarFileName($contactId) {
    $linkToAvatar = civicrm_api4('Contact', 'get', [
      'select' => [
        'image_URL',
      ],
      'where' => [
        ['id', '=', $contactId],
      ],
      'checkPermissions' => FALSE,
    ])->first()['image_URL'];

    $linkToAvatar = htmlspecialchars_decode($linkToAvatar, ENT_NOQUOTES);
    $urlQuery = parse_url($linkToAvatar, PHP_URL_QUERY);
    parse_str($urlQuery, $parsedUrlQuery);

    if (!empty($parsedUrlQuery["photo"])) {
      return $parsedUrlQuery["photo"];
    }

    return false;
  }

  /**
   * Gets file url
   *
   * @param $entityId
   * @param $entityTable
   * @param $filename
   *
   * @return string
   */
  public static function getFileUrl($entityId, $entityTable, $filename) {
    $url = '';
    $files = CRM_Core_BAO_File::getEntityFile($entityTable, $entityId);
    $currentCMS = CRM_CiviMobileAPI_Utils_CmsUser::getInstance()->getSystem();

    foreach ($files as $file) {
      if ((!empty($file['fileName']) && $file['fileName'] == $filename)
        || (!empty($file['cleanName']) && $file['cleanName'] == $filename)) {
        $url = CRM_Utils_System::url('civicrm/file', ['filename' => $filename, 'mime-type' => $file['mime_type']], TRUE, NULL, FALSE);
      }
    }

    if ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_JOOMLA) {
      $url = preg_replace('/administrator\//', 'index.php', $url);
    } elseif ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_WORDPRESS ) {
      $url = str_replace("wp-admin/admin.php", "index.php", $url);
    }

    return $url;
  }

}
