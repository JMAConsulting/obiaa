<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

return [
  [
    'name' => 'Job_Civimobile_clean_old_push_notification_messages',
    'entity' => 'Job',
    'cleanup' => 'always',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'run_frequency' => 'Daily',
        'name' => 'Civimobile clean old push notification messages',
        'description' => E::ts('Clean old push notification messages'),
        'api_entity' => 'PushNotificationMessages',
        'api_action' => 'clear_old',
        'is_active' => TRUE,
      ],
    ],
  ],

  [
    'name' => 'Job_Notify_all_participants_that_event_is_going_to_start',
    'entity' => 'Job',
    'cleanup' => 'always',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'run_frequency' => 'Hourly',
        'name' => 'Notify all participants that event is going to start',
        'description' => E::ts('Notify all participants that event is going to start'),
        'api_entity' => 'PushNotificationEventReminder',
        'api_action' => 'send',
        'is_active' => TRUE,
      ],
    ],
  ],
];