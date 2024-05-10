<?php

namespace Civi\Api4;

use Civi\Api4\Action\CiviMobileEvent\Get;
use CRM_Event_DAO_Event;

class CiviMobileEvent extends Generic\BasicEntity {

  public static function getFields($checkPermissions = FALSE) {
    return (new Generic\BasicGetFieldsAction(__CLASS__, __FUNCTION__, function ($getFieldsAction) {
      return CRM_Event_DAO_Event::fields();
    }));
  }

  public static function get($checkPermissions = TRUE) {
    return (new Get(__CLASS__, __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  public static function permissions() {
    return [
      'get' => ['access CiviCRM']
    ];
  }
}
