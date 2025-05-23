<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

return [
  [
    'name' => 'CustomGroup_civi_mobile_qr_uses',
    'entity' => 'CustomGroup',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'civi_mobile_qr_uses',
        'title' => E::ts('Qr options'),
        'extends' => 'Event',
        'style' => 'Inline',
        'weight' => 10,
        'is_reserved' => TRUE,
        'is_public' => FALSE,
        'is_active' => TRUE,
      ],
      'match' => [
        'name',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_civi_mobile_qr_uses_CustomField_civi_mobile_is_qr_used',
    'entity' => 'CustomField',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'civi_mobile_qr_uses',
        'name' => 'civi_mobile_is_qr_used',
        'label' => E::ts('Is qr code used for this Event?'),
        'data_type' => 'Boolean',
        'html_type' => 'Radio',
        'default_value' => '0',
        'is_view' => TRUE,
        'is_active' => TRUE,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],



  [
    'name' => 'CustomGroup_civi_mobile_agenda_participant',
    'entity' => 'CustomGroup',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'civi_mobile_agenda_participant',
        'title' => E::ts('Agenda'),
        'extends' => 'Participant',
        'style' => 'Inline',
        'weight' => 9,
        'is_reserved' => TRUE,
        'is_public' => FALSE,
        'is_active' => TRUE,
      ],
      'match' => [
        'name',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_civi_mobile_agenda_participant_CustomField_civi_mobile_agenda_participant_bio',
    'entity' => 'CustomField',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'civi_mobile_agenda_participant',
        'name' => 'civi_mobile_agenda_participant_bio',
        'label' => E::ts('Bio'),
        'data_type' => 'Memo',
        'html_type' => 'TextArea',
        'attributes' => 'rows=4, cols=60',
        'is_active' => TRUE,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],



  [
    'name' => 'CustomGroup_civi_mobile_qr_codes',
    'entity' => 'CustomGroup',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'civi_mobile_qr_codes',
        'title' => E::ts('Qr codes'),
        'extends' => 'Participant',
        'style' => 'Inline',
        'weight' => 8,
        'is_reserved' => TRUE,
        'is_public' => FALSE,
        'is_active' => TRUE,
      ],
      'match' => [
        'name',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_civi_mobile_qr_codes_CustomField_civi_mobile_qr_event_id',
    'entity' => 'CustomField',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'civi_mobile_qr_codes',
        'name' => 'civi_mobile_qr_event_id',
        'label' => E::ts('QR Event id'),
        'html_type' => 'Text',
        'default_value' => '0',
        'is_view' => TRUE,
        'is_active' => TRUE,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_civi_mobile_qr_codes_CustomField_civi_mobile_qr_code',
    'entity' => 'CustomField',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'civi_mobile_qr_codes',
        'name' => 'civi_mobile_qr_code',
        'label' => E::ts('Qr hash code'),
        'html_type' => 'Text',
        'default_value' => '0',
        'is_view' => TRUE,
        'is_active' => TRUE,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_civi_mobile_qr_codes_CustomField_civi_mobile_qr_image',
    'entity' => 'CustomField',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'civi_mobile_qr_codes',
        'name' => 'civi_mobile_qr_image',
        'label' => E::ts('QR image url'),
        'html_type' => 'Text',
        'default_value' => '0',
        'is_view' => TRUE,
        'is_active' => TRUE,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],



  [
    'name' => 'CustomGroup_civi_mobile_public_info',
    'entity' => 'CustomGroup',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'civi_mobile_public_info',
        'title' => E::ts('Public Info'),
        'extends' => 'Participant',
        'style' => 'Inline',
        'weight' => 7,
        'is_reserved' => TRUE,
        'is_active' => TRUE,
        'is_public' => FALSE,
      ],
      'match' => [
        'name',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_civi_mobile_public_info_CustomField_public_key',
    'entity' => 'CustomField',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'civi_mobile_public_info',
        'name' => 'public_key',
        'label' => E::ts('Public key'),
        'html_type' => 'Text',
        'is_view' => TRUE,
        'is_active' => TRUE,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],



  [
    'name' => 'CustomGroup_civi_mobile_survey',
    'entity' => 'CustomGroup',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'civi_mobile_survey',
        'title' => E::ts('Survey`s additional info'),
        'extends' => 'Activity',
        'style' => 'Inline',
        'weight' => 6,
        'is_reserved' => TRUE,
        'is_public' => FALSE,
        'is_active' => TRUE,
      ],
      'match' => [
        'name',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_civi_mobile_survey_CustomField_civi_mobile_survey_gotv_status',
    'entity' => 'CustomField',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'civi_mobile_survey',
        'name' => 'civi_mobile_survey_gotv_status',
        'label' => E::ts('Is GOTV?'),
        'data_type' => 'Boolean',
        'html_type' => 'Radio',
        'default_value' => '0',
        'is_view' => TRUE,
        'is_active' => TRUE,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],



  [
    'name' => 'CustomGroup_civi_mobile_allow_registration',
    'entity' => 'CustomGroup',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'name' => 'civi_mobile_allow_registration',
        'title' => E::ts('Allow Online registration in CiviMobile'),
        'extends' => 'Event',
        'style' => 'Inline',
        'collapse_display' => TRUE,
        'weight' => 5,
        'is_reserved' => TRUE,
        'is_public' => FALSE,
        'is_active' => TRUE,
      ],
      'match' => [
        'name',
      ],
    ],
  ],
  [
    'name' => 'CustomGroup_civi_mobile_allow_registration_CustomField_civi_mobile_is_event_mobile_registration',
    'entity' => 'CustomField',
    'cleanup' => 'never',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'custom_group_id.name' => 'civi_mobile_allow_registration',
        'name' => 'civi_mobile_is_event_mobile_registration',
        'label' => E::ts('Allow Online registration in CiviMobile?'),
        'data_type' => 'Boolean',
        'html_type' => 'Radio',
        'default_value' => '1',
        'is_view' => TRUE,
        'is_active' => TRUE,
      ],
      'match' => [
        'name',
        'custom_group_id',
      ],
    ],
  ],
];