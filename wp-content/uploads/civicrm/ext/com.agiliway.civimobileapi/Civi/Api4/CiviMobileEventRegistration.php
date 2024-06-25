<?php

namespace Civi\Api4;

use Civi\Api4\Action\CiviMobileEventRegistration\Create;
use CRM_Utils_Type;

class CiviMobileEventRegistration extends Generic\BasicEntity {

  public static function getFields($checkPermissions = FALSE) {
    return (new Generic\BasicGetFieldsAction(__CLASS__, __FUNCTION__, function ($getFieldsAction) {
      return [
        [
          'name' => 'event_id',
          'title' => ts('Event ID'),
          'type' => CRM_Utils_Type::T_INT,
          'required' => TRUE,
        ]
      ];
    }));
  }

  public static function create($checkPermissions = TRUE) {
    return (new Create(__CLASS__, __FUNCTION__))
      ->setCheckPermissions($checkPermissions);
  }

  public static function permissions() {
    return [
      'get' => ['access CiviCRM']
    ];
  }
}
