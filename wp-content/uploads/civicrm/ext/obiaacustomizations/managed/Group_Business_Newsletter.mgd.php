<?php

use CRM_Obiaacustomizations_ExtensionUtil as E;

return [
  [
    'name' => 'Group_Business_Newsletter',
    'entity' => 'Group',
    'cleanup' => 'unused',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'Business_Newsletter',
        'title' => E::ts('Business Newsletter'),
        'group_type:name' => [
          'Mailing List',
        ],
        'frontend_title' => E::ts('Business Newsletter'),
      ],
      'match' => [
        'name',
      ],
    ],
  ],
];
