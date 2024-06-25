<?php

class CRM_CiviMobileAPI_Hook_AlterMailParams_EventOnlineReceipt {

  /**
   * Attach variable to message template
   *
   * @param $params
   * @param $context
   * @throws CRM_Core_Exception
   */
  public static function run($params, $context) {
    if ($context == 'messageTemplate' && $params['workflow'] == 'event_online_receipt') {
      $template = CRM_Core_Smarty::singleton();
      $eventId = (int)$template->getTemplateVars('event')['id'];
      $contactId = (int)$template->getTemplateVars('contactID');

      if (!empty($params['tplParams']['contactIdApi'])) {
        $contactId = (int)$params['tplParams']['contactIdApi'];
      }
      elseif (empty($contactId) && isset($params['contactId'])) {
        $contactId = $params['contactId'];
      }

      if (!empty($params['tplParams']['eventIdApi'])) {
        $eventId = (int)$params['tplParams']['eventIdApi'];
      }

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
