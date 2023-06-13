<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_Api_CiviMobileEventSession_Get extends CRM_CiviMobileAPI_Api_CiviMobileBase {

  /**
   * Cached events
   */
  private $cacheEvents = [];

  /**
   * Returns results to api
   *
   * @return array
   * @throws api_Exception
   */
  public function getResult() {
    $config = CRM_Core_Config::singleton();
    $sessions = CRM_CiviMobileAPI_BAO_EventSession::getAll($this->validParams);
    $result = [];

    if (!empty($sessions)) {
      foreach ($sessions as $session) {
        $displayInfo = $this->getDisplayInfo($session);

        if (isset($this->validParams['is_display']) && $displayInfo['is_display'] != $this->validParams['is_display']) {
          continue;
        }

        $preparedEventSession = [
          'id' => $session["id"],
          'title' => $session["title"],
          'start_time' => $session["start_time"],
          'end_time' => $session["end_time"],
          'event_id' => $session["event_id"],
          'description' => $session["description"],
          'speakers_names' => CRM_CiviMobileAPI_Utils_Agenda_Speakers::getSpeakersNames($session["speakers"], $session["event_id"]),
          'venue_name' => $session['venue_name'],
          'venue_id' => $session["venue_id"],
          'speakers_id' => $session["speakers"],
          'date_formatted' => CRM_Utils_Date::customFormat($session["start_time"], $config->dateformatshortdate),
          'start_time_formatted' => CRM_Utils_Date::customFormat($session["start_time"], $config->dateformatTime),
          'end_time_formatted' => CRM_Utils_Date::customFormat($session["end_time"], $config->dateformatTime),
          'is_display' => $displayInfo['is_display'],
          'display_status' =>  $displayInfo['display_status'],
        ];

        if (CRM_Core_Session::getLoggedInContactID()) {
          $preparedEventSession['is_favourite'] = $session["is_favourite"];
        }
        $favoriteParticipants = CRM_CiviMobileAPI_BAO_FavouriteEventSession::getAll(['event_session_id' => $session["id"]]);
        $favoriteParticipantIds = [];
        foreach ($favoriteParticipants as $participant) {
          $favoriteParticipantIds[] = (int)$participant['contact_id'];
        }

        $preparedEventSession['participants'] = CRM_CiviMobileAPI_Utils_Participant::getParticipantsNames($favoriteParticipantIds, $session["event_id"]);

        $result[] = $preparedEventSession;
      }
    }

    return $result;
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
    if (!CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionForGetEventSession()) {
      throw new api_Exception('You don`t have enough permissions.', 'do_not_have_enough_permissions');
    }

    foreach ($params as $key => $value) {
      if (!isset($params[$key])) {
        unset($params[$key]);
      }
    }
    return $params;
  }

  /**
   * Returns Event by Id
   *
   * @param $eventId
   * @return CRM_Event_BAO_Event
   */
  private function getCachedEventById($eventId) {
    if (isset($this->cacheEvents[$eventId])) {
      return $this->cacheEvents[$eventId];
    }

    $this->cacheEvents[$eventId] = CRM_Event_BAO_Event::findById($eventId);

    return $this->cacheEvents[$eventId];
  }

  /**
   * Returns display info
   *
   * @param $session
   * @return array
   */
  private function getDisplayInfo($session) {
    $isDisplay = 1;
    $displayStatus = '';

    if (empty($session["venue_id"])) {
      $isDisplay = 0;
      $displayStatus = E::ts('Venue does not exists in session');
    }

    $event = $this->getCachedEventById($session["event_id"]);

    if (((strtotime($session["end_time"]) > strtotime($event->end_date) && $event->end_date != NULL)
        || strtotime($session["start_time"]) < strtotime($event->start_date))
      && empty($session["venue"])
    ) {
      $isDisplay = 0;
      $displayStatus = E::ts('Venue does not exists in session and session is outside of timeframe event');
    } elseif ((strtotime($session["end_time"]) > strtotime($event->end_date) && $event->end_date != NULL)
      || strtotime($session["start_time"]) < strtotime($event->start_date)
    ) {
      $isDisplay = 0;
      $displayStatus = E::ts('Session is outside of timeframe event');
    }
    if ($isDisplay == 1) {
      $displayStatus = E::ts('Session is ready to use');
    }

    return [
      'is_display' => $isDisplay,
      'display_status' => $displayStatus,
    ];
  }

}
