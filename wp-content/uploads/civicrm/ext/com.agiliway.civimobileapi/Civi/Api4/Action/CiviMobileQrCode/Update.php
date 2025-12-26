<?php

namespace Civi\Api4\Action\CiviMobileQrCode;

use Civi\Api4\Generic\AbstractAction;
use Civi\Api4\Generic\Result;
use CRM_CiviMobileAPI_Utils_Cms;

class Update extends AbstractAction {

  public function _run(Result $result) {
    $generator = new \CRM_CiviMobileAPI_Install_Entity_ApplicationQrCode();
    $generator->generateQrCode();

    $fileUrl = \CRM_CiviMobileAPI_Install_Entity_ApplicationQrCode::getPath();
    $siteUrl = CRM_CiviMobileAPI_Utils_Cms::getPublicBaseUrl();
    $qrContent = 'https://civimobile.org/download?domain=' . $siteUrl;

    $result[] = [
      'qr_file' => $fileUrl,
      'qr_content' => $qrContent,
    ];
  }

}