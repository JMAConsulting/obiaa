<?php

class CRM_CiviMobileAPI_Utils_Agenda_AgendaConfig {

  public static function isAgendaActiveForEvent($eventId) {
    try {
      $isUseAgenda = civicrm_api3('CiviMobileAgendaConfig', 'getsingle', [
        'event_id' => $eventId,
      ])['is_active'];
    } catch (Exception $e) {
      $isUseAgenda = 0;
    }
    return $isUseAgenda;
  }
}
