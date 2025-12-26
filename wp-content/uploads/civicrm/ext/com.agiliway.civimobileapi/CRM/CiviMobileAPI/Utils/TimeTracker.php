<?php

class CRM_CiviMobileAPI_Utils_TimeTracker {

  /**
   * Is "com.agiliway.time-tracker" installed
   *
   * @return bool
   */
  public static function isTimeTrackerInstalled() {
    return CRM_CiviMobileAPI_Utils_ExtensionHelper::isInstalled('com.agiliway.time-tracker');
  }

  public static function getProjectIdByTask($taskId) {
    $timeTrackerTask = civicrm_api4('TimeTrackerTask', 'get', [
      'where' => [
        ['id', '=', $taskId],
      ],
      'checkPermissions' => FALSE,
    ])->first();

    return $timeTrackerTask['project_id'];
  }

  public static function getTaskById($taskId) {
    $timeTrackerTask = civicrm_api4('TimeTrackerTask', 'get', [
      'select' => ['title', 'project_id.title'],
      'where' => [
        ['id', '=', $taskId],
      ],
      'checkPermissions' => FALSE,
    ])->first();

    return [
      'task_title' => $timeTrackerTask['title'],
      'project_title' => $timeTrackerTask['project_id.title'],
    ];
  }

  public static function getTasksEndingBetween($dateTimeMin, $dateTimeMax) {
    return civicrm_api4('TimeTrackerTask', 'get', [
      'select' => ['title', 'id', 'end_time', 'project_id', 'is_due_soon_notified'],
      'where' => [
        ['end_time', 'BETWEEN', [$dateTimeMin, $dateTimeMax]],
        ['is_enabled', '=', TRUE],
        ['column_id.is_done', '=', FALSE],
      ],
      'checkPermissions' => FALSE,
    ])->getArrayCopy();
  }

  public static function getProjectTitleById($projectId) {
    return civicrm_api4('TimeTrackerProject', 'get', [
      'select' => ['title'],
      'where' => [
        ['id', '=', $projectId],
      ],
      'checkPermissions' => FALSE,
    ])->first()['title'];
  }

}
