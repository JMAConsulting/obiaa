<?php

namespace Civi\Api4;

use CRM_Utils_Type;
use CRM_CiviMobileAPI_ExtensionUtil as E;

class CiviMobileResetPassword extends Generic\BasicEntity {
  
  public static function getFields($checkPermissions = FALSE) {
    return (new Generic\BasicGetFieldsAction(__CLASS__, __FUNCTION__, function ($getFieldsAction) {
      return [
        [
          'title' => E::ts('Email'),
          'name' => 'email',
          'data_type' => CRM_Utils_Type::T_EMAIL,
          'required' => TRUE
        ]
      ];
    }));
  }

  public static function create($checkPermissions = FALSE) {
    return (new Action\CiviMobileResetPassword\Create(__CLASS__, __FUNCTION__));
  }
  
  public static function permissions() {
    return [
      'create' => ['access CiviCRM']
    ];
  }
}
