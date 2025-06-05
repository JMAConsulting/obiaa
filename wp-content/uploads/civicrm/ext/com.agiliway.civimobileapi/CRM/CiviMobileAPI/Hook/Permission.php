<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_Hook_Permission {

  public static function run(&$permissionList) {
    $permissionsPrefix = 'CiviCRM : ';

    $permissionList[CRM_CiviMobileAPI_Utils_Permission::CAN_CHECK_IN_ON_EVENT] = [
      'label' => $permissionsPrefix . CRM_CiviMobileAPI_Utils_Permission::CAN_CHECK_IN_ON_EVENT,
      'description' => E::ts("It means User can only update Participant status to 'Registered' or 'Attended'. Uses by QR Code."),
    ];

    $permissionList['view Agenda'] = [
      'label' => $permissionsPrefix . 'view Agenda',
      'description' => E::ts("View Agenda."),
    ];

    $permissionList['see tags'] = [
      'label' => $permissionsPrefix . 'see tags',
      'description' => E::ts("It means the User can see the tags he belongs to."),
    ];

    $permissionList['see groups'] = [
      'label' => $permissionsPrefix . 'see groups',
      'description' => E::ts("It means the User can see the groups he belongs to"),
    ];

    $permissionList['CiviMobile backend access'] = [
      'label' => $permissionsPrefix . 'CiviMobile backend access',
      'description' => E::ts("Gives possibility to access CiviMobile without accessing web. Works in a pair with CiviCRM: access CiviCRM backend and API"),
    ];

    $permissionList['CiviMobile ChatGPT access'] = [
      'label' => $permissionsPrefix . 'CiviMobile ChatGPT access',
      'description' => E::ts("Allows to send requests to ChatGPT"),
    ];

    $permissionList['CiviMobile view all events'] = [
      'label' => $permissionsPrefix . 'CiviMobile view all events',
      'description' => E::ts("View all events including not public for authenticated user"),
    ];
  }
}
