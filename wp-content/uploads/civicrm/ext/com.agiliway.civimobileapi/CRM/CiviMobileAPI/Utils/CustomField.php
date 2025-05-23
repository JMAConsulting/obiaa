<?php

class CRM_CiviMobileAPI_Utils_CustomField {

  /**
   * Gets Custom Field id by Custom Group name and Custom Field name
   *
   * @param $customGroupName
   * @param $customFieldName
   *
   * @return bool|int
   */
  public static function getId($customGroupName, $customFieldName) {
    return civicrm_api4('CustomField', 'get', [
      'select' => [
        'id',
      ],
      'where' => [
        ['name', '=', $customFieldName],
        ['custom_group_id:name', '=', $customGroupName],
      ],
      'checkPermissions' => FALSE,
    ])->first()['id'];
  }

}
