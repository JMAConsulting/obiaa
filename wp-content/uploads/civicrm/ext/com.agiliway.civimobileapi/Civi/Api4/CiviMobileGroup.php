<?php

namespace Civi\Api4;

use Civi\Api4\Action\CiviMobileGroup\Get;
use Civi\Api4\Action\CiviMobileGroup\Create;

class CiviMobileGroup extends Generic\BasicEntity {

  public static function get($checkPermissions = FALSE) {
    return (new Get(static::getEntityName(), __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  public static function create($checkPermissions = FALSE) {
    return (new Create(static::getEntityName(), __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  public static function getFields($checkPermissions = TRUE) {
    return (new Generic\BasicGetFieldsAction(__CLASS__, __FUNCTION__, function($getFieldsAction) {
      $fields = \CRM_Contact_DAO_Group::fields();

      $fields['id']['required'] = FALSE;

      return $fields;
    }))->setCheckPermissions($checkPermissions);
  }

  public static function permissions() {
    return [
      'get' => ['see groups'],
      'create' => ['edit groups'],
    ];
  }

}