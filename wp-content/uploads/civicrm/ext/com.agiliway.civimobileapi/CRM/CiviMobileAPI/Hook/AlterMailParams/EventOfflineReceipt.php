<?php

class CRM_CiviMobileAPI_Hook_AlterMailParams_EventOfflineReceipt {

  /**
   * Attach variable to message template
   *
   * @param $params
   * @param $context
   * @throws CRM_Core_Exception
   */
  public static function run($params, $context) {
    if ($context == 'messageTemplate' && $params['workflow'] == 'event_offline_receipt') {
      $template = CRM_Core_Smarty::singleton();
      $eventId = (int)$template->getTemplateVars('eventID');
      $contactId = (int)$template->getTemplateVars('contactID');

      try {
        $participantId = civicrm_api3('Participant', 'getvalue', [
          'sequential' => 1,
          'contact_id' => $contactId,
          'event_id' => $eventId,
          "return" => "id",
        ]);
      } catch (CiviCRM_API3_Exception $e) {
        $participantId = false;
      }

      if (!empty($participantId)) {
        $qrCodeInfo = CRM_CiviMobileAPI_Utils_ParticipantQrCode::getQrCodeInfo($participantId);
        $template->assign('file_name', $qrCodeInfo['qr_code_image']);
      }
    }
  }

}
