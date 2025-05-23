<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

return [
  [
    'name' => 'OptionGroup_civi_mobile_tabs',
    'entity' => 'OptionGroup',
    'cleanup' => 'always',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'civi_mobile_tabs',
        'title' => E::ts('CiviMobile Tabs'),
        'data_type' => 'String',
        'is_reserved' => TRUE,
        'is_locked' => TRUE,
        'is_active' => TRUE,
        'option_value_fields' => [
          'name',
          'label',
        ],
      ],
      'match' => [
        'name',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_civi_mobile_tabs_OptionValue_civi_mobile_tab_calendar',
    'entity' => 'OptionValue',
    'cleanup' => 'always',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'civi_mobile_tabs',
        'label' => E::ts('Calendar'),
        'value' => 'civi_mobile_tab_calendar',
        'name' => 'civi_mobile_tab_calendar',
        'weight' => 1,
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_civi_mobile_tabs_OptionValue_civi_mobile_tab_events',
    'entity' => 'OptionValue',
    'cleanup' => 'always',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'civi_mobile_tabs',
        'label' => E::ts('Events'),
        'value' => 'civi_mobile_tab_events',
        'name' => 'civi_mobile_tab_events',
        'weight' => 2,
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_civi_mobile_tabs_OptionValue_civi_mobile_tab_cases',
    'entity' => 'OptionValue',
    'cleanup' => 'always',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'civi_mobile_tabs',
        'label' => E::ts('Cases'),
        'value' => 'civi_mobile_tab_cases',
        'name' => 'civi_mobile_tab_cases',
        'weight' => 3,
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_civi_mobile_tabs_OptionValue_civi_mobile_tab_activities',
    'entity' => 'OptionValue',
    'cleanup' => 'always',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'civi_mobile_tabs',
        'label' => E::ts('Activities'),
        'value' => 'civi_mobile_tab_activities',
        'name' => 'civi_mobile_tab_activities',
        'weight' => 4,
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_civi_mobile_tabs_OptionValue_civi_mobile_tab_contacts',
    'entity' => 'OptionValue',
    'cleanup' => 'always',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'civi_mobile_tabs',
        'label' => E::ts('Contacts'),
        'value' => 'civi_mobile_tab_contacts',
        'name' => 'civi_mobile_tab_contacts',
        'weight' => 5,
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_civi_mobile_tabs_OptionValue_civi_mobile_tab_relationships',
    'entity' => 'OptionValue',
    'cleanup' => 'always',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'civi_mobile_tabs',
        'label' => E::ts('Relationships'),
        'value' => 'civi_mobile_tab_relationships',
        'name' => 'civi_mobile_tab_relationships',
        'weight' => 6,
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_civi_mobile_tabs_OptionValue_civi_mobile_tab_memberships',
    'entity' => 'OptionValue',
    'cleanup' => 'always',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'civi_mobile_tabs',
        'label' => E::ts('Memberships'),
        'value' => 'civi_mobile_tab_memberships',
        'name' => 'civi_mobile_tab_memberships',
        'weight' => 7,
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_civi_mobile_tabs_OptionValue_civi_mobile_tab_contributions',
    'entity' => 'OptionValue',
    'cleanup' => 'always',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'civi_mobile_tabs',
        'label' => E::ts('Contributions'),
        'value' => 'civi_mobile_tab_contributions',
        'name' => 'civi_mobile_tab_contributions',
        'weight' => 8,
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_civi_mobile_tabs_OptionValue_civi_mobile_tab_notes',
    'entity' => 'OptionValue',
    'cleanup' => 'always',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'civi_mobile_tabs',
        'label' => E::ts('Notes'),
        'value' => 'civi_mobile_tab_notes',
        'name' => 'civi_mobile_tab_notes',
        'weight' => 9,
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_civi_mobile_tabs_OptionValue_civi_mobile_tab_groups',
    'entity' => 'OptionValue',
    'cleanup' => 'always',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'civi_mobile_tabs',
        'label' => E::ts('Groups'),
        'value' => 'civi_mobile_tab_groups',
        'name' => 'civi_mobile_tab_groups',
        'weight' => 10,
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_civi_mobile_tabs_OptionValue_civi_mobile_tab_tags',
    'entity' => 'OptionValue',
    'cleanup' => 'always',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'civi_mobile_tabs',
        'label' => E::ts('Tags'),
        'value' => 'civi_mobile_tab_tags',
        'name' => 'civi_mobile_tab_tags',
        'weight' => 11,
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_civi_mobile_tabs_OptionValue_civi_mobile_tab_surveys',
    'entity' => 'OptionValue',
    'cleanup' => 'always',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'civi_mobile_tabs',
        'label' => E::ts('Surveys'),
        'value' => 'civi_mobile_tab_surveys',
        'name' => 'civi_mobile_tab_surveys',
        'weight' => 12,
        'is_active' => TRUE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_civi_mobile_tabs_OptionValue_civi_mobile_tab_time_tracker',
    'entity' => 'OptionValue',
    'cleanup' => 'always',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'civi_mobile_tabs',
        'label' => E::ts('TimeTracker'),
        'value' => 'civi_mobile_tab_time_tracker',
        'name' => 'civi_mobile_tab_time_tracker',
        'weight' => 13,
        'is_active' => FALSE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
  [
    'name' => 'OptionGroup_civi_mobile_tabs_OptionValue_civi_mobile_tab_appointment',
    'entity' => 'OptionValue',
    'cleanup' => 'always',
    'update' => 'unmodified',
    'params' => [
      'version' => 4,
      'values' => [
        'option_group_id.name' => 'civi_mobile_tabs',
        'label' => E::ts('Appointment'),
        'value' => 'civi_mobile_tab_appointment',
        'name' => 'civi_mobile_tab_appointment',
        'weight' => 14,
        'is_active' => FALSE,
      ],
      'match' => [
        'option_group_id',
        'name',
        'value',
      ],
    ],
  ],
];