<?php

namespace Civi\Api4;

use Civi\Api4\Action\CiviMobileGroupContact\Create;

class CiviMobileGroupContact extends Generic\BasicEntity {

  public static function getFields($checkPermissions = TRUE) {
    return (new Generic\BasicGetFieldsAction(__CLASS__, __FUNCTION__, function($getFieldsAction) {
      $fields = \CRM_Contact_DAO_GroupContact::fields();

      $fields['id']['required'] = FALSE;

      return $fields;
    }))->setCheckPermissions($checkPermissions);
  }

  public static function create($checkPermissions = FALSE) {
    return (new Create(static::getEntityName(), __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  public static function permissions() {
    return [
      'getContacts' => ['access CiviCRM'],
      'create' => [
        ['edit my contact', 'edit all contacts'],
      ],
    ];
  }

}