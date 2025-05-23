<?php

class CRM_CiviMobileAPI_Hook_AlterAPIPermissions_CustomPermissions {

  public static function run(&$permissions) {
    $permissions['civi_mobile_favourite_event_session']['create'] = ['view Agenda'];
    $permissions['civi_mobile_agenda_config']['create'] = [
      'access CiviCRM',
      'view my contact',
      'access CiviEvent',
      'view Agenda'
    ];
    $permissions['civi_mobile_agenda_config']['get'] = ['view Agenda'];
    $permissions['civi_mobile_speaker']['get'] = ['view Agenda'];
    $permissions['civi_mobile_participant']['get'] = [
      'access CiviEvent',
      'view event info',
      'view event participants'
    ];
    $permissions['civi_mobile_venue']['get'] = ['view Agenda'];
    $permissions['civi_mobile_event_session']['get'] = ['view Agenda'];
    $permissions['civi_mobile_venue_attach_file']['delete'] = [
      'access CiviCRM',
      'view my contact',
      'access CiviEvent',
      'view Agenda'
    ];
    $permissions['civi_mobile_participant_payment_link']['get'] = [
      'view event info',
      'register for events'
    ];
    $permissions['civi_mobile_participant_link']['get'] = [
      'view event info',
      'register for events'
    ];
  }
}
