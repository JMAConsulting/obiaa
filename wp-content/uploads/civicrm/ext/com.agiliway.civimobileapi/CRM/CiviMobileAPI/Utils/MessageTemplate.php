<?php

/**
 * Class provide MessageTemplate helper methods
 */
class CRM_CiviMobileAPI_Utils_MessageTemplate {

  /**
   * Get message template info by workflow name
   *
   * @param $workflowName
   *
   * @return array|bool
   */
  public static function getByWorkflowId($workflowName) {
    $messageTemplate = civicrm_api4('MessageTemplate', 'get', [
      'where' => [
        ['workflow_name', '=', $workflowName],
        ['is_default', '=', TRUE],
      ],
      'checkPermissions' => FALSE,
    ])->first();

    return !empty($messageTemplate) ? $messageTemplate : false;
  }

}
