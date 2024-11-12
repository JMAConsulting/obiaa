<?php
use CRM_Biaproperty_ExtensionUtil as E;
return [
  'name' => 'Unit',
  'table' => 'civicrm_unit',
  'class' => 'CRM_Biaproperty_DAO_Unit',
  'getInfo' => fn() => [
    'title' => E::ts('Unit'),
    'title_plural' => E::ts('Units'),
    'description' => E::ts('FIXME'),
    'log' => TRUE,
  ],
  'getIndices' => fn() => [
    'UI_address_id' => [
      'fields' => [
        'address_id' => TRUE,
      ],
      'unique' => TRUE,
    ],
    'UI_source_record_id' => [
      'fields' => [
        'source_record_id' => TRUE,
        'source_record' => TRUE,
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
      'description' => E::ts('Unique Unit ID'),
      'primary_key' => TRUE,
      'auto_increment' => TRUE,
    ],
    'unit_size' => [
      'title' => E::ts('Unit Size'),
      'sql_type' => 'decimal(20,2)',
      'input_type' => 'Number',
      'description' => E::ts('Unit Size'),
    ],
    'unit_price' => [
      'title' => E::ts('Unit Price'),
      'sql_type' => 'decimal(20,2)',
      'input_type' => 'Text',
      'description' => E::ts('Unit Price'),
    ],
    'unit_status' => [
      'title' => E::ts('Unit Status'),
      'sql_type' => 'int unsigned',
      'input_type' => 'Select',
      'description' => E::ts('Unit Status'),
      'pseudoconstant' => [
        'option_group_name' => 'unit_status',
      ],
      'entity_reference' => [
        'entity' => 'OptionValue',
        'key' => 'id',
      ],
    ],
    'mls_listing_link' => [
      'title' => E::ts('Mls Listing Link'),
      'sql_type' => 'varchar(255)',
      'input_type' => 'Url',
      'description' => E::ts('Unit Status'),
    ],
    'unit_photo' => [
      'title' => E::ts('Unit Photo'),
      'sql_type' => 'int unsigned',
      'input_type' => 'Text',
      'description' => E::ts('Unit Photo'),
      'entity_reference' => [
        'entity' => 'File',
        'key' => 'id',
        'on_delete' => 'CASCADE',
      ],
    ],
    'unit_location' => [
      'title' => E::ts('Unit Location'),
      'sql_type' => 'varchar(255)',
      'input_type' => 'Text',
      'description' => E::ts('Unit Location'),
    ],
    'address_id' => [
      'title' => E::ts('Address ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => E::ts('FK to Address'),
      'entity_reference' => [
        'entity' => 'Address',
        'key' => 'id',
        'on_delete' => 'SET NULL',
      ],
    ],
    'property_id' => [
      'title' => E::ts('Property ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'required' => TRUE,
      'description' => E::ts('FK to civicrm_property'),
      'entity_reference' => [
        'entity' => 'Property',
        'key' => 'id',
        'on_delete' => 'CASCADE',
      ],
    ],
    'source_record_id' => [
      'title' => E::ts('Source Record ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'Number',
      'description' => E::ts('Field used to handle sync processes'),
      'default' => NULL,
    ],
    'source_record' => [
      'title' => E::ts('Source Record'),
      'sql_type' => 'varchar(255)',
      'input_type' => 'Text',
      'description' => E::ts('Field used to handle sync processes'),
      'default' => NULL,
    ],
  ],
];
