<?php

use CRM_Obiaacustomizations_ExtensionUtil as E;

return [
  [
    'name' => 'CustomField_Opt_in_to_receive_communication_',
    'entity' => 'CustomField',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'Business_Category',
        'name' => 'Opt_in_to_receive_communication_',
        'label' => E::ts('Opt in to receive communication?'),
        'data_type' => 'Boolean',
        'html_type' => 'Radio',
        'default_value' => '1',
        'text_length' => 255,
        'note_columns' => 60,
        'note_rows' => 4,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];
