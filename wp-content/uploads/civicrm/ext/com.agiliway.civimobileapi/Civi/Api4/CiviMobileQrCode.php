<?php

namespace Civi\Api4;

use CRM_Utils_Type;
use CRM_CiviMobileAPI_ExtensionUtil as E;

class CiviMobileQrCode extends Generic\DAOEntity {

  public static function getFields($checkPermissions = FALSE) {
    return (new Generic\BasicGetFieldsAction(__CLASS__, __FUNCTION__, function($getFieldsAction) {
      return [
      ];
    }));
  }

  public static function update($checkPermissions = FALSE) {
    return (new Action\CiviMobileQrCode\Update(__CLASS__, __FUNCTION__));
  }

  public static function permissions() {
    return [
      'update' => ['access CiviCRM'],
    ];
  }

}