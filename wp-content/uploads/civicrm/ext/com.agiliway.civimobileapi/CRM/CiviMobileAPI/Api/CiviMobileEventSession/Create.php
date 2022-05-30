<?php

class CRM_CiviMobileAPI_Api_CiviMobileEventSession_Create extends CRM_CiviMobileAPI_Api_CiviMobileBase {

  /**
   * Returns results to api
   *
   * @return array
   * @throws api_Exception
   */
  public function getResult() {
    $session = [];
    if (!isset($this->validParams['id'])) {
      $session['title'] = $this->validParams["title"];
      $session['start_time'] = $this->validParams["start_time"];
      $session['end_time'] = $this->validParams["end_time"];
      $session['event_id'] = $this->validParams["event_id"];
      if (isset($this->validParams["speakers"])) {
        $session['speakers'] = $this->validParams["speakers"];
      }
      if (isset($this->validParams["venue_id"])) {
        $session['venue_id'] = $this->validParams["venue_id"];
      }
      if (isset($this->validParams["description"])) {
        $session['description'] = $this->validParams["description"];
      }
    } else {
      $session['id'] = $this->validParams['id'];
      if (isset($this->validParams["title"])) {
        $session['title'] = $this->validParams["title"];
      }
      if (isset($this->validParams["start_time"])) {
        $session['start_time'] = $this->validParams["start_time"];
      }
      if (isset($this->validParams["end_time"])) {
        $session['end_time'] = $this->validParams["end_time"];
      }
      if (isset($this->validParams["event_id"])) {
        $session['event_id'] = $this->validParams["event_id"];
      }
      if (isset($this->validParams["speakers"])) {
        $session['speakers'] = $this->validParams["speakers"];
      }
      if (isset($this->validParams["venue_id"])) {
        $session['venue_id'] = $this->validParams["venue_id"];
      }
      if (isset($this->validParams["description"])) {
        $session['description'] = $this->validParams["description"];
      }
    }
    $sessionBAO = CRM_CiviMobileAPI_BAO_EventSession::create($session);

    return [
      [
        'id' => $sessionBAO->id,
        'title' => $sessionBAO->title,
        'start_time' =>  $sessionBAO->start_time,
        'end_time' =>  $sessionBAO->end_time,
        'description' => $sessionBAO->description,
        'venue_id' => $sessionBAO->venue_id
      ]
    ];
  }

  /**
   * Returns validated params
   *
   * @param $params
   *
   * @return array
   * @throws api_Exception
   */
  protected function getValidParams($params) {
    if (!CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionForCreateEventSession()) {
      throw new api_Exception('You don`t have enough permissions.', 'do_not_have_enough_permissions');
    }

    if (!empty($params['event_id'])) {
      try {
        $event = CRM_Event_BAO_Event::findById($params["event_id"]);
      } catch (Exception $e) {
        throw new api_Exception('This event does not exists.', 'event_does_not_exists');
      }
    }
    if (!empty($params['speakers'])) {
      $params['speakers'] = array_unique(explode(',', $params['speakers']));
    }

    $sessionId = NULL;
    if (empty($params['id'])) {
      if (empty($params['event_id'])) {
        throw new api_Exception('Missed "event_id" parameter.', 'event_id_is_required');
      }
      if (empty($params['title'])) {
        throw new api_Exception('Missed "title" parameter.', 'title_is_required');
      }
      if (empty($params['start_time'])) {
        throw new api_Exception('Missed "start_time" parameter.', 'start_time_is_required');
      }
      if (empty($params['end_time'])) {
        throw new api_Exception('Missed "end_time" parameter.', 'end_time_is_required');
      }
      if (empty($params['venue_id'])) {
        throw new api_Exception('Missed "venue_id" parameter.', 'venue_id_is_required');
      } elseif (!CRM_CiviMobileAPI_Utils_Agenda_Venue::issetVenue($params['venue_id'], $params["event_id"])) {
        throw new api_Exception('Venue doesn`t exists.', 'venue_does_not_exists');
      }
      if (!empty($params['speakers']) && !CRM_CiviMobileAPI_Utils_Agenda_Speakers::issetSpeakers($params['speakers'], $params["event_id"])) {
        throw new api_Exception('Some speakers does not exists.', 'speakers_does_not_exists');
      }
      $startTime = $params['start_time'];
      $endTime = $params['end_time'];

    } else {
      try {
        $session = CRM_CiviMobileAPI_BAO_EventSession::findById($params['id']);
      } catch (Exception $e) {
        throw new api_Exception('This session does not exists.', 'session_does_not_exists');
      }
      $sessionId = $session->id;
      if (strlen($params['title']) > 255) {
        throw new api_Exception('Title length must be less than 256.', 'title_length_is_too_long');
      }
      if (!empty($params['venue_id']) && !CRM_CiviMobileAPI_Utils_Agenda_Venue::issetVenue($params['venue_id'], $params["event_id"])) {
        throw new api_Exception('Venue doesn`t exists.', 'venue_does_not_exists');
      }
      if (!empty($params['speakers']) && !CRM_CiviMobileAPI_Utils_Agenda_Speakers::issetSpeakers($params['speakers'], $session->event_id)) {
        throw new api_Exception('Some speakers does not exists.', 'speakers_does_not_exists');
      }
      if (empty($params['start_time']) && empty($params['end_time'])) {
        $startTime = $session->start_time;
        $endTime = $session->end_time;
      } elseif (empty($params['start_time'])) {
        $startTime = $session->start_time;
        $endTime = $params['end_time'];
      } elseif (empty($params['end_time'])) {
        $startTime = $params['start_time'];
        $endTime = $session->end_time;
      } else {
        $startTime = $params['start_time'];
        $endTime = $params['end_time'];
      }
    }
    if (!empty($params['speakers']) && CRM_CiviMobileAPI_BAO_EventSession::isSpeakersBusy($params['speakers'], $sessionId, $startTime, $endTime)) {
      throw new api_Exception('Some speakers are busy on other Event Session at this time.', 'some_speakers_are_busy');
    }
    if (!empty($params['venue_id']) && CRM_CiviMobileAPI_BAO_EventSession::isVenueBusy($params["venue_id"], $sessionId, $startTime, $endTime)) {
      throw new api_Exception('Venue is booked.','venues_is_booked');
    }
    $startTime = strtotime($startTime);
    $endTime = strtotime($endTime);
    if ($startTime >= $endTime) {
      throw new api_Exception('Start time can`t be later than end time.', 'start_time_cannot_be_later_than_end_time');
    } elseif ($startTime + 15*60 > $endTime) {
      throw new api_Exception('Event Session duration can not be less than 15 min.', 'event_session_duration_must_be_longer');
    }
    if ($startTime < strtotime($event->start_date)) {
      throw new api_Exception('Start time can`t be early than start time on Event.', 'start_time_cannot_be_early_than_start_time_on_event');
    }
    if ($endTime > strtotime($event->end_date) && $event->end_date != NULL) {
      throw new api_Exception('End time can`t be later than end time on Event.', 'end_time_cannot_be_later_than_end_time_on_event');
    }
    foreach ($params as $key => $value) {
      if (empty($params[$key])) {
        unset($params[$key]);
      } elseif ($key == 'speakers' || $key == 'venues') {
        $params[$key] = implode(',', $params[$key]);
      }
    }

    return $params;
  }

}
