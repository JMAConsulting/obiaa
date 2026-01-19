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
        'api_entity' => 'CiviMobileEventPushNotification',
        'api_action' => 'send',
        'is_active' => TRUE,
      ],
    ],
  ],
  [
    'name' => 'Job_Notify_performers_about_due_soon_tasks',
    'entity' => 'Job',
    'cleanup' => 'always',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'run_frequency' => 'Always',
        'name' => 'Notify performers about due soon tasks',
        'description' => E::ts('Notify performers about due soon tasks'),
        'api_entity' => 'CiviMobileTimeTrackerPushNotification',
        'api_action' => 'send_due_soon',
        'is_active' => TRUE,
      ],
    ],
  ],
  [
    'name' => 'Job_Notify_performers_about_overdue_tasks',
    'entity' => 'Job',
    'cleanup' => 'always',
    'update' => 'always',
    'params' => [
      'version' => 4,
      'values' => [
        'run_frequency' => 'Daily',
        'name' => 'Notify performers about overdue tasks',
        'description' => E::ts('Notify performers about overdue tasks'),
        'api_entity' => 'CiviMobileTimeTrackerPushNotification',
        'api_action' => 'send_overdue',
        'is_active' => TRUE,
      ],
    ],
  ],
];
