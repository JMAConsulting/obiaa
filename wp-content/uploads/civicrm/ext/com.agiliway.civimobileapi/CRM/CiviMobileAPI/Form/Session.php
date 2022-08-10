<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_Form_Session extends CRM_Core_Form {

  /**
   * EventSession ID
   *
   * @var int
   */
  public $sessionId;

  /**
   * Event ID
   *
   * @var int
   */
  public $eventId;

  /**
   * Location id
   *
   * @var int
   */
  public $locationId;

  public function preProcess() {
    parent::preProcess();

    unset($_COOKIE['civimobile_speaker_id']);
    setcookie('civimobile_speaker_id', null, -1, '/');

    if (!in_array($this->getAction(), [CRM_Core_Action::ADD, CRM_Core_Action::UPDATE, CRM_Core_Action::DELETE, CRM_Core_Action::VIEW])) {
      CRM_Utils_System::redirect(CRM_Utils_System::url("civicrm/event/manage"));
    }

    if (in_array($this->getAction(), [CRM_Core_Action::ADD, CRM_Core_Action::UPDATE, CRM_Core_Action::DELETE])
      && !CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionForCreateEventSession()) {
      CRM_Core_Error::statusBounce('You do not have all the permissions needed for this page.', '', E::ts('Permission Denied'));
    }

    $url = CRM_Utils_System::url('civicrm/event/manage');

    if ($this->getAction() == CRM_Core_Action::ADD) {
      $eventId = CRM_Utils_Request::retrieve('event_id', 'Positive');
      if (!$eventId) {
        $eventId = $this->_submitValues["event_id"];
      }
      try {
        $event = CRM_Event_BAO_Event::findById($eventId);
      } catch (Exception $e) {
        CRM_Core_Error::statusBounce('Invalid eventId parameter.', $url, E::ts('Not Found'));
      }
      $this->eventId = $event->id;
      $this->setTitle($event->title . ' - Add Session');
    }

    if ($this->getAction() == CRM_Core_Action::UPDATE || $this->getAction() == CRM_Core_Action::DELETE || $this->getAction() == CRM_Core_Action::VIEW) {
      $sessionId = CRM_Utils_Request::retrieve('id', 'Positive');
      if (!$sessionId) {
        $sessionId = $this->_submitValues["session_id"];
      }
      try {
        $session = civicrm_api3('CiviMobileEventSession', 'getsingle', [
          'id' => $sessionId
        ]);
      } catch (Exception $e) {
        CRM_Core_Error::statusBounce('Invalid sessionId parameter.', $url, E::ts('Not Found'));
      }
      try {
        $event = CRM_Event_BAO_Event::findById($session["event_id"]);
      } catch (Exception $e) {
        CRM_Core_Error::statusBounce('Event does not exists.', $url, E::ts('Not Found'));
      }
      $this->sessionId = $session["id"];
      $this->eventId = $event->id;
      $this->setTitle($event->title . ' - ' . $session["title"]);

      if ($this->getAction() == CRM_Core_Action::VIEW) {
        $session["speakers_with_links"] = [];
        foreach ($session["speakers_names"] as $speaker) {
          $url = CRM_Utils_System::url('civicrm/civimobile/event/speaker', 'reset=1&action=view&pid=' . $speaker['id'] . '&eid=' . $this->eventId);
          $session["speakers_with_links"][] = '<a href="' . $url . '" class="crm-popup medium-popup">' . $speaker["display_name"] . '</a>';
        }
        $session["participant_with_links"] = [];
        foreach ($session["participants"] as $participant) {
          $url = CRM_Utils_System::url('civicrm/contact/view/', 'reset=1&cid=' . $participant['contact_id']);
          $session["participant_with_links"][] = '<a href="' . $url . '" class="crm-popup">' . $participant["display_name"] . '</a>';
        }
        $session['participant_with_links'] = implode(', ', $session['participant_with_links']);
        $session["speakers_with_links"] = implode(', ', $session["speakers_with_links"]);
        $session["venue_link"] = '<a href="' . CRM_Utils_System::url('civicrm/civimobile/venue', 'reset=1&use_delete_button=0&action=view&id=' . $session['venue_id'] . '&location_id=' . $event->loc_block_id) . '" class="crm-popup medium-popup">' . $session['venue_name'] . '</a>';
        $this->assign('can_edit_session', CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionForCreateEventSession());
        $this->assign('can_delete_session', CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionForDeleteEventSession());
      }
      $this->assign('eventSession', $session);

    }

    $this->locationId = $event->loc_block_id;
    $this->assign('event_id', $this->eventId);
  }

  /**
   * Build the form object.
   *
   * @return void
   */
  public function buildQuickForm() {
    parent::buildQuickForm();

    $buttons = [];
    $cancelURL = CRM_Utils_System::url('civicrm/civimobile/event/agenda', http_build_query([
      'reset' => '1',
      'id' => $this->eventId
    ]));
    $cancelButtonName = 'Cancel';

    if ($this->getAction() == CRM_Core_Action::ADD) {
      $this->add('hidden', 'event_id', $this->eventId);
    }

    if ($this->getAction() == CRM_Core_Action::UPDATE) {
      $this->add('hidden', 'session_id', $this->sessionId);
      $this->add('hidden', 'event_id', $this->eventId);
    }

    if ($this->getAction() == CRM_Core_Action::DELETE) {
      $this->add('hidden', 'session_id', $this->sessionId);
      $this->add('hidden', 'event_id', $this->eventId);
    }

    if (($this->getAction() == CRM_Core_Action::UPDATE || $this->getAction() == CRM_Core_Action::ADD)) {
      $this->add('text', 'title', E::ts('Title'), ['class' => 'huge'], TRUE);
      $this->add('datepicker', 'date', E::ts('Date'), [], TRUE, ['time' => FALSE]);
      $this->add('datepicker', 'start_time', E::ts('Start time'), [], TRUE, ['time' => TRUE, 'date' => FALSE]);
      $this->add('datepicker', 'end_time', E::ts('End time'), [], TRUE, ['time' => TRUE, 'date' => FALSE]);

      $this->addEntityRef('speakers', E::ts('Speakers'), [
        'placeholder' => '- select speakers -',
        'multiple' => TRUE,
        'entity' => 'CiviMobileParticipant',
        'api' => [
          'params' => ['event_id' => $this->eventId],
        ],
        'id' => 'speakers',
        'select' => ['minimumInputLength' => 0]
      ]);

      $this->addEntityRef('venue_id', E::ts('Venue'), [
        'placeholder' => '- select venue -',
        'entity' => 'CiviMobileVenue',
        'api' => [
          'params' => ['location_id' => $this->locationId, 'is_active' => 1, 'options' => ['sort' => "weight asc"]]
        ],
        'select' => ['minimumInputLength' => 0]
      ], TRUE);

      $this->add('textarea', 'description', E::ts('Description'), ['class' => 'big']);
      if (CRM_CiviMobileAPI_Utils_Event::isEventHaveLocation($this->eventId)) {
        $this->assign('location', CRM_CiviMobileAPI_Utils_Agenda_Venue::getLocaleId($this->eventId));
      }
      if (!CRM_CiviMobileAPI_Utils_Event::isEventHaveLocation($this->eventId)) {
        $this->getElement('venue_id')->freeze();
        $this->assign('venueNotice', E::ts('This event does not have location'));
      }

      $buttons[] = [
        'type' => 'submit',
        'name' => E::ts('Save'),
        'isDefault' => TRUE,
      ];
    }

    if ($this->getAction() == CRM_Core_Action::DELETE) {
      $buttons[] = [
        'type' => 'submit',
        'name' => E::ts('Delete'),
        'isDefault' => TRUE
      ];
    }

    if ($this->getAction() == CRM_Core_Action::ADD) {
      $buttons[] = [
        'type' => 'next',
        'name' => E::ts('Save and continue'),
        'isDefault' => TRUE,
      ];
    }

    if ($this->getAction() == CRM_Core_Action::VIEW) {
      $cancelButtonName = 'Done';
    }

    $buttons[] = [
      'type' => 'cancel',
      'name' => E::ts($cancelButtonName),
      'js' => ['onclick' => "
         if( CRM.$('.ui-dialog').length ) {
           var active = 'a.crm-popup';
           CRM.$('#crm-main-content-wrapper').on('crmPopupFormSuccess.crmLivePage', active, CRM.refreshParent, CRM.$('.ui-dialog-titlebar-close').trigger('click'));
         } else {
           window.location.href='{$cancelURL}'; return false;
         }"
      ],
    ];

    $this->addButtons($buttons);
  }

  public function postProcess() {
    $params = $this->exportValues();

    if ($this->getAction() == CRM_Core_Action::ADD || $this->getAction() == CRM_Core_Action::UPDATE) {
      $apiParams = [
        'title' => $params['title'],
        'description' => $params['description'],
        'start_time' => $params['date'] . ' ' . $params['start_time'],
        'end_time' => $params['date'] . ' ' . $params['end_time'],
        'speakers' => $params['speakers'],
        'venue_id' => $params['venue_id'],
        'event_id' => $params['event_id']
      ];

      if ($this->getAction() == CRM_Core_Action::UPDATE) {
        $apiParams["id"] = $params["session_id"];
      }

      civicrm_api3('CiviMobileEventSession', 'create', $apiParams);

      if ($this->getAction() == CRM_Core_Action::ADD) {
        CRM_Core_Session::setStatus(E::ts('The Session was successfully created!'), E::ts('Success'), 'success');
      }

      if ($this->getAction() == CRM_Core_Action::UPDATE) {
        CRM_Core_Session::setStatus(E::ts('The Session was successfully updated!'), E::ts('Success'), 'success');
      }

      if (!empty($params['_qf_Session_next'])) {
        CRM_Utils_System::redirect(CRM_Utils_System::url("civicrm/civimobile/event/session", 'reset=1&action=add&event_id=' . $params["event_id"]));
      }
    }

    if ($this->getAction() == CRM_Core_Action::DELETE) {
      try {
        civicrm_api3('CiviMobileEventSession', 'delete', [
          'id' => $params["session_id"]
        ]);
      } catch(Exception $e) {
        CRM_Core_Session::setStatus(E::ts('The Session was not deleted!'), E::ts('Error'), 'error');
      }
      CRM_Core_Session::setStatus(E::ts('The Session was successfully deleted!'), E::ts('Success'), 'success');
    }
    CRM_Utils_System::redirect(CRM_Utils_System::url("civicrm/civimobile/event/agenda", 'reset=1&id=' . $params["event_id"]));
  }

  /**
   * Set defaults for form.
   */
  public function setDefaultValues() {
    $defaults = [];

    if ($this->getAction() == CRM_Core_Action::UPDATE) {
      $url = CRM_Utils_System::url('civicrm/event/manage');
      try {
        $session = civicrm_api3('CiviMobileEventSession', 'getsingle', [
          'id' => $this->sessionId
        ]);
      } catch (Exception $e) {
        CRM_Core_Error::statusBounce('Invalid sessionId parameter.', $url, E::ts('Not Found'));
      }

      $defaults["title"] = $session["title"];
      $defaults["description"] = $session["description"];
      $defaults["venue_id"] = $session['venue_id'];
      $defaults["speakers"] = $session["speakers_id"];
      $defaults["date"] = substr($session["start_time"], 0, 10);
      $defaults["start_time"] = $session["start_time"];
      $defaults["end_time"] = $session["end_time"];
      $defaults["participant"] = $session["participant"];
    }

    return $defaults;
  }

  /**
   * AddRules hook
   */
  public function addRules() {
    if ($this->getAction() == CRM_Core_Action::ADD || $this->getAction() == CRM_Core_Action::UPDATE) {
      $this->addFormRule([self::class, 'validateForm']);
    }
  }

  /**
   * Validates form
   *
   * @param $values
   *
   * @return array|bool
   */
  public static function validateForm($values) {
    $errors = [];
    if (!empty($values['speakers'])) {
      $values['speakers'] = explode(',', $values['speakers']);
    }
    $sessionId = isset($values['session_id']) ? $values['session_id'] : NULL;
    $startTime = $values["date"] . ' ' . $values["start_time"];
    $endTime = $values["date"] . ' ' . $values["end_time"];
    try {
      $event = CRM_Event_BAO_Event::findById($values["event_id"]);
    } catch (Exception $e) {
      $errors["event_id"] = E::ts('Invalid eventId parameter.');
      return $errors;
    }
    if (empty($values["title"])) {
      $errors["title"] = E::ts('Title can`t be empty!');
    }
    if (strlen($values["title"]) > 255) {
      $errors["title"] = E::ts('Title length must be less than 255 characters.');
    }
    if (strtotime($startTime) >= strtotime($endTime)) {
      $errors["start_time"] = E::ts('Start time can`t be later than End time.');
    } elseif (strtotime($startTime) + 15*60 > strtotime($endTime)) {
      $errors["start_time"] = E::ts('Event Session duration can`t be less than 15 min.');
    }
    $startEventDate = strtotime(date('Y-m-d',strtotime($event->start_date)));
    if (strtotime($values["date"]) < $startEventDate) {
      $errors["date"] = E::ts('Session start date can`t be early than start time on the Event. (Start time on Event: ' . $event->start_date . ')');
    } elseif (strtotime($startTime) < strtotime($event->start_date)) {
      $errors["start_time"] = E::ts('Start date can`t be early than start time on Event. (Start time on Event: ' . $event->start_date . ')');
    }
    if (!empty($event->end_date) && (strtotime($values["date"]) > strtotime($event->end_date))) {
      $errors["date"] = E::ts('End date can`t be later than end date on Event. (End time on Event: ' . $event->end_date . ')');
    } elseif (!empty($event->end_date) && (strtotime($endTime) > strtotime($event->end_date))) {
      $errors["end_time"] = E::ts('End time can`t be later than end time on Event. (End time on Event: ' . $event->end_date . ')');
    }
    if (!empty($values['speakers']) && !CRM_CiviMobileAPI_Utils_Agenda_Speakers::issetSpeakers($values['speakers'], $values["event_id"])) {
      $errors["speakers"] = E::ts('Some speakers does not exists.');
    }
    if (isset($values["speakers"]) && CRM_CiviMobileAPI_BAO_EventSession::isSpeakersBusy($values["speakers"], $sessionId, $startTime, $endTime)) {
      $errors["speakers"] = E::ts('Some speakers are busy on other Event Session at this time.');
    }
    if (!empty($values["venue_id"])) {
      if (!CRM_CiviMobileAPI_Utils_Agenda_Venue::issetVenue($values['venue_id'], $values["event_id"])) {
        $errors["venue_id"] = E::ts('Venue does not exists.');
      }
      if (CRM_CiviMobileAPI_BAO_EventSession::isVenueBusy($values["venue_id"], $sessionId, $startTime, $endTime)) {
        $errors["venue_id"] = E::ts('Venue is booked on other Event Session at this time.');
      }
    } else {
      $errors["venue_id"] = E::ts('Venue is required.');
    }

    return empty($errors) ? TRUE : $errors;
  }

}
