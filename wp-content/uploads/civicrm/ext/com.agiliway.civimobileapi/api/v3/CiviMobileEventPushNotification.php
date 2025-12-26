<?php

use Civi\CiviMobileAPI\PushNotification\Utils\PushNotificationSender;
use CRM_CiviMobileAPI_ExtensionUtil as E;

/**
 * This API get called when run schedule job "Notify all participants that event is going to start"
 *
 * @param $params
 *
 * @return mixed
 * @throws \Exception
 */
function civicrm_api3_civi_mobile_event_push_notification_send($params) {
  $data_time_min = CRM_Utils_Date::getToday(NULL, 'Y-m-d H:i:s');
  $data_time_max = date('Y-m-d H:i:s', strtotime($data_time_min . "+1 hour"));
  $result = [];

  $events = civicrm_api4('Event', 'get', [
    'select' => [
      'title',
      'id',
      'start_date',
    ],
    'where' => [
      ['start_date', 'BETWEEN', [$data_time_min, $data_time_max]],
      ['is_active', '=', TRUE],
    ],
    'checkPermissions' => FALSE,
  ])->getArrayCopy();

  foreach ($events as $event) {
    $participants = civicrm_api4('Participant', 'get', [
      'select' => [
        'contact_id',
      ],
      'where' => [
        ['event_id', '=', $event['id']],
      ],
      'checkPermissions' => FALSE,
    ]);

    $text = E::ts('Event start at') . ' ' . $event['start_date'];
    $data = [
      'entity' => 'Event',
      'id' => strval($event['id']),
      'body' => $text
    ];

    $contacts = [];

    foreach ($participants as $participant) {
      $contacts[] = $participant['contact_id'];
    }

    PushNotificationSender::send($event['title'], $text, $contacts, $data);

    $result[] = [
      'event_id' => $event['id'],
      'status' => 'Send notifications'
    ];
  }

  return [
    'values' => $result,
    'is_error' => '0'
  ];
}
