<?php

class CRM_CiviMobileAPI_Page_AJAX_Agenda {

  /**
   * Returns EventSessions for crm-ajax-table
   * @return mixed
   */
  public static function getEventSessions() {

    $requiredParameters = [
      'event_id' => 'Integer',
    ];

    $optionalParameters = [
      'title' => 'String',
      'speaker' => 'Integer',
      'venue_id' => 'Integer'
    ];

    $params = CRM_Core_Page_AJAX::defaultSortAndPagerParams();
    $params += CRM_Core_Page_AJAX::validateParams($requiredParameters, $optionalParameters);
    $params['options']['offset'] = ($params['page'] - 1) * $params['rp'];
    $params['options']['limit'] = $params['rp'];
    $params['options']['sort'] = $params['sortBy'];

    $canEdit = CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionForCreateEventSession();
    $canDelete = CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionForDeleteEventSession();

    if (!empty($params['_raw_values']['sort']) && $params['_raw_values']['sort'][0] == 'date') {
      $params['options']['sort'] = 'start_time ' . $params['_raw_values']['order'][0];
    }

    $result = [];
    $result['data'] = [];

    $eventSessions = civicrm_api3('CiviMobileEventSession', 'get', $params);

    foreach ($eventSessions['values'] as $eventSession) {
      $speakersLinksWithNames = [];
      foreach ($eventSession['speakers_names'] as $speaker) {
        $speakersLinksWithNames[] = '<a href="' . CRM_Utils_System::url('civicrm/civimobile/event/speaker', 'reset=1&action=view&pid=' . $speaker['id'] . '&eid=' . $eventSession['event_id'])
          . '"   class="crm-popup medium-popup event-session-popup">' . $speaker['display_name'] . '</a>';
      }
      $sessionLinks = '<a href="' . CRM_Utils_System::url('civicrm/civimobile/event/session', 'reset=1&action=view&id=' . $eventSession['id']) . '" class="action-item crm-hover-button crm-popup medium-popup event-session-popup">View</a>' .
        ($canEdit ? '<a href="' . CRM_Utils_System::url('civicrm/civimobile/event/session', 'reset=1&action=update&id=' . $eventSession['id']) . '" class="action-item crm-hover-button crm-popup medium-popup event-session-popup">Edit</a>' : '') .
        ($canDelete ? '<a href="' . CRM_Utils_System::url('civicrm/civimobile/event/session', 'reset=1&action=delete&id=' . $eventSession['id']) . '" class="action-item crm-hover-button crm-popup small-popup event-session-popup">Delete</a>' : '');
      $result['data'][] = [
        'title' => $eventSession['title'],
        'date' => $eventSession['date_formatted'],
        'time' => $eventSession['start_time_formatted'] . ' - ' . $eventSession['end_time_formatted'],
        'venue_name' => $eventSession['venue_name'],
        'speakers' => implode(', ', $speakersLinksWithNames),
        'status' => $eventSession['display_status'],
        'links' => $sessionLinks,
        'DT_RowClass' => !$eventSession['is_display'] ? 'error-session' : '',
        'DT_RowId' => $eventSession['id'],
        'DT_RowAttr' => [
          'data-entity' => 'event_session',
          'data-id' => $eventSession['id']
        ]
      ];
    }

    $totalCount = CRM_CiviMobileAPI_BAO_EventSession::getCount($params);

    $result['recordsFiltered'] = $totalCount;
    $result['recordsTotal'] = $totalCount;

    return CRM_Utils_JSON::output($result);

  }

  /**
   * Returns Speakers for crm-ajax-table
   * @return mixed
   */
  public static function getEventSpeakers() {
    $requiredParameters = [
      'event_id' => 'Integer',
    ];

    $params = CRM_Core_Page_AJAX::defaultSortAndPagerParams();
    $params += CRM_Core_Page_AJAX::validateParams($requiredParameters, []);
    $params['options']['offset'] = ($params['page'] - 1) * $params['rp'];
    $params['options']['limit'] = $params['rp'];
    $params['options']['sort'] = $params['sortBy'];

    $result = [];
    $result['data'] = [];

    $speakers = civicrm_api3('CiviMobileSpeaker', 'get', $params);
    $canEdit = CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionToEditSpeaker();

    foreach ($speakers['values'] as $speaker) {
      $speakerLinks = '<a href="' . CRM_Utils_System::url('civicrm/civimobile/event/speaker', 'reset=1&action=view&pid=' . $speaker['participant_id'] . '&eid=' . $speaker['event_id']) .'"  class="action-item crm-hover-button crm-popup medium-popup">View</a>' .
        ($canEdit ? '<a href="' . CRM_Utils_System::url('civicrm/civimobile/event/speaker', 'reset=1&action=update&pid=' . $speaker['participant_id'] . '&eid=' . $speaker['event_id']) . '" class="action-item crm-hover-button crm-popup medium-popup">Edit</a>' : '');

      $result['data'][] = [
        'display_name' => $speaker['display_name'],
        'job_title' => $speaker['job_title'],
        'organization_name' => $speaker['current_employer'],
        'links' => $speakerLinks,
        'DT_RowClass' => '',
        'DT_RowId' => $speaker['participant_id'],
        'DT_RowAttr' => [
          'data-entity' => 'event_session_speaker',
          'data-id' => $speaker['participant_id']
        ]
      ];
    }

    $totalCount = CRM_CiviMobileAPI_BAO_EventSessionSpeaker::getCountSpeakersBelongedToEvent($params);

    $result['recordsFiltered'] = $totalCount;
    $result['recordsTotal'] = $totalCount;

    return CRM_Utils_JSON::output($result);
  }
}
