<?php

/**
 * @deprecated will be deleted in version 7.0.0
 */
class CRM_CiviMobileAPI_ApiWrapper_Contribution implements API_Wrapper {

  /**
   * Interface for interpreting api input
   *
   * @param array $apiRequest
   *
   * @return array
   */
  public function fromApiInput($apiRequest) {
    if (!empty($apiRequest['params']['return'])) {
      if (is_string($apiRequest['params']['return'])) {
        $apiRequest['params']['return'] = explode(',', $apiRequest['params']['return']);
      }
      $apiRequest['params']['return'] = array_unique(array_merge($apiRequest['params']['return'], ['currency', 'financial_type_id']));
    }

    if (is_mobile_request()) {
      if (isset($apiRequest['params']['currency'])) {
        $contributionsIds = $this->getIdsByCurrency($apiRequest['params']['currency']);

        if (!empty($contributionsIds)) {
          $apiRequest['params']['contribution_id'] = ['IN' => $contributionsIds];
        } else {
          $apiRequest['params']['contribution_id'] = ['IS NULL' => 1];
        }
        unset($apiRequest['params']['currency']);
      }

      if ($apiRequest['params']['contact_display_name']
        || $apiRequest['params']['contact_type']
        || $apiRequest['params']['contact_tags']
        || $apiRequest['params']['contact_groups']
      ) {

        $contactsId = (new CRM_CiviMobileAPI_Utils_ContactFieldsFilter)->filterContacts($apiRequest['params']);

        if (!empty($contactsId)) {
          $apiRequest['params']['contact_id'] = ['IN' => $contactsId];
        } else {
          $apiRequest['params']['contact_id'] = ['IS NULL' => 1];
        }
      }
    }
    return $apiRequest;
  }

  /**
   * Interface for interpreting api output
   *
   * @param $apiRequest
   * @param $result
   *
   * @return array
   */
  public function toApiOutput($apiRequest, $result) {
    if (is_mobile_request()) {
      if (!empty($result['values'])) {
        $contactsId = $this->getContributionContactsId($result['values']);

        try {
          $contacts = civicrm_api3('Contact', 'get', [
            'sequential' => 1,
            'id' => ["IN" => $contactsId],
            'options' => ['limit' => 0],
          ])['values'];
        } catch (CiviCRM_API3_Exception $e) {
          $contacts = [];
        }

        if (!empty($contacts)) {
          foreach ($contacts as $contact) {
            foreach ($result['values'] as &$contribution) {
              if ($contact['id'] == $contribution['contact_id']) {
                $contribution['contact_display_name'] = !empty($contact['display_name']) ? $contact['display_name'] : '';
                $contribution['contact_first_name'] = !empty($contact['first_name']) ? $contact['first_name'] : '';
                $contribution['contact_last_name'] = !empty($contact['last_name']) ? $contact['last_name'] : '';
                $contribution['contact_type'] = !empty($contact['contact_type']) ? $contact['contact_type'] : '';
                $contribution['contact_image_URL'] = !empty($contact['image_URL']) ? $contact['image_URL'] : '';
              }
            }
          }
        }
      }

      if (is_string($apiRequest['params']['return'])) {
        $apiRequest['params']['return'] = explode(',', $apiRequest['params']['return']);
      }

      $result = $this->fillFinancialTypeName($apiRequest, $result);
      $result = $this->fillFormatTotalAmount($apiRequest, $result);
      $result['max_total_amount'] = $this->getMaxTotalAmount();
    }
    return $result;
  }

  /**
   * Get contribution contact's Id
   *
   * @param $contributions
   * @return array
   */
  public function getContributionContactsId($contributions) {
    $contactsId = [];
    if (!empty($contributions)) {
      foreach ($contributions as $contribution) {
        $contactsId[] = $contribution['contact_id'];
      }
    }

    return $contactsId;
  }

  /**
   * Get max total amount of the contributions
   *
   * @return int
   */
  public function getMaxTotalAmount() {
    $select = 'SELECT MAX(civicrm_contribution.total_amount) as max_total_amount';
    $from = ' FROM civicrm_contribution';
    $sql = $select . $from;

    $maxTotalAmount = 0;
    try {
      $dao = CRM_Core_DAO::executeQuery($sql);
      while ($dao->fetch()) {
        $maxTotalAmount = (int)$dao->max_total_amount;
      }
    } catch (Exception $e) {
      return $maxTotalAmount;
    }

    return $maxTotalAmount;
  }

  /**
   * Check with API version is being called.
   *
   * @param $result - either apiv3 or apiv4 result
   *
   * @return Boolean - TRUE if the api result is from version 3.
   */
  private static function isApiVersion3($result) {
    if (is_array($result) && isset($result['version']) && $result['version'] == 3) {
      return TRUE;
    }
    return FALSE;
  }

  /**
   * @param array $apiRequest
   * @param array $result
   *
   * @return mixed
   */
  private function fillFinancialTypeName($apiRequest, $result) {
    if (!self::isApiVersion3($result)) {
      return $result;
    }

    if (!empty($apiRequest['params']['return'])
      && in_array('financial_type_name', $apiRequest['params']['return'])
      && $apiRequest['action'] == 'get'
    ) {
      foreach ($result['values'] as &$contribution) {
        $contribution['financial_type_name'] = $this->getFinancialTypeName($contribution);
      }
    }

    return $result;
  }

  /**
   * @param array $apiRequest
   * @param array $result
   *
   * @return mixed
   */
  private function fillFormatTotalAmount($apiRequest, $result) {
    if (!self::isApiVersion3($result)) {
      return $result;
    }

    if (!empty($apiRequest['params']['return'])
      && in_array('total_amount', $apiRequest['params']['return'])
      && $apiRequest['action'] == 'get'
    ) {
      foreach ($result['values'] as &$contribution) {
        $contribution['format_total_amount'] = CRM_Utils_Money::format($contribution['total_amount'], $contribution['currency']);
      }
    }

    return $result;
  }

  /**
   * @param array $contribution
   *
   * @return string
   */
  private function getFinancialTypeName($contribution) {
    if (!empty($contribution['financial_type_id'])) {
      return CRM_Core_DAO::getFieldValue('CRM_Financial_DAO_FinancialType', $contribution['financial_type_id'], 'name');
    } else {
      $financialTypeId = CRM_Core_DAO::getFieldValue('CRM_Contribute_DAO_Contribution', $contribution['id'], 'financial_type_id');

      return CRM_Core_DAO::getFieldValue('CRM_Financial_DAO_FinancialType', $financialTypeId, 'name');
    }
  }

  /**
   * @param string $currency
   *
   * @return array
   */
  public function getIdsByCurrency($currency) {
    $table = CRM_Contribute_DAO_Contribution::getTableName();
    $sql =  "SELECT id as contribution_id FROM $table WHERE currency = %1"; 
    $params = [
      1 => [$currency, 'String']
    ];
    $contributionsByCurrenciesList = [];
    try {
      $dao = CRM_Core_DAO::executeQuery($sql, $params);
      while ($dao->fetch()) {
        $contributionsByCurrenciesList[] = $dao->contribution_id;
      }
    } catch (Exception $e) {
      $contributionsByCurrenciesList = [];
    }

    return $contributionsByCurrenciesList;
  }
}
