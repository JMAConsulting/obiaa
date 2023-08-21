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

return [
  0 =>
  [
    'name' => 'ProcessPaymentProcessorWebhooks',
    'entity' => 'Job',
    'update' => 'never',
    'params' =>
    [
      'version' => 3,
      'name' => 'Process PaymentProcessor Webhooks',
      'description' => 'Process incomplete payment processor webhooks',
      'run_frequency' => 'Always',
      'api_entity' => 'Job',
      'api_action' => 'process_paymentprocessor_webhooks',
      'parameters' => 'delete_old=-3 month',
    ],
  ],
];
