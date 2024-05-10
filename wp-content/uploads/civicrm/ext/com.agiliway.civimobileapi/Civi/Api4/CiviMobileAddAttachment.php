<?php

namespace Civi\Api4;

use CRM_Utils_Type;
use CRM_CiviMobileAPI_ExtensionUtil as E;

class CiviMobileAddAttachment extends Generic\BasicEntity {
  
  public static function getFields($checkPermissions = FALSE) {
    return (new Generic\BasicGetFieldsAction(__CLASS__, __FUNCTION__, function ($getFieldsAction) {
      return [
        [
          'title' => E::ts('Entity ID'),
          'name' => 'entity_id',
          'data_type' => CRM_Utils_Type::T_INT,
          'required' => TRUE
        ],
        [
          'title' => E::ts('Entity Table'),
          'name' => 'entity_table',
          'data_type' => CRM_Utils_Type::T_STRING,
          'required' => TRUE
        ],
      ];
    }));
  }

  public static function create($checkPermissions = FALSE) {
    return (new Action\CiviMobileAddAttachment\Create(__CLASS__, __FUNCTION__));
  }
  
  public static function permissions() {
    return [
      'create' => ['access CiviCRM']
    ];
  }
}