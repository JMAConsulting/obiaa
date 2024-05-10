<?php

namespace Civi\Api4;

use CRM_Utils_Type;
use CRM_CiviMobileAPI_ExtensionUtil as E;

class CiviMobileDeleteAccount extends Generic\AbstractEntity {
  
  public static function getFields($checkPermissions = FALSE) {
    return (new Generic\BasicGetFieldsAction(__CLASS__, __FUNCTION__, function ($getFieldsAction) {
      return [
        [
          'title' => E::ts('Account id'),
          'name' => 'id',
          'data_type' => CRM_Utils_Type::T_INT,
          'required' => TRUE
        ]
      ];
    }));
  }

  public static function delete($checkPermissions = FALSE) {
    return (new Action\CiviMobileDeleteAccount\Delete(__CLASS__, __FUNCTION__));
  }
  
  public static function permissions() {
    return [
      'delete' => ['access CiviCRM']
    ];
  }
}
