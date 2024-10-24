<?php

use Civi\Api4\OptionValue;

if (empty(OptionValue::get(FALSE)
  ->addWhere('option_group_id:name', '=', 'cg_extend_objects')
  ->addWhere('value', '=', 'FinancialTrxn')
  ->execute()
  ->first())) {
  return [
    [
      'name' => 'cg_extend_objects:FinancialTrxn',
      'entity' => 'OptionValue',
      'cleanup' => 'unused',
      'update' => 'unmodified',
      'params' => [
        'version' => 4,
        'values' => [
          'option_group_id.name' => 'cg_extend_objects',
          'label' => ts('Financial Transaction (Payment)'),
          'value' => 'FinancialTrxn',
          'name' => 'civicrm_financial_trxn',
          'is_reserved' => TRUE,
          'is_active' => TRUE,
        ],
        'match' => ['option_group_id', 'name'],
      ],
    ],
  ];
}
return [];