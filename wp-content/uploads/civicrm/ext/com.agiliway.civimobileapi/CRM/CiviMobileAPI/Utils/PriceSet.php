<?php

/**
 * Class provide PriceSet helper methods
 */
class CRM_CiviMobileAPI_Utils_PriceSet {

  /**
   * Gets price set fields by price set id
   *
   * @param $priceSetId
   *
   * @return array|bool
   */
  public static function getFields($priceSetId) {
    try {
      $priceSetFields = civicrm_api4('PriceField', 'get', [
        'where' => [
          ['price_set_id', '=', $priceSetId],
        ],
        'checkPermissions' => FALSE,
      ])->getArrayCopy();
    } catch (CRM_Core_Exception $e) {
      return FALSE;
    }

    return $priceSetFields;
  }

}
