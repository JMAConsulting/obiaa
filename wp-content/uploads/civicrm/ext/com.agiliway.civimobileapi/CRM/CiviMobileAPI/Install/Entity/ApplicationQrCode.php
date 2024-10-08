<?php

class CRM_CiviMobileAPI_Install_Entity_ApplicationQrCode {

  /**
   * File name for Application QrCode
   *
   * @var string
   */
  const FILE_NAME = 'qrCodeForApplication.png';

  public static function getPath() {
    return CRM_CiviMobileAPI_Utils_File::getFileUrl(1, 'civimobile', self::FILE_NAME);
  }

  public function install() {
    $this->generateQrCode();
  }

  public function generateQrCode() {
    $config = CRM_Core_Config::singleton();
    $directoryName = $config->uploadDir . DIRECTORY_SEPARATOR . 'qr';
    CRM_Utils_File::createDir($directoryName);
    $imageName = self::FILE_NAME;
    $path = $directoryName . DIRECTORY_SEPARATOR . $imageName;
    $siteUrl = CRM_CiviMobileAPI_Utils_Cms::getPublicBaseUrl();

    $params = [
      'attachFile_1' => [
        'uri' => $path,
        'location' => $path,
        'description' => '',
        'type' => 'image/png'
      ],
    ];

    $qrCodeContent = 'https://civimobile.org/download?domain=' . $siteUrl;
    \PHPQRCode\QRcode::png($qrCodeContent, $path, 'L', 9, 3);
    CRM_Core_BAO_File::processAttachment($params, 'civimobile', 1);
  }
}
