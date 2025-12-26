<?php

namespace Civi\CiviMobileAPI\PushNotification\Entity;

use Civi\CiviMobileAPI\PushNotification\Utils\PushNotificationSender;
use CRM_CiviMobileAPI_Utils_TimeTracker;
use CRM_CiviMobileAPI_ExtensionUtil as E;

class TimeTrackerPushNotification extends BasePushNotification {

  private $actionText;

  public const ACTION_ASSIGN = 'assign';

  public const ACTION_REMOVE = 'remove';

  public const ACTION_UPDATE = 'update';

  public const ACTION_ARCHIVE = 'archive';

  public const ACTION_DELETE  = 'delete';

  public const ACTION_ADD_CONTACT  = 'addContact';

  public const ACTION_REMOVE_CONTACT  = 'removeContact';

  public const ENTITY_TASK = 'Task';

  public const ENTITY_TASK_DELETE = 'TaskDelete';

  public const ENTITY_PROJECT = 'Project';

  public const ENTITY_PROJECT_DELETE = 'ProjectDelete';

  function __construct($action, $entity, $id, $entityInstance) {
    parent::__construct($action, $entity, $id, $entityInstance);

    $this->actionText = [
      self::ACTION_ASSIGN => E::ts('You have been assigned to a new task in Project:') . ' %project_name, ' . E::ts('Task:') . ' %task_name.',
      self::ACTION_REMOVE => E::ts('You have been removed from the task in Project:') . ' %project_name, ' . E::ts('Task:') . ' %task_name.',
      self::ACTION_UPDATE => E::ts('Your task') . ' %task_name ' . E::ts('in project') . ' %project_name ' . E::ts('has been updated.'),
      self::ACTION_ARCHIVE => E::ts('Project') . ' %project_name ' . E::ts('has been archived.'),
      self::ACTION_DELETE => E::ts('Project') . ' %project_name ' . E::ts('has been deleted.'),
      self::ACTION_ADD_CONTACT => E::ts('You have been added to the project') . ' %project_name ' . E::ts('as a') . ' %role.',
      self::ACTION_REMOVE_CONTACT => E::ts('You have been removed from the project') . ' %project_name.',
    ];

    if ($entity == self::ENTITY_TASK || $entity == self::ENTITY_TASK_DELETE) {
      $taskData = CRM_CiviMobileAPI_Utils_TimeTracker::getTaskById($id);

      foreach ($taskData as $key => $value) {
        $this->entityInstance->{$key} = $value;
      }
    } elseif ($entity == self::ENTITY_PROJECT || $entity == self::ENTITY_PROJECT_DELETE) {
      $title = CRM_CiviMobileAPI_Utils_TimeTracker::getProjectTitleById($id);
      $this->entityInstance->title = $title;
    }
  }

  public function sendNotification() {
    $contacts = $this->getContacts();
    $message = $this->getMessage();
    $title = $this->getTitle();

    if (empty($contacts) || empty($title)) {
      return;
    }

    $id = $this->id;
    if ($this->entity == self::ENTITY_TASK_DELETE) {
      $id = CRM_CiviMobileAPI_Utils_TimeTracker::getProjectIdByTask($this->id);
    }

    $data = [
      'entity' => $this->entity,
      'id' => (string) $id,
      'body' => $message,
    ];

    PushNotificationSender::send($title, $message, $contacts, $data);
  }

  protected function getTitle() {
    if ($this->entity == self::ENTITY_PROJECT_DELETE || $this->entity == self::ENTITY_PROJECT) {
      return $this->entityInstance->title;
    }
    return $this->entityInstance->task_title;
  }

  protected function getMessage() {
    $message = $this->actionText[$this->action];

    if ($this->entity == self::ENTITY_PROJECT_DELETE || $this->entity == self::ENTITY_PROJECT) {
      $message = str_replace('%role', $this->entityInstance->role, $message);

      return str_replace('%project_name', $this->entityInstance->title, $message);
    }
    $message = str_replace('%task_name', $this->entityInstance->task_title, $message);

    return str_replace('%project_name', $this->entityInstance->project_title, $message);
  }

  protected function getContacts() {
    if (!isset($this->entityInstance->contact_id)) {
      return NULL;
    }
    if (is_array($this->entityInstance->contact_id)) {
      return array_values($this->entityInstance->contact_id);
    }

    return [$this->entityInstance->contact_id];
  }

}
