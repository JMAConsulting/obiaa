<?php

namespace Civi\Api4\Action\PaymentMJW;

use CRM_Mjwshared_ExtensionUtil as E;
use Civi\Api4\CustomField;

/**
 * This API Action creates a payment. It is based on API3 Payment.create and API3 MJWPayment.create
 *
 */
class Create extends \Civi\Api4\Generic\AbstractCreateAction {

  public static function getCreateFields() {
    // Basically a copy of _civicrm_api3_payment_create_spec;
    $fields = [
      [
        'name' => 'contribution_id',
        'required' => TRUE,
        'description' => E::ts('Contribution ID'),
        'data_type' => 'Integer',
        'fk_entity' => 'Contribution',
        'input_type' => 'EntityRef',
      ],
      [
        'name' => 'total_amount',
        'required' => TRUE,
        'description' => E::ts('Total Payment Amount'),
        'data_type' => 'Float',
      ],
      [
        'name' => 'fee_amount',
        'description' => E::ts('Fee Amount'),
        'data_type' => 'Float',
      ],
      [
        'name' => 'payment_processor_id',
        'data_type' => 'Integer',
        'description' => E::ts('Payment Processor for this payment'),
        'fk_entity' => 'PaymentProcessor',
        'input_type' => 'EntityRef',
      ],
      [
        'name' => 'trxn_date',
        'description' => E::ts('Payment Date'),
        'data_type' => 'Datetime',
        'default' => 'now',
        'required' => TRUE,
      ],
      [
        'name' => 'is_send_contribution_notification',
        'description' => E::ts('Send out notifications based on contribution status change?'),
        'data_type' => 'Boolean',
        'default' => TRUE,
      ],
      [
        'name' => 'payment_instrument_id',
        'data_type' => 'Integer',
        'description' => E::ts('Payment Method (FK to payment_instrument option group values)'),
        'pseudoconstant' => [
          'optionGroupName' => 'payment_instrument',
          'optionEditPath' => 'civicrm/admin/options/payment_instrument',
        ],
      ],
      [
        'name' => 'card_type_id',
        'data_type' => 'Integer',
        'description' => E::ts('Card Type ID (FK to accept_creditcard option group values)'),
        'pseudoconstant' => [
          'optionGroupName' => 'accept_creditcard',
          'optionEditPath' => 'civicrm/admin/options/accept_creditcard',
        ],
      ],
      [
        'name' => 'trxn_result_code',
        'data_type' => 'String',
        'description' => E::ts('Transaction Result Code'),
      ],
      [
        'name' => 'trxn_id',
        'data_type' => 'String',
        'description' => E::ts('Transaction ID supplied by external processor. This may not be unique.'),
      ],
      [
        'name' => 'order_reference',
        'data_type' => 'String',
        'description' => E::ts('Payment Processor external order reference'),
      ],
      [
        'name' => 'check_number',
        'data_type' => 'String',
        'description' => E::ts('Check Number'),
      ],
      [
        'name' => 'pan_truncation',
        'type' => 'String',
        'description' => E::ts('PAN Truncation (Last 4 digits of credit card)'),
      ],
    ];
    $customFields = CustomField::get(FALSE)
      ->addSelect('custom_group_id:name', 'name', 'label', 'data_type')
      ->addWhere('custom_group_id.extends', '=', 'FinancialTrxn')
      ->execute();
    foreach ($customFields as $customField) {
      $customField['name'] = $customField['custom_group_id:name'] . '.' . $customField['name'];
      unset($customField['id'], $customField['custom_group_id:name']);
      $customField['description'] = $customField['label'];
      $fields[] = $customField;
    }
    return $fields;
  }

  public function fields(): array {
    return self::getCreateFields();
  }

  /**
   *
   * Note that the result class is that of the annotation below, not the h
   * in the method (which must match the parent class)
   *
   * @var \Civi\Api4\Generic\Result $result
   */
  public function _run(\Civi\Api4\Generic\Result $result) {
    $trxn = \CRM_Financial_BAO_Payment::create($this->values);

    $customFields = CustomField::get(FALSE)
      ->addSelect('id', 'custom_group_id:name', 'name', 'label', 'data_type')
      ->addWhere('custom_group_id.extends', '=', 'FinancialTrxn')
      ->execute();
    foreach ($customFields as $customField) {
      $key = $customField['custom_group_id:name'] . '.' . $customField['name'];
      if (isset($this->values[$key])) {
        $customParams['custom_' . $customField['id']] = $this->values[$key];
      }
    }
    if (!empty($customParams)) {
      $customParams['entity_id'] = $trxn->id;
      civicrm_api3('CustomValue', 'create', $customParams);
    }

    $result->exchangeArray($trxn);
    return $result;
  }

}
