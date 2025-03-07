<?php

namespace Civi\Api4;

use CRM_Utils_Type;
use CRM_CiviMobileAPI_ExtensionUtil as E;

class CiviMobileGenerateText extends Generic\DAOEntity {

  public static function getFields($checkPermissions = False) {
    return (new Generic\BasicGetFieldsAction(__CLASS__, __FUNCTION__, function ($getFieldsAction) {
      return [
        [
          'title' => E::ts('Params'),
          'name' => 'params',
          'data_type' => CRM_Utils_Type::T_TEXT,
          'required' => false
        ],
        [
          'title' => E::ts('User input'),
          'name' => 'user_input',
          'data_type' => CRM_Utils_Type::T_STRING,
          'required' => false
        ]
      ];
    }));
  }

  public static function create($checkPermissions = False) {
    return (new Action\CiviMobileGenerateText\Create(__CLASS__, __FUNCTION__));
  }
  
  public static function permissions() {
    return [
      'create' => ['CiviMobile ChatGPT access', 'access CiviCRM']
    ];
  }

}