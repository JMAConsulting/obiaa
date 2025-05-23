<?php

class CRM_CiviMobileAPI_Hook_PageRun_EventInfo {

  public static function run() {
    $session = CRM_Core_Session::singleton();
    if ($session->get('cmbHash')) {
      CRM_CiviMobileAPI_Hook_BuildForm_Register::customizeEventRegistration();
    }
    else {
      if (CRM_CiviMobileAPI_Utils_Agenda_AgendaConfig::isAgendaActiveForEvent(CRM_Utils_Request::retrieve('id', 'Positive'))) {
        $sessionsValues = CRM_CiviMobileAPI_Utils_Agenda_SessionSchedule::getEventSessionsValues(CRM_Utils_Request::retrieve('id', 'Positive'));
        if (!empty($sessionsValues)) {
          $smarty = CRM_Core_Smarty::singleton();
          $smarty->assign('session_schedule_data', json_encode(CRM_CiviMobileAPI_Utils_Agenda_SessionSchedule::getSessionScheduleData(CRM_Utils_Request::retrieve('id', 'Positive'))));
          CRM_Core_Region::instance('page-body')->add([
            'template' => 'CRM/CiviMobileAPI/Form/SessionSchedule.tpl',
          ]);
        }
      }
    }
  }
}
