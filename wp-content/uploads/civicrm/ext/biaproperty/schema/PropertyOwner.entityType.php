<?php
use CRM_Biaproperty_ExtensionUtil as E;
return [
  'name' => 'PropertyOwner',
  'table' => 'civicrm_property_owner',
  'class' => 'CRM_Biaproperty_DAO_PropertyOwner',
  'getInfo' => fn() => [
    'title' => E::ts('Property Owner'),
    'title_plural' => E::ts('Property Owners'),
    'description' => E::ts('FIXME'),
    'log' => TRUE,
  ],
  'getIndices' => fn() => [
    'UI_property_owner_key' => [
      'fields' => [
        'property_id' => TRUE,
        'owner_id' => TRUE,
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
      'description' => E::ts('Unique PropertyOwner ID'),
      'primary_key' => TRUE,
      'auto_increment' => TRUE,
    ],
    'property_id' => [
      'title' => E::ts('Property ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => E::ts('FK to Property'),
      'entity_reference' => [
        'entity' => 'Property',
        'key' => 'id',
        'on_delete' => 'CASCADE',
      ],
    ],
    'owner_id' => [
      'title' => E::ts('Owner ID'),
      'sql_type' => 'int unsigned',
      'input_type' => 'EntityRef',
      'description' => E::ts('FK to Contact'),
      'entity_reference' => [
        'entity' => 'Contact',
        'key' => 'id',
        'on_delete' => 'CASCADE',
      ],
    ],
    'is_voter' => [
      'title' => E::ts('Is Voter'),
      'sql_type' => 'boolean',
      'input_type' => 'Radio',
      'description' => E::ts('Is Vote?'),
      'default' => FALSE,
    ],
  ],
];
