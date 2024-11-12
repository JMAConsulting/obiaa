<?php
use CRM_Biaproperty_ExtensionUtil as E;
return [
  'name' => 'UnitBusiness',
  'table' => 'civicrm_unit_business',
  'class' => 'CRM_Biaproperty_DAO_UnitBusiness',
  'getInfo' => fn() => [
    'title' => E::ts('Unit Business'),
    'title_plural' => E::ts('Unit Businesses'),
    'description' => E::ts('FIXME'),
    'log' => TRUE,
  ],
  'getIndices' => fn() => [
    'UI_property_unit_key' => [
      'fields' => [
        'unit_id' => TRUE,
        'business_id' => TRUE,
      ],
      'unique' => TRUE,
    ],
  ],
  'getFields' => fn() => [
    'id' => [
      'title' => E::ts('ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'Number',
      'required' => TRUE,
      'description' => E::ts('Unique UnitBusiness ID'),
      'primary_key' => TRUE,
      'auto_increment' => TRUE,
    ],
    'unit_id' => [
      'title' => E::ts('Unit ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => E::ts('FK to Unit'),
      'entity_reference' => [
        'entity' => 'Unit',
        'key' => 'id',
        'on_delete' => 'CASCADE',
      ],
    ],
    'business_id' => [
      'title' => E::ts('Business ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => E::ts('FK to Contact'),
      'entity_reference' => [
        'entity' => 'Contact',
        'key' => 'id',
        'on_delete' => 'SET NULL',
      ],
    ],
  ],
];
