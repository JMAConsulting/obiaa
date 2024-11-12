<?php
use CRM_Biaproperty_ExtensionUtil as E;
return [
  'name' => 'Property',
  'table' => 'civicrm_property',
  'class' => 'CRM_Biaproperty_DAO_Property',
  'getInfo' => fn() => [
    'title' => E::ts('Property'),
    'title_plural' => E::ts('Properties'),
    'description' => E::ts('FIXME'),
    'log' => TRUE,
  ],
  'getPaths' => fn() => [
    'add' => 'civicrm/property/form?reset=1&action=add',
    'view' => 'civicrm/property/form?reset=1&action=view&id=[id]',
    'update' => 'civicrm/property/form?reset=1&action=update&id=[id]',
    'delete' => 'civicrm/property/form?reset=1&action=delete&id=[id]',
  ],
  'getIndices' => fn() => [
    'UI_property_address' => [
      'fields' => [
        'property_address' => TRUE,
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
      'description' => E::ts('Unique Property ID'),
      'primary_key' => TRUE,
      'auto_increment' => TRUE,
    ],
    'roll_no' => [
      'title' => E::ts('Roll No'),
      'sql_type' => 'varchar(255)',
      'input_type' => 'Text',
      'description' => E::ts('Roll #'),
    ],
    'created_id' => [
      'title' => E::ts('Created ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => E::ts('FK to Contact'),
      'entity_reference' => [
        'entity' => 'Contact',
        'key' => 'id',
        'on_delete' => 'CASCADE',
      ],
    ],
    'property_address' => [
      'title' => E::ts('Property Address'),
      'sql_type' => 'varchar(255)',
      'input_type' => 'Text',
      'description' => E::ts('Property Tax Roll Address'),
    ],
    'name' => [
      'title' => E::ts('Name'),
      'sql_type' => 'varchar(255)',
      'input_type' => 'Text',
      'description' => E::ts('Property Name'),
    ],
    'city' => [
      'title' => E::ts('City'),
      'sql_type' => 'varchar(64)',
      'input_type' => 'Text',
      'description' => E::ts('City this property is in'),
    ],
    'postal_code' => [
      'title' => E::ts('Postal Code'),
      'sql_type' => 'varchar(64)',
      'input_type' => 'Text',
      'description' => E::ts('postal code this property is in'),
    ],
    'modified_id' => [
      'title' => E::ts('Modified ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => E::ts('FK to Contact'),
      'entity_reference' => [
        'entity' => 'Contact',
        'key' => 'id',
        'on_delete' => 'CASCADE',
      ],
    ],
    'modified_date' => [
      'title' => E::ts('Modified Date'),
      'sql_type' => 'timestamp',
      'input_type' => NULL,
      'readonly' => TRUE,
      'description' => E::ts('When was the property was created or modified or deleted.'),
      'default' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
      'input_attrs' => [
        'label' => E::ts('Modified Date'),
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
    'non_mpac_property' => [
      'title' => E::ts('Non MPAC Property'),
      'sql_type' => 'boolean',
      'input_type' => 'CheckBox',
      'description' => E::ts('Field used to handle sync processes'),
      'default' => 0,
      'required' => TRUE,
    ],
  ],
];
