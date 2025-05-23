<?php

/**
 * Class provide OptionValue helper methods
 */
class CRM_CiviMobileAPI_Utils_OptionValue {

  /**
   * Get id OptionValue for custom groupId
   *
   * @param $optionGroupName
   * @param $optionValueName
   *
   * @return array|bool
   */
  public static function getId($optionGroupName, $optionValueName) {
    $optionValue = civicrm_api4('OptionValue', 'get', [
      'select' => ['id'],
      'where' => [
        ['option_group_id:name', '=', $optionGroupName],
        ['name', '=', $optionValueName],
      ],
      'limit' => 1,
      'checkPermissions' => FALSE,
    ])->first();

    return !empty($optionValue['id']) ? $optionValue['id'] : false;
  }

  /**
   * Gets OptionValues by OptionGroupId
   *
   * @param $optionGroupId
   * @param array $extraParams
   *
   * @return array|bool
   */
  public static function getGroupValues($optionGroupId, $extraParams = []) {
    if (empty($optionGroupId)) {
      return [];
    }

    $whereParams = [['option_group_id', '=', $optionGroupId]];

    foreach ($extraParams as $key => $extraParam) {
      $whereParams[] = [$key, '=', $extraParam];
    }

    return civicrm_api4('OptionValue', 'get', [
      'where' => $whereParams,
      'orderBy' => [
        'label' => 'ASC',
      ],
      'checkPermissions' => FALSE,
    ])->getArrayCopy();
  }

}
