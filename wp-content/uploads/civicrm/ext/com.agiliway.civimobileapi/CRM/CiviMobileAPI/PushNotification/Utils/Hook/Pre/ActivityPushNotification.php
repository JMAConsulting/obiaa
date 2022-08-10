<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;
use CRM_CiviMobileAPI_PushNotification_Helper as PushNotification_Helper;

class CRM_CiviMobileAPI_PushNotification_Utils_Hook_Pre_ActivityPushNotification extends CRM_CiviMobileAPI_PushNotification_Utils_BasePushNotificationManager {

  /**
   * List of actions text
   *
   * @var array
   */
  private $actionText = [
    'delete' => '%display_name has deleted activity.'
  ];

  /**
   * @inheritdoc
   */
  protected function getContact() {
    if ($this->action === 'delete') {
      return PushNotification_Helper::getActivityContacts($this->id, TRUE);
    }

    return [];
  }

  /**
   * Gets id of current case id
   *
   * @return array
   * @throws \CiviCRM_API3_Exception
   */
  private function getCaseId() {
    return civicrm_api3('Case', 'get', [
      'return' => 'id',
      'activity_id' => $this->id,
    ]);
  }

  /**
   * @return array|null
   */
  private function getCaseIdByActivity($activityId) {
    if (empty($this->id)) {
      return null;
    }

    try {
      $case = civicrm_api3('Case', 'get', [
        'return' => 'id',
        'activity_id' => $activityId,
      ]);
    } catch (Exception $e) {
      return null;
    }

    return $case['id'];
  }

  public function sendNotification() {
    if ($this->action == 'delete') {
      $isRecentlyDeleted = false;
      $caseId = $this->getCaseIdByActivity($this->id);
      if (!empty($caseId)) {
        $caseDeleteService = CRM_CiviMobileAPI_PushNotification_Utils_CaseDeleteService::getInstance();
        $isRecentlyDeleted = $caseDeleteService->isCaseRecentlyDeleted($caseId);
      }

      if (!$isRecentlyDeleted) {
        parent::sendNotification();
      }
    } else {
      parent::sendNotification();
    }
  }

  /**
   * @inheritdoc
   */
  protected function getTitle() {
    if ($this->action === 'delete' && $this->id) {
      try {
        $activityName = civicrm_api3('Activity', 'getvalue', ['return' => 'subject', 'id' => $this->id]);
      } catch (Exception $e) {
        $activityName = NULL;
      }
    }

    return (!empty($activityTitle)) ?  $activityTitle : E::ts('Activity');
  }

  /**
   * @inheritdoc
   */
  protected function getText() {
    return isset($this->actionText[$this->action]) ? E::ts($this->actionText[$this->action]) : $this->action;
  }

}
