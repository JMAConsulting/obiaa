<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_Form_Agenda extends CRM_Event_Form_ManageEvent {

  /**
   * Id of current event
   *
   * @var int
   */
  public $eventId;

  /**
   * @throws \CRM_Core_Exception
   * @throws \Exception
   */
  public function preProcess() {
    parent::preProcess();
    if (method_exists($this, 'setSelectedChild')) {
      $this->setSelectedChild('agenda');
    }
    $url = CRM_Utils_System::url('civicrm/event/manage');
    $this->eventId = CRM_Utils_Request::retrieve('id', 'Positive');
    if (!$this->eventId) {
      $this->eventId = $this->_submitValues["event_id"];
    }
    try {
      $event = CRM_Event_BAO_Event::findById($this->eventId);
    } catch (Exception $e) {
      CRM_Core_Error::statusBounce('Invalid eventId parameter.', $url, E::ts('Not Found'));
    }

    $isActive = [
      1 => 'Yes',
      0 => 'No',
    ];

    $this->assign('venues', CRM_CiviMobileAPI_BAO_LocationVenue::getAll(["location_id" => $event->loc_block_id]));
    $this->assign('location_id', $event->loc_block_id);
    $this->assign('is_active', $isActive);

    $this->assign('is_use_agenda', CRM_CiviMobileAPI_Utils_Agenda_AgendaConfig::isAgendaActiveForEvent($this->eventId));
    $this->assign('event_id', $this->eventId);
    $this->assign('can_change_agenda_config', CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionForCreateAgendaConfig());
    $this->assign('can_create_event_session', CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionForCreateEventSession());

  }

  /**
   * Build the form object.
   */
  public function buildQuickForm() {
    parent::buildQuickForm();
    $speakers = CRM_CiviMobileAPI_BAO_EventSessionSpeaker::getSpeakersBelongedToSessionsByEvent(['event_id' => $this->eventId]);
    $preparedSpeakers = [];
    foreach ($speakers as $speaker) {
      $preparedSpeakers[$speaker['speaker_id']] = $speaker['display_name'];
    }

    $venues = CRM_CiviMobileAPI_Utils_Agenda_Venue::getVenuesNamesByEventId($this->eventId);
    $localeId = CRM_CiviMobileAPI_Utils_Agenda_Venue::getLocaleId($this->eventId);
    if (empty($localeId)) {
      $this->assign('notice', E::ts('If you want to fill Agenda, you need to add the location for the event.'));
    }

    $this->add('hidden', 'event_id', $this->eventId);
    $this->add('select', 'speaker', E::ts('Speaker'), $preparedSpeakers, FALSE,
      ['id' => 'speaker', 'class' => 'crm-select2', 'placeholder' => E::ts('- any -')]
    );
    $this->add('select', 'venue', E::ts('Venue'), $venues, FALSE,
      ['id' => 'venue', 'class' => 'crm-select2', 'placeholder' => E::ts('- any -')]
    );
    $this->add('text', 'name_include', E::ts('Name include'));
  }

  /**
   * Set defaults for form.
   */
  public function setDefaultValues() {
    $defaults = [];
    return $defaults;
  }

}
