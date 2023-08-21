<?php
/*
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC. All rights reserved.                        |
 |                                                                    |
 | This work is published under the GNU AGPLv3 license with some      |
 | permitted exceptions and without any warranty. For full license    |
 | and copyright information, see https://civicrm.org/licensing       |
 +--------------------------------------------------------------------+
 */

use Civi\Api4\ContributionRecur;
use Civi\Payment\Exception\PaymentProcessorException;
use Civi\Payment\PropertyBag;
use CRM_Mjwshared_ExtensionUtil as E;
/**
 * Shared payment functions that should one day be migrated to CiviCRM core
 * Trait CRM_Core_Payment_MJWTrait
 */
trait CRM_Core_Payment_MJWTrait {

  /**
   * @var array params passed for payment
   */
  protected $_params = [];

  /**
   * @var string The unique charge/trxn reference from the payment processor
   */
  private $paymentProcessorTrxnID;

  /**
   * @var string The unique invoice/order reference from the payment processor
   */
  private $paymentProcessorOrderID;

  /**
   * @var string The unique subscription reference from the payment processor
   */
  private $paymentProcessorSubscriptionID;

  /**
   * @var bool Throw an exception in handleError.
   */
  public $handleErrorThrowsException = FALSE;

  /**
   * Get the billing email address
   *
   * @param \Civi\Payment\PropertyBag|array $params
   * @param int $contactID
   *
   * @return string|NULL
   */
  public function getBillingEmail($propertyBag, $contactID = NULL) {
    // We want this function to take a single argument - propertyBag but for legacy compatibility
    //   we still accept an array and the second parameter contactID.
    // Start: Hackery to convert this function to take propertyBag
    $propertyBag = PropertyBag::cast($propertyBag);
    if (empty($contactID) && $propertyBag->has('contactID')) {
      $contactID = $propertyBag->getContactID();
    }
    $params = $this->getPropertyBagAsArray($propertyBag);
    // End: Hackery to convert this function to take propertyBag

    $billingLocationId = CRM_Core_BAO_LocationType::getBilling();
    $emailAddress = $params["email-{$billingLocationId}"] ?? $params['email-Primary'] ?? $params['email'] ?? NULL;

    if (empty($emailAddress) && !empty($contactID)) {
      // Try and retrieve an email address from Contact ID
      $emailAddresses = \Civi\Api4\Email::get(FALSE)
        ->addWhere('contact_id', '=', $contactID)
        ->execute();

      $other_options = [];
      foreach ($emailAddresses as $row) {
        if ($row['location_type_id'] == $billingLocationId) {
          return $row['email'];
        }
        elseif ($row['is_primary']) {
          array_unshift($other_options, $row['email']);
        }
        else {
          $other_options[] = $row['email'];
        }
      }
      if ($other_options) {
        return $other_options[0];
      }
      return NULL;
    }
    return $emailAddress;
  }

  /**
   * Get the billing email address
   *
   * @param array $params
   * @param int $contactId
   *
   * @return string|NULL
   */
  protected function getBillingPhone($params, $contactId) {
    $billingLocationId = CRM_Core_BAO_LocationType::getBilling();
    $phoneNumber = $params["phone-{$billingLocationId}"] ?? $params['phone-Primary'] ?? $params['phone'] ?? NULL;

    if (empty($phoneNumber) && !empty($contactId)) {
      // Try and retrieve a phone number from Contact ID
      try {
        $phoneNumber = civicrm_api3('Phone', 'getvalue', [
          'contact_id' => $contactId,
          'return' => ['phone'],
        ]);
      }
      catch (CiviCRM_API3_Exception $e) {
        return NULL;
      }
    }
    return $phoneNumber;
  }

  /**
   * Get the contact id
   *
   * @param \Civi\Payment\PropertyBag $propertyBag
   *
   * @return int|NULL The ContactID
   */
  protected function getContactId(&$propertyBag) {
    if ($propertyBag->has('contactID')) {
      return $propertyBag->getContactID();
    }

    // cms_contactID is set by: membership payment workflow when "on behalf of" / related contact is used.
    $contactId = $propertyBag->getter('cms_contactID', TRUE) ?? $propertyBag->getter('cid', TRUE);
    if (empty($contactId)) {
      // FIXME: Ref: https://lab.civicrm.org/extensions/stripe/issues/16
      // The problem is that when registering for a paid event, civicrm does not pass in the
      // contact id to the payment processor (civicrm version 5.3). So, I had to patch your
      // getContactId to check the session for a contact id. It's a hack and probably should be fixed in core.
      // The code below is exactly what CiviEvent does, but does not pass it through to the next function.
      $session = CRM_Core_Session::singleton();
      $contactId = $session->get('transaction.userID', NULL);
    }
    if (!empty($contactId)) {
      $propertyBag->setContactID($contactId);
      return $propertyBag->getContactID();
    }
    return NULL;
  }

  /**
   * Get the recurring contribution ID from parameters
   *
   * @param \Civi\Payment\PropertyBag $propertyBag
   *
   * @return int|null
   */
  protected function getRecurringContributionId(PropertyBag $propertyBag) {
    if ($propertyBag->has('contributionRecurID')) {
      return $propertyBag->getContributionRecurID();
    }

    if ($propertyBag->has('contributionID')) {
      return (int) civicrm_api3('Contribution', 'getvalue', ['id' => $propertyBag->getContributionID(), 'return' => 'contribution_recur_id']);
    }

    if ($propertyBag->has('processorID')) {
      $propertyBag->getRecurProcessorID();
      return (int) civicrm_api3('ContributionRecur', 'getvalue', [
        'processor_id' => $propertyBag->getRecurProcessorID(),
        'return' => 'id'
      ]);
    }
    return NULL;
  }

  /**
   * @param array $params
   *
   * @return mixed|null
   */
  protected function getFinancialTypeID($params) {
    return $params['financial_type_id'] ?? $params['financialTypeID'] ?? NULL;
  }

  /**
   * Get the currency configured for the form when it is loaded
   *
   * @param \CRM_Core_Form $form
   *
   * @return string
   */
  public function getDefaultCurrencyForForm($form) {
    // For contribution pages it is in $form->_values
    $currency = $form->_values['currency'] ?? NULL;
    // If we AJAX load and have more than one processor (eg. stripe, pay later) we can end up with the wrong
    // currency set in $form->_values. So we use $form->getVar('currency') to get the right one!
    if (!$currency && is_a($form, 'CRM_Financial_Form_Payment')) {
      $currency = $form->getVar('currency');
    }

    // Due to https://github.com/civicrm/civicrm-core/pull/21966 currency might be set to the string "undefined"
    if (!$currency || $currency === 'undefined') {
      // For event pages it is in $form->_values['event']
      if (isset($form->_values['event'])) {
        $currency = $form->_values['event']['currency'] ?? NULL;
      }
    }
    if (!$currency || $currency === 'undefined') {
      // If we can't find it we'll use the default from the configuration
      $currency = CRM_Core_Config::singleton()->defaultCurrency;
    }
    return $currency;
  }

  /**
   *
   * @param array $params ['name' => payment instrument name]
   *
   * @return int|null
   * @throws \CiviCRM_API3_Exception
   */
  public static function createPaymentInstrument($params) {
    $mandatoryParams = ['name'];
    foreach ($mandatoryParams as $value) {
      if (empty($params[$value])) {
        Civi::log()->error('createPaymentInstrument: Missing mandatory parameter: ' . $value);
        return NULL;
      }
    }

    // Create a Payment Instrument
    // See if we already have this type
    $paymentInstrument = civicrm_api3('OptionValue', 'get', [
      'option_group_id' => "payment_instrument",
      'name' => $params['name'],
    ]);
    if (empty($paymentInstrument['count'])) {
      // Otherwise create it
      try {
        $financialAccount = civicrm_api3('FinancialAccount', 'getsingle', [
          'financial_account_type_id' => "Asset",
          'name' => "Payment Processor Account",
        ]);
      }
      catch (Exception $e) {
        $financialAccount = civicrm_api3('FinancialAccount', 'getsingle', [
          'financial_account_type_id' => "Asset",
          'name' => "Payment Processor Account",
          'options' => ['limit' => 1, 'sort' => "id ASC"],
        ]);
      }

      $paymentParams = [
        'option_group_id' => "payment_instrument",
        'name' => $params['name'],
        'description' => $params['name'],
        'financial_account_id' => $financialAccount['id'],
      ];
      $paymentInstrument = civicrm_api3('OptionValue', 'create', $paymentParams);
      $paymentInstrumentId = $paymentInstrument['values'][$paymentInstrument['id']]['value'];
    }
    else {
      $paymentInstrumentId = $paymentInstrument['id'];
    }
    return $paymentInstrumentId;
  }

  /**
   * Get the error URL to "bounce" the user back to.
   * @param \Civi\Payment\PropertyBag $params
   *
   * @return string|null
   */
  public function getErrorUrl($params) {
    // Get proper entry URL for returning on error.
    if (!$params->has('qfKey') || !$params->has('entryURL')) {
      // Probably not called from a civicrm form (e.g. webform) -
      // will return error object to original api caller.
      $errorUrl = NULL;
    }
    else {
      $qfKey = $params->getCustomProperty('qfKey');
      $parsedUrl = parse_url($params->getCustomProperty('entryURL'));
      $urlPath = substr($parsedUrl['path'], 1);
      $query = $parsedUrl['query'];
      if (strpos($query, '_qf_Main_display=1') === FALSE) {
        $query .= '&_qf_Main_display=1';
      }
      if (strpos($query, 'qfKey=') === FALSE) {
        $query .= "&qfKey={$qfKey}";
      }
      $errorUrl = CRM_Utils_System::url($urlPath, $query, FALSE, NULL, FALSE);
    }
    return $errorUrl;
  }

  /**
   * Are we using a test processor?
   *
   * @return bool
   */
  public function getIsTestMode() {
    return isset($this->_paymentProcessor['is_test']) && $this->_paymentProcessor['is_test'] ? 1 : 0;
  }

  /**
   * Format the fields for the payment processor.
   * @fixme Copied from CiviCRM Core 5.13. We should remove this when all forms submit using this function (eg updateSubscriptionBillingInfo)
   *
   * In order to pass fields to the payment processor in a consistent way we add some renamed
   * parameters.
   *
   * @param array $fields
   *
   * @return array
   */
  private function formatParamsForPaymentProcessor($fields) {
    $billingLocationId = CRM_Core_BAO_LocationType::getBilling();
    // also add location name to the array
    $this->_params["address_name-{$billingLocationId}"] = ($this->_params['billing_first_name'] ?? '') . ' ' . ($this->_params['billing_middle_name'] ?? '') . ' ' . ($this->_params['billing_last_name'] ?? '');
    $this->_params["address_name-{$billingLocationId}"] = trim($this->_params["address_name-{$billingLocationId}"]);
    // Add additional parameters that the payment processors are used to receiving.
    if (!empty($this->_params["billing_state_province_id-{$billingLocationId}"])) {
      $this->_params['state_province'] = $this->_params["state_province-{$billingLocationId}"] = $this->_params["billing_state_province-{$billingLocationId}"] = CRM_Core_PseudoConstant::stateProvinceAbbreviation($this->_params["billing_state_province_id-{$billingLocationId}"]);
    }
    if (!empty($this->_params["billing_country_id-{$billingLocationId}"])) {
      $this->_params['country'] = $this->_params["country-{$billingLocationId}"] = $this->_params["billing_country-{$billingLocationId}"] = CRM_Core_PseudoConstant::countryIsoCode($this->_params["billing_country_id-{$billingLocationId}"]);
    }

    [$hasAddressField, $addressParams] = CRM_Contribute_BAO_Contribution::getPaymentProcessorReadyAddressParams($this->_params, $billingLocationId);
    if ($hasAddressField) {
      $this->_params = array_merge($this->_params, $addressParams);
    }

    $nameFields = ['first_name', 'middle_name', 'last_name'];
    foreach ($nameFields as $name) {
      $fields[$name] = 1;
      if (array_key_exists("billing_$name", $this->_params)) {
        $this->_params[$name] = $this->_params["billing_{$name}"];
        $this->_params['preserveDBName'] = TRUE;
      }
    }
    return $fields;
  }

  /**
   * Handle an error and notify the user
   *
   * @param string $errorCode
   * @param string $errorMessage
   * @param string $bounceURL
   *
   * @throws \Civi\Payment\Exception\PaymentProcessorException
   *   (or statusbounce if URL is specified)
   */
  private function handleError($errorCode = '', $errorMessage = '', $bounceURL = NULL, $log = TRUE) {
    $errorMessage = empty($errorMessage) ? 'Unknown System Error.' : $errorMessage;
    $message = $errorMessage . (!empty($errorCode) ? " - {$errorCode}" : '');

    if ($log) {
      Civi::log()->error($this->getPaymentTypeLabel() . ' Payment Error: ' . $message);
    }
    if ($this->handleErrorThrowsException) {
      // We're in a test environment. Throw exception.
      throw new \Exception('Exception thrown to avoid statusBounce because handleErrorThrowsException is set.' . $message);
    }

    if ($bounceURL) {
      CRM_Core_Error::statusBounce($message, $bounceURL, $this->getPaymentTypeLabel());
    }
    throw new PaymentProcessorException($errorMessage, $errorCode);
  }

  /**
   * Get the label for the payment processor
   *
   * @return string
   */
  public function getPaymentProcessorLabel() {
    return $this->_paymentProcessor['name'];
  }

  /**
   * Allow (phpunit test code) to configure handleError to throw an exception,
   * thereby avoiding a statusBounce which is hard to deal with in tests.
   */
  public function setHandleErrorThrowsException(bool $value) {
    $this->handleErrorThrowsException = $value;
  }
  /**
   * Set the payment processor Transaction ID
   *
   * @param string $trxnID
   */
  protected function setPaymentProcessorTrxnID($trxnID) {
    $this->paymentProcessorTrxnID = $trxnID;
  }

  /**
   * Get the payment processor Transaction ID
   *
   * @return string
   */
  protected function getPaymentProcessorTrxnID() {
    return $this->paymentProcessorTrxnID;
  }

  /**
   * Set the payment processor Order ID
   *
   * @param string $orderID
   */
  protected function setPaymentProcessorOrderID($orderID) {
    $this->paymentProcessorOrderID = $orderID;
  }

  /**
   * Get the payment processor Order ID
   *
   * @return string
   */
  protected function getPaymentProcessorOrderID() {
    return $this->paymentProcessorOrderID;
  }

  /**
   * Set the payment processor Subscription ID
   *
   * @param string $subscriptionID
   */
  protected function setPaymentProcessorSubscriptionID($subscriptionID) {
    $this->paymentProcessorSubscriptionID = $subscriptionID;
  }

  /**
   * Get the payment processor Subscription ID
   *
   * @return string
   */
  protected function getPaymentProcessorSubscriptionID() {
    return $this->paymentProcessorSubscriptionID;
  }

  /**
   * In some cases a payment is still submitted via the payment processor with zero amount.
   * See eg. https://lab.civicrm.org/extensions/stripe/-/issues/256.
   * When you have a 0 membership option and a confirmation page.
   * This function should be called in doPayment() before beginDoPayment()
   *
   * @param \Civi\Payment\PropertyBag $propertyBag
   *
   * @return array|false
   */
  protected function processZeroAmountPayment(PropertyBag $propertyBag) {
    // If we have a $0 amount, skip call to processor and set payment_status to Completed.
    // https://github.com/civicrm/civicrm-core/blob/master/CRM/Core/Payment.php#L1362
    if ($propertyBag->getAmount() == 0) {
      return $this->setStatusPaymentCompleted([]);
    }
    else {
      return FALSE;
    }
  }

  /**
   * @param \Civi\Payment\PropertyBag $propertyBag
   *
   * @return \Civi\Payment\PropertyBag
   */
  protected function beginDoPayment($propertyBag) {
    // Make sure it's a propertyBag
    $propertyBag = PropertyBag::cast($propertyBag);
    // This currently doesn't have a default (5.27). Should be fixed in a future version of CiviCRM.
    if (!$propertyBag->has('isRecur')) {
      $propertyBag->setIsRecur(FALSE);
    }

    // Make sure we have a description for the contribution
    if (!$propertyBag->has('description')) {
      $propertyBag->setDescription(E::ts('Contribution: %1', [1 => $this->getPaymentProcessorLabel()]));
    }

    $propertyBag->setCustomProperty('error_url', $this->getErrorUrl($propertyBag));

    // Make sure we have a contactID set on propertyBag
    $this->getContactId($propertyBag);
    return $propertyBag;
  }

  /**
   * @param array $params
   *
   * @return \Civi\Payment\PropertyBag
   */
  protected function beginChangeSubscriptionAmount(array $params): PropertyBag {
    /*
     * 5.43 passes an array of params as follows:
     * $params = [
     *   'amount' => '10.00',
     *   'currency' => 'USD',
     *   'id' => 10 // Needs to map to contributionRecurID
     *   'subscriptionId' => 'yxz3432' // The processor_id/trxn_id
     *   'installments' => '' // May be set or not
     * ];
     */
    $propertyBag = PropertyBag::cast($params);
    $propertyBag->setContributionRecurID($params['id']);
    $propertyBag->setIsRecur(TRUE);
    // @fixme We can't use $propertyBag->setRecurInstallments until https://github.com/civicrm/civicrm-core/pull/21517 is merged
    //   Use $propertyBag->has('recurInstallments') ? $propertyBag->getRecurInstallments() : 0; in code as workaround
    // if (empty($params['installments'])) {
    //   $propertyBag->setRecurInstallments(0);
    // }
    $existingRecur = ContributionRecur::get(FALSE)
      ->addWhere('is_test', 'IN', [TRUE, FALSE])
      ->addWhere('id', '=', $propertyBag->getContributionRecurID())
      ->execute()
      ->first();

    // @fixme: https://github.com/civicrm/civicrm-core/pull/21517
    if (!$propertyBag->has('recurInstallments') && !empty($existingRecur['installments'])) {
      $propertyBag->setRecurInstallments($existingRecur['installments']);
    }
    $propertyBag->setRecurFrequencyInterval($existingRecur['frequency_interval']);
    $propertyBag->setRecurFrequencyUnit($existingRecur['frequency_unit']);

    $propertyBag->setCustomProperty('error_url', $this->getErrorUrl($propertyBag));

    // Make sure we have a contactID set on propertyBag
    $this->getContactId($propertyBag);
    return $propertyBag;
  }

  /**
   * @param array $params
   *
   * @return \Civi\Payment\PropertyBag
   */
  protected function beginUpdateSubscriptionBillingInfo(array $params): PropertyBag {
    /*
     * 5.43 passes an array of params as follows:
     * $params = [
     *   'amount' => '10.00',
     *   'subscriptionId' => 'yxz3432' // The processor_id/trxn_id
     *   .. card/billing fields
     * ];
     */
    $propertyBag = PropertyBag::cast($params);
    $propertyBag->setIsRecur(TRUE);
    $whereAnd[] = ['is_test', 'IN', [TRUE, FALSE]];
    if (isset($params['id'])) {
      $propertyBag->setContributionRecurID($params['id']);
      $whereAnd[] = ['id', '=', $params['id']];
    }
    elseif (isset($params['subscriptionId'])) {
      $whereAnd[] = ['processor_id', '=', $params['subscriptionId']];
    }
    else {
      throw new PaymentProcessorException('Missing id or processor_id required to find contributionRecur');
    }

    $existingRecur = ContributionRecur::get(FALSE)
      ->setWhere([['AND', $whereAnd]])
      ->execute()
      ->first();

    if ($existingRecur) {
      $propertyBag->setContributionRecurID($existingRecur['id']);
    }

    // @fixme Billing properties don't really work properly without https://github.com/civicrm/civicrm-core/pull/21527
    // $propertyBag->setBillingCity($params['city'] ?? '');
    // $propertyBag->setBillingCountry($params['country'] ?? '');
    // $propertyBag->setBillingStateProvince()

    $propertyBag->setCustomProperty('error_url', $this->getErrorUrl($propertyBag));

    // Make sure we have a contactID set on propertyBag
    $this->getContactId($propertyBag);
    return $propertyBag;
  }

  /**
   * Call this at the end of a call to doPayment to ensure everything is updated/returned correctly.
   *
   * @param \Civi\Payment\PropertyBag|array $params
   *
   * @return array
   * @throws \CiviCRM_API3_Exception
   */
  protected function endDoPayment($params) {
    $propertyBag = PropertyBag::cast($params);

    // We need to set this to ensure that contributions are set to the correct status
    // It should have already been set to "Completed" if we made a successful payment
    if (!$propertyBag->has('payment_status_id')) {
      $propertyBag = $this->setStatusPaymentPending($propertyBag);
    }

    // payment_status is the newer property. It *should* be set but we'll make sure.
    if (!$propertyBag->has('payment_status')) {
      CRM_Core_Error::deprecatedWarning('endDoPayment payment_status is not set! Make sure you are using setStatusPaymentPending/Completed');
      if ($propertyBag->getCustomProperty('payment_status_id') == CRM_Core_PseudoConstant::getKey('CRM_Contribute_BAO_Contribution', 'contribution_status_id', 'Completed')) {
        $propertyBag->setCustomProperty('payment_status', 'Completed');
      }
      else {
        $propertyBag->setCustomProperty('payment_status', 'Pending');
      }
    }

    // See https://lab.civicrm.org/dev/financial/-/issues/141
    $returnParams = [
      'payment_status_id' => $propertyBag->getCustomProperty('payment_status_id'),
      'payment_status' => $propertyBag->getCustomProperty('payment_status'),
      'trxn_id' => $this->getPaymentProcessorTrxnID() ?? $this->getPaymentProcessorOrderID(),
      'order_reference' => $this->getPaymentProcessorOrderID() ?? NULL,
    ];
    if ($propertyBag->has('feeAmount')) {
      $returnParams['fee_amount'] = $propertyBag->getFeeAmount();
    }
    return $returnParams;
  }

  /**
   * Set the payment status to Pending
   * @param \Civi\Payment\PropertyBag|array $params
   *
   * @return array
   */
  protected function setStatusPaymentPending($params) {
    $params['payment_status_id'] = CRM_Core_PseudoConstant::getKey('CRM_Contribute_BAO_Contribution', 'contribution_status_id', 'Pending');
    $params['payment_status'] = 'Pending';
    return $params;
  }

  /**
   * Set the payment status to Completed
   * @param \Civi\Payment\PropertyBag|array $params
   *
   * @return array
   */
  protected function setStatusPaymentCompleted($params) {
    $params['payment_status_id'] = CRM_Core_PseudoConstant::getKey('CRM_Contribute_BAO_Contribution', 'contribution_status_id', 'Completed');
    $params['payment_status'] = 'Completed';
    return $params;
  }

  /**
   * Get a "token" parameter that was inserted via javascript on the payment form (eg. paymentIntentID).
   *
   * @param string $parameterName
   * @param \Civi\Payment\PropertyBag $propertyBag
   * @param bool $required
   *
   * @return \Civi\Payment\PropertyBag
   * @throws \CRM_Core_Exception
   */
  protected function getTokenParameter($parameterName, $propertyBag, $required = TRUE) {
    // If we stored it via pre_approval_parameters it should already be in the params array
    if ($propertyBag->has($parameterName)) {
      return $propertyBag;
    }
    // If we're submitting without a confirmation page it should be in the $_POST array
    $parameterValue = CRM_Utils_Request::retrieve($parameterName, 'String');
    $propertyBag->setCustomProperty($parameterName, $parameterValue);

    if (empty($parameterValue) && $required) {
      Civi::log()->debug("{$parameterName} not found. \$params: " . print_r($propertyBag, TRUE));
      CRM_Core_Error::statusBounce(E::ts('Unable to complete payment! Missing %1.', [1 => $parameterName]));
    }
    return $propertyBag;
  }

  /**
   * This converts from \Civi\Payment\PropertyBag to array
   * It can be called on a propertyBag or an array.
   * @see https://github.com/civicrm/civicrm-core/pull/17507
   *
   * @param array|\Civi\Payment\PropertyBag $propertyBag
   * @param string $label
   *
   * @return mixed
   * @throws \ReflectionException
   */
  public function getPropertyBagAsArray($propertyBag, $label = 'default') {
    if ($propertyBag instanceof PropertyBag) {
      $reflectionClass = new ReflectionClass($propertyBag);
      $reflectionProperty = $reflectionClass->getProperty('props');
      $reflectionProperty->setAccessible(TRUE);
      $params = $reflectionProperty->getValue($propertyBag)['default'];
    }
    else {
      $params = $propertyBag;
    }
    return $params;
  }

}
