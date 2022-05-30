<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_Utils_Agenda_SessionSchedule {

  /**
   * Return session values for event.
   *
   * @param $eventId
   *
   * @return array
   */
  public static function getEventSessionsValues($eventId) {
    try {
      $sessionsValues = civicrm_api3('CiviMobileEventSession', 'get', [
        'event_id' => $eventId,
        'sequential' => 1,
        'options' => ['limit' => 0],
      ])['values'];
    } catch (Exception $e) {
      return [];
    }

    return $sessionsValues;
  }

  /**
   * Return speakers for event.
   *
   * @param $eventId
   *
   * @return array
   */
  public static function getEventSpeakers($eventId) {
    try {
      $speakers = civicrm_api3('CiviMobileSpeaker', 'get', [
        'sequential' => 1,
        'event_id' => $eventId,
        'options' => ['limit' => 0],
      ])['values'];
    } catch (Exception $e) {
      return [];
    }

    return $speakers;
  }

  /**
   * Return start and end date for schedule.
   *
   * @param $eventId
   *
   * @return array
   */
  private static function getStartAndEndTime($eventId) {
    $eventData = civicrm_api3('Event', 'getsingle', ['id' => $eventId]);
    $startTime = $eventData['event_start_date'];
    if (empty($eventData['event_end_date'])) {
      $sessionsData = self::getEventSessionsValues($eventId);
      foreach ($sessionsData as $sessionData) {
        $endTimes[] = $sessionData['end_time'];
      }
      $endTime = date('Y-m-d H:i:s', max(array_map('strtotime', $endTimes)));
    } else {
      $endTime = $eventData['event_end_date'];
    }

    return [
      'start' => $startTime,
      'end' => $endTime
    ];
  }

  /**
   * Get time format array
   *
   * @return array
   */
  private static function getTimeTypeArray() {
    $timeSetting = civicrm_api3('Setting', 'getsingle', [
      'return' => ["dateformatTime"],
    ])['dateformatTime'];

    $timesArray12 = [
      '12:00 AM', '01:00 AM', '02:00 AM', '03:00 AM', '04:00 AM', '05:00 AM',
      '06:00 AM', '07:00 AM', '08:00 AM', '09:00 AM', '10:00 AM', '11:00 AM',
      '12:00 PM', '01:00 PM', '02:00 PM', '03:00 PM', "04:00 PM", '05:00 PM',
      '06:00 PM', '07:00 PM', '08:00 PM', '09:00 PM', '10:00 PM', '11:00 PM'
    ];

    $timesArray24 = [
      '00:00', '01:00', '02:00', '03:00', '04:00', '05:00', '06:00', '07:00',
      '08:00', '09:00', '10:00', '11:00', "12:00", '13:00', '14:00', '15:00',
      '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00'
    ];

    if ((strpos($timeSetting, '%H') !== FALSE) || (strpos($timeSetting, '%k') !== FALSE)) {
      $timeArray = $timesArray24;
    } else {
      $timeArray = $timesArray12;
    }

    return $timeArray;
  }

  /**
   * Get month names depending on the language of CiviCRM
   *
   * @return array
   */
  private static function getLocaleMonthNames() {
    $month = [
      1 => E::ts('January'),
      2 => E::ts('February'),
      3 => E::ts('March'),
      4 => E::ts('April'),
      5 => E::ts('May'),
      6 => E::ts('June'),
      7 => E::ts('July'),
      8 => E::ts('August'),
      9 => E::ts('September'),
      10 => E::ts('October'),
      11 => E::ts('November'),
      12 => E::ts('December')
    ];

    return $month;
  }

  /**
   * Check 'Enable Popup Forms'
   *
   * @return mixed
   */
  private static function isPopup(){
    return civicrm_api3('Setting', 'getsingle', [
      'sequential' => 1,
      'return' => ["ajaxPopupsEnabled"],
    ])["ajaxPopupsEnabled"];
  }

  /**
   * Return needed data for session schedule
   *
   * @param $eventId
   *
   * @return array
   */
  public static function getSessionScheduleData($eventId) {
    $data = [
      "is_popup" => self::isPopup(),
      "timeTypeArray" => self::getTimeTypeArray(),
      "monthNames" => self::getLocaleMonthNames(),
      "event_session_values" => self::getEventSessionsValues($eventId),
      "speakers" => self::getEventSpeakers($eventId),
      "start_and_end_time" => self::getStartAndEndTime($eventId),
      "venues" => CRM_CiviMobileAPI_Utils_Agenda_Venue::getVenuesByEventId($eventId),
      "default_user_image" => CRM_CiviMobileAPI_ExtensionUtil::url('/img/default-user-image.png'),
      "event_id" => $eventId
    ];

    return $data;
  }

}
