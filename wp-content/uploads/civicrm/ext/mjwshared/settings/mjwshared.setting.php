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

use CRM_Mjwshared_ExtensionUtil as E;

return [
  'mjwshared_refundpaymentui' => [
    'name' => 'mjwshared_refundpaymentui',
    'type' => 'Boolean',
    'html_type' => 'checkbox',
    'default' => 1,
    'is_domain' => 1,
    'is_contact' => 0,
    'title' => E::ts('Enable refund payment via UI?'),
    'description' => E::ts('Enables a "Refund payment" option next to the edit payment option on Payments. Find payments by expanding contributions.
    For more detail see the <a href="%1">Refund documentation</a>', [1 => 'https://docs.civicrm.org/mjwshared/en/latest/refunds/']),
    'html_attributes' => [],
    'settings_pages' => [
      'stripe' => [
        'weight' => 21,
      ]
    ],
  ],
  'mjwshared_jsdebug' => [
    'name' => 'mjwshared_jsdebug',
    'type' => 'Boolean',
    'html_type' => 'checkbox',
    'default' => 0,
    'is_domain' => 1,
    'is_contact' => 0,
    'title' => E::ts('Enable Javascript debugging?'),
    'description' => E::ts('Enables debug logging to browser console for javascript based payment processors.'),
    'html_attributes' => [],
    'settings_pages' => [
      'stripe' => [
        'weight' => 99,
      ]
    ],
  ],
];
