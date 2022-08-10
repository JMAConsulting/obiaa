<?php

class CRM_CiviMobileAPI_Utils_CreateCase {
  /**
   * Process the form submission.
   *
   * @param CRM_Case_Form_Case $form
   * @param array $params
   *
   * @throws \Exception
   */
  public static function runCreateCase(&$form, &$params) {
    if ($form->_context == 'caseActivity') {
      return;
    }

    $xmlProcessorProcess = new CRM_Case_XMLProcessor_Process();
    $isMultiClient = $xmlProcessorProcess->getAllowMultipleCaseClients();

    if (!$isMultiClient && !$form->_currentlyViewedContactId) {
      CRM_Core_Error::statusBounce('Required parameter missing for OpenCase - end post processing');
    }

    if (!$form->_currentUserId || !$params['case_id'] || !$params['case_type']) {
      CRM_Core_Error::statusBounce('Required parameter missing for OpenCase - end post processing');
    }

    // 1. create case-contact
    if ($isMultiClient && $form->_context == 'standalone') {
      foreach ($params['client_id'] as $cliId) {
        if (empty($cliId)) {
          CRM_Core_Error::statusBounce('client_id cannot be empty for OpenCase - end post processing');
        }
        $contactParams = [
          'case_id' => $params['case_id'],
          'contact_id' => $cliId,
        ];
        CRM_Case_BAO_CaseContact::create($contactParams);
      }
    }
    else {
      $contactParams = [
        'case_id' => $params['case_id'],
        'contact_id' => $form->_currentlyViewedContactId,
      ];
      CRM_Case_BAO_CaseContact::create($contactParams);
    }

    // 2. initiate xml processor
    $xmlProcessor = new CRM_Case_XMLProcessor_Process();

    $xmlProcessorParams = [
      'clientID' => $form->_currentlyViewedContactId,
      'creatorID' => $form->_currentUserId,
      'standardTimeline' => 1,
      'activityTypeName' => 'Open Case',
      'caseID' => $params['case_id'],
      'subject' => $params['activity_subject'],
      'location' => $params['location'],
      'activity_date_time' => $params['start_date'],
      'duration' => $params['duration'] ?? NULL,
      'medium_id' => $params['medium_id'],
      'details' => $params['activity_details'],
      'relationship_end_date' => $params['end_date'] ?? NULL,
    ];

    if (array_key_exists('custom', $params) && is_array($params['custom'])) {
      $xmlProcessorParams['custom'] = $params['custom'];
    }

    // Add parameters for attachments
    $numAttachments = Civi::settings()->get('max_attachments');
    for ($i = 1; $i <= $numAttachments; $i++) {
      $attachName = "attachFile_$i";
      if (isset($params[$attachName]) && !empty($params[$attachName])) {
        $xmlProcessorParams[$attachName] = $params[$attachName];
      }
    }

    $xmlProcessor->run($params['case_type'], $xmlProcessorParams);

    // status msg
    $params['statusMsg'] = ts("Case opened successfully. id: ". $params['case_id'] );

    return $params['statusMsg'];
  }
}
