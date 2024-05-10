<?php

namespace Civi\Api4;

use CRM_Utils_Type;
use CRM_CiviMobileAPI_ExtensionUtil as E;

class CiviMobileGenerateEventDescription extends Generic\DAOEntity {

  public static function getFields($checkPermissions = False) {
    return (new Generic\BasicGetFieldsAction(__CLASS__, __FUNCTION__, function ($getFieldsAction) {
      return [
        [
          'title' => E::ts('Event title'),
          'name' => 'title',
          'data_type' => CRM_Utils_Type::T_STRING,
          'required' => true
        ],
        [
          'title' => E::ts('Event type'),
          'name' => 'event_type',
          'data_type' => CRM_Utils_Type::T_STRING,
          'required' => true
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
    return (new Action\CiviMobileGenerateEventDescription\Create(__CLASS__, __FUNCTION__));
  }
  
  public static function permissions() {
    return [
      'create' => ['CiviMobile ChatGPT access', 'access CiviCRM']
    ];
  }

}