<?php

/**
 * Class provide PriceSetField helper methods
 */
class CRM_CiviMobileAPI_Utils_PriceSetField {

  /**
   * @param $priceSetFieldId
   * @return array|bool
   */
  public static function getPriceSetFieldValue($priceSetFieldId) {
    try {
      $priceFieldValue = civicrm_api4('PriceFieldValue', 'get', [
        'select' => [
          'id',
          'name',
          'price_field_id',
          'amount',
          'label',
          'is_default',
        ],
        'where' => [
          ['price_field_id', '=', $priceSetFieldId],
          ['is_active', '=', TRUE],
        ],
        'checkPermissions' => FALSE,
      ])->getArrayCopy();
    } catch (CRM_Core_Exception $e) {
      return false;
    }

    return ['values' => $priceFieldValue];
  }

  /**
   * Is Actual PriceField now
   *
   * @param array $priceSetField
   * @return bool
   */
  public static function isActualPriceFieldNow($priceSetField) {
    if (isset($priceSetField['active_on']) && isset($priceSetField['expire_on'])) {
      if (CRM_Utils_Date::unixTime($priceSetField['active_on']) >= CRM_Utils_Date::unixTime($priceSetField['expire_on'])) {
        return FALSE;
      }
      if (CRM_Utils_Date::unixTime($priceSetField['active_on']) >= time() || CRM_Utils_Date::unixTime($priceSetField['expire_on']) <= time()) {
        return FALSE;
      }
    } elseif (isset($priceSetField['active_on']) && CRM_Utils_Date::unixTime($priceSetField['active_on']) >= time()) {
      return FALSE;
    } elseif (isset($priceSetField['expire_on']) && CRM_Utils_Date::unixTime($priceSetField['expire_on']) <= time()) {
      return FALSE;
    }

    return TRUE;
  }

}
