<?php

namespace Civi\Api4;

use CRM_Utils_Type;
use CRM_CiviMobileAPI_ExtensionUtil as E;

class CiviMobileProfileFields extends Generic\BasicEntity {

  public static function getFields($checkPermissions = FALSE) {
    return (new Generic\BasicGetFieldsAction(__CLASS__, __FUNCTION__, function ($getFieldsAction) {
      return [
        [
          'title' => E::ts('Entity Table'),
          'name' => 'entity_table',
          'data_type' => CRM_Utils_Type::T_STRING,
          'required' => TRUE
        ],
        [
          'title' => E::ts('Entity ID'),
          'name' => 'entity_id',
          'data_type' => CRM_Utils_Type::T_INT,
          'required' => TRUE
        ],
      ];
    }));
  }
  
  public static function get($checkPermissions = FALSE) {
    return (new Action\CiviMobileProfileFields\Get(__CLASS__, __FUNCTION__));
  }

  public static function permissions() {
    return [
      'create' => ['access CiviCRM']
    ];
  }
}
