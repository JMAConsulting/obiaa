<?php

use Civi\CiviMobileAPI\PushNotification\Utils\PushNotificationSender;
use CRM_CiviMobileAPI_ExtensionUtil as E;

/**
 * This API get called when run schedule job "Notify performers about due tasks"
 *
 * @param $params
 *
 * @return array|void
 */
function civicrm_api3_civi_mobile_time_tracker_push_notification_send_due_soon($params) {
  if (!CRM_CiviMobileAPI_Utils_Extension::isTimeTrackerExtensionEnabled()) {
    return;
  }
  $currentDateTime = CRM_Utils_Date::getToday(NULL, 'Y-m-d H:i:s');
  $result = CRM_CiviMobileAPI_Utils_TimeTrackerPushNotification::sendDueSoonNotifications($currentDateTime);

  return [
    'values' => $result,
    'is_error' => 0,
  ];
}

/**
 * @param $params
 *
 * @return array|void
 */
function civicrm_api3_civi_mobile_time_tracker_push_notification_send_overdue($params) {
  if (!CRM_CiviMobileAPI_Utils_Extension::isTimeTrackerExtensionEnabled()) {
    return;
  }
  $currentDateTime = CRM_Utils_Date::getToday(NULL, 'Y-m-d H:i:s');
  $result = CRM_CiviMobileAPI_Utils_TimeTrackerPushNotification::sendOverdueNotifications($currentDateTime);

  return [
    'values' => $result,
    'is_error' => 0,
  ];
}
