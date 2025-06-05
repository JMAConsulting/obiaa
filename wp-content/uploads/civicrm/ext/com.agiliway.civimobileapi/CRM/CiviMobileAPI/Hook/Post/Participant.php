<?php

class CRM_CiviMobileAPI_Hook_Post_Participant {

  /**
   * Rebuild venue after changing event location data.
   */
  public static function run($op, $objectName, $objectId) {
    if ($objectName == 'Participant') {
      if ($op == 'create') {
        CRM_CiviMobileAPI_Utils_QRcode::generateQRcode($objectId);
      }

      if ($op == 'delete') {
        CRM_CiviMobileAPI_BAO_EventSessionSpeaker::deleteAllSpeakersByParticipantId($objectId);
      }
    }
  }
}
