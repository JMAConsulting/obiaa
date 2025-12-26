<?php

use Civi\CiviMobileAPI\PushNotification\Utils\PushNotificationSender;
use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_Utils_TimeTrackerPushNotification {

  private const MINUTES_BEFORE_DUE = 1440;

  public static function sendOverdueNotifications(string $currentDateTime): array {
    $dayAgo = date('Y-m-d H:i:s', strtotime($currentDateTime . "-1 day"));
    $result = [];

    $timeTrackerTasks = CRM_CiviMobileAPI_Utils_TimeTracker::getTasksEndingBetween($dayAgo, $currentDateTime);

    foreach ($timeTrackerTasks as $timeTrackerTask) {
      $text = E::ts('Reminder: Task ') . $timeTrackerTask['title'] . E::ts(' is overdue.');
      $result[] = self::sendNotifications($timeTrackerTask, $text);
    }

    return $result;
  }

  public static function sendDueSoonNotifications(string $currentDateTime): array {
    $reminderTime = !empty(Civi::settings()->get("civimobile_push_notification_reminder_before_task_due"))
      ? (int) Civi::settings()->get("civimobile_push_notification_reminder_before_task_due")
      : self::MINUTES_BEFORE_DUE;

    $dateTimeMax = date('Y-m-d H:i:s', strtotime($currentDateTime . " +{$reminderTime} minutes"));
    $result = [];

    $timeTrackerTasks = CRM_CiviMobileAPI_Utils_TimeTracker::getTasksEndingBetween($currentDateTime, $dateTimeMax);
    $idsToUpdate = [];
    foreach ($timeTrackerTasks as $timeTrackerTask) {
      if (!$timeTrackerTask['is_due_soon_notified']) {
        $idsToUpdate[] = $timeTrackerTask['id'];
        $text = E::ts('Reminder: Task ') . $timeTrackerTask['title'] . E::ts(' is due soon.');
        $result[] = self::sendNotifications($timeTrackerTask, $text);
      }
    }

    if (!empty($idsToUpdate)) {
      civicrm_api4('TimeTrackerTask', 'update', [
        'values' => ['is_due_soon_notified' => TRUE],
        'where' => [
          ['id', 'IN', $idsToUpdate],
        ],
        'checkPermissions' => FALSE,
      ]);
    }

    return $result;
  }

  private static function sendNotifications($timeTrackerTask, $text) {
    $contacts = CRM_TimeTracker_Utils_Performers::getPerformers($timeTrackerTask['project_id'], $timeTrackerTask['id']);

    if (empty($contacts)) {
      return [];
    }

    $data = [
      'entity' => 'Task',
      'id' => strval($timeTrackerTask['id']),
      'body' => $text,
    ];

    PushNotificationSender::send($timeTrackerTask['title'], $text, $contacts, $data);

    return [
      'task_id' => $timeTrackerTask['id'],
      'status' => 'Send due soon/overdue notifications',
    ];
  }

}
