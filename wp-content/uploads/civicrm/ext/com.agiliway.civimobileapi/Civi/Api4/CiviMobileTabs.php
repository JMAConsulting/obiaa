<?php

namespace Civi\Api4;

use Civi\Api4\Action\CiviMobileTabs\Get;

class CiviMobileTabs extends Generic\BasicEntity {

  public static function get($checkPermissions = false) {
    return (new Get(static::getEntityName(), __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  public static function getFields($checkPermissions = TRUE) {
    return (new Generic\BasicGetFieldsAction(__CLASS__, __FUNCTION__, function($getFieldsAction) {
      return [];
    }))->setCheckPermissions($checkPermissions);
  }

  public static function permissions() {
    return [
      'get' => ['access CiviCRM']
    ];
  }

}