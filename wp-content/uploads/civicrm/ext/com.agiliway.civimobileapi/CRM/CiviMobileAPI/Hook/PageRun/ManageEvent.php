<?php

class CRM_CiviMobileAPI_Hook_PageRun_ManageEvent {

  public static function run() {
    $smarty = CRM_Core_Smarty::singleton();
    foreach ($smarty->getTemplateVars()["rows"] as $key => &$row) {
      if ($key == 'tab') {
        continue;
      }
      $row['is_agenda'] = CRM_CiviMobileAPI_Utils_Agenda_AgendaConfig::isAgendaActiveForEvent($row['id']);
    }
  }
}
