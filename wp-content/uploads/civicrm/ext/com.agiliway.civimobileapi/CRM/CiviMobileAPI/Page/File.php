<?php

/**
 * Provides the ability to view images that use for display QR code
 */
class CRM_CiviMobileAPI_Page_File extends CRM_Core_Page {

  /**
   * Open file by URL
   */
  public function run() {
    $filename  = $_GET['photo'];
    $uploadDirPath = CRM_CiviMobileAPI_Utils_File::getUploadDirPath();
    $file = $uploadDirPath . $filename;

    if (!file_exists($file)) {
      return;
    }

    if (!is_readable($file)) {
      return;
    }

    $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    $mimeType = 'image/' . ($fileExtension == 'png' ? 'png' : $fileExtension);
    $ttl = 43200;

    CRM_Utils_System::setHttpHeader('Expires', gmdate('D, d M Y H:i:s \G\M\T', CRM_Utils_Time::getTimeRaw() + $ttl));
    CRM_Utils_System::setHttpHeader("Content-Type", $mimeType);
    CRM_Utils_System::setHttpHeader("Content-Disposition", "inline; filename=\"" . basename($file) . "\"");
    CRM_Utils_System::setHttpHeader("Cache-Control", "max-age=$ttl, public");
    CRM_Utils_System::setHttpHeader('Pragma', 'public');
    readfile($file);
    CRM_Utils_System::civiExit();
  }

}
