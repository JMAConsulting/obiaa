<?php

namespace Civi\Api4;

use Civi\Api4\Action\CiviMobileCoreSelectValues\Get;
use CRM_Utils_Type;

class CiviMobileCoreSelectValues extends Generic\BasicEntity {

  public static function get($checkPermissions = FALSE) {
    return (new Get(static::getEntityName(), __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  public static function getFields($checkPermissions = TRUE) {
    return (new Generic\BasicGetFieldsAction(__CLASS__, __FUNCTION__, function($getFieldsAction) {
      return [
        [
          'title' => 'Type',
          'name' => 'type',
          'data_type' => CRM_Utils_Type::T_STRING,
          'required' => TRUE,
        ],
      ];
    }))->setCheckPermissions($checkPermissions);
  }

  public static function permissions() {
    return [
      'get' => ['access CiviCRM'],
    ];
  }

}