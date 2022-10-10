# Payment Processor Implementation

This library provides two helper "traits":
- `CRM_Core_Payment_MJWTrait` - helpers to implement your `CRM_Core_Payment_Xx` class.
- `CRM_Core_Payment_MJWIPNTrait` - helpers to implement IPN/webhook processing for your payment processor.

## The Payment Processor

### doPayment()

```php
  public function doPayment(&$params, $component = 'contribute') {
    /* @var \Civi\Payment\PropertyBag $propertyBag */
    $propertyBag = $this->beginDoPayment($params);

    // Set payment pending
    $this->setStatusPaymentPending($propertyBag)

    // ... Handle the actual payment / communicate with external servers etc.

    // Payment succeeded?
    $this->setStatusPaymentCompleted($propertyBag)

    return $this->endDoPayment($propertyBag);
}
```

## changeSubscriptionAmount() (Edit recurring contribution)

```php
  public function changeSubscriptionAmount(&$message = '', $params = []) {
    $propertyBag = $this->beginChangeSubscriptionAmount($params);

    // ... Handle update subscription / communicate with external servers.

    // On error throw exception

    return TRUE;
  }
```

## updateSubscriptionBillingInfo() (Update payment info + address)

```php
  public function updateSubscriptionBillingInfo(&$message = '', $params = []) {
    $propertyBag = $this->beginUpdateSubscriptionBillingInfo($params);

    // ... Handle update billing info / communicate with external servers.

    // On error throw exception

    return TRUE;
  }
```

## doCancelRecurring() (Cancel recurring contribution)

```php
  public function doCancelRecurring(PropertyBag $propertyBag) {
    // By default we always notify the processor and we don't give the user the option
    // because supportsCancelRecurringNotifyOptional() = FALSE
    if (!$propertyBag->has('isNotifyProcessorOnCancelRecur')) {
      // If isNotifyProcessorOnCancelRecur is NOT set then we set our default
      $propertyBag->setIsNotifyProcessorOnCancelRecur(TRUE);
    }
    $notifyProcessor = $propertyBag->getIsNotifyProcessorOnCancelRecur();

    if (!$notifyProcessor) {
      return ['message' => E::ts('Successfully cancelled the subscription in CiviCRM ONLY.')];
    }

    if (!$propertyBag->has('recurProcessorID')) {
      $errorMessage = E::ts('The recurring contribution cannot be cancelled (No reference (contribution_recur.processor_id) found).');
      \Civi::log()->error($errorMessage);
      throw new \Civi\Payment\Exception\PaymentProcessorException($errorMessage);
    }

    // ... Handle cancel recurring / communicate with external servers.

    // If we failed to cancel
    if ($failed) {
      throw new \Civi\Payment\Exception\PaymentProcessorException($this->handleError(NULL, 'Failed to cancel'));
    }

    return ['message' => E::ts('Successfully cancelled the subscription at XYZ.net.')];
  }
```
