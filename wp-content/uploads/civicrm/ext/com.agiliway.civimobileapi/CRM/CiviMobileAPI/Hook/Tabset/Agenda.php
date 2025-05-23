<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_Hook_Tabset_Agenda {

  public static function run($tabsetName, &$tabs, $context) {
    if ($tabsetName == 'civicrm/event/manage') {
      $isActiveAgenda = !empty($context['event_id']) ? CRM_CiviMobileAPI_Utils_Agenda_AgendaConfig::isAgendaActiveForEvent($context['event_id']) : false;
      $tabs['agenda'] = [
        'title' => E::ts('Agenda'),
        'url' => 'civicrm/civimobile/event/agenda',
        'link' => CRM_Utils_System::url('civicrm/civimobile/event/agenda', (isset($context['event_id']) ? 'id=' . $context['event_id'] : NULL)),
        'valid' => $isActiveAgenda,
        'active' => true,
        'current' => true,
        'class' => 'ajaxForm',
        'field' => 'is_agenda'
      ];
    }
  }
}
