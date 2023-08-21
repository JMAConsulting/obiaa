<?php
use CRM_Mjwshared_ExtensionUtil as E;

// This enables custom fields for Grant entities
return [
  [
    'name' => 'cg_extend_objects:FinancialTrxn',
    'entity' => 'OptionValue',
    'cleanup' => 'always',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'cg_extend_objects',
        'label' => E::ts('Financial Transaction (Payment)'),
        'value' => 'FinancialTrxn',
        'name' => 'civicrm_financial_trxn',
        'is_reserved' => TRUE,
        'is_active' => TRUE,
      ],
    ],
    'match' => ['option_group_id', 'name'],
  ],
  [
    'name' => 'CustomGroup_Payment_details',
    'entity' => 'CustomGroup',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Payment_details',
        'title' => E::ts('Payment details'),
        'extends' => 'FinancialTrxn',
        'extends_entity_column_id' => NULL,
        'extends_entity_column_value' => NULL,
        'style' => 'Inline',
        'collapse_display' => FALSE,
        'help_pre' => E::ts(''),
        'help_post' => E::ts(''),
        'weight' => 30,
        'is_active' => TRUE,
        'is_multiple' => FALSE,
        'min_multiple' => NULL,
        'max_multiple' => NULL,
        'collapse_adv_display' => TRUE,
        'is_reserved' => FALSE,
        'is_public' => TRUE,
        'icon' => '',
      ],
      'match' => [
        'name',
      ],
    ],
  ],
];
