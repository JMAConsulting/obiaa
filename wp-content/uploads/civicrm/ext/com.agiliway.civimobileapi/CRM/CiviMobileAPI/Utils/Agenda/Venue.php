<?php

class CRM_CiviMobileAPI_Utils_Agenda_Venue {

  /**
   * Returns full data about venues by EventId
   *
   * @param $eventId
   * @return array
   */
  public static function getVenuesByEventId($eventId) {
    $locationBlockId = self::getLocaleId($eventId);

    if (empty($locationBlockId)) {
      return [];
    }

    return civicrm_api3('CiviMobileVenue', 'get', [
      'sequential' => 1,
      'location_id' => $locationBlockId,
      'is_active' => 1,
      'options' => ['sort' => "weight asc"]
    ])['values'];
  }

  /**
   * Returns venues names by EventId
   *
   * @param $eventId
   * @return array
   * @throws CiviCRM_API3_Exception
   */
  public static function getVenuesNamesByEventId($eventId) {
    $venuesNames = [];
    $venuesData = self::getVenuesByEventId($eventId);

    foreach ($venuesData as $venue) {
      $venuesNames[$venue['id']] = $venue['name'];
    }

    return $venuesNames;
  }

  /**
   * Take event id and return locale id
   *
   * @param $eventId
   *
   * @return string|bool
   */
  public static function getLocaleId($eventId) {
    try {
      $event = civicrm_api3('Event', 'getsingle', [
        'return' => ["loc_block_id"],
        'id' => $eventId,
      ]);
    } catch (CiviCRM_API3_Exception $e) {
      return FALSE;
    }

    if (empty($event['loc_block_id'])) {
      return FALSE;
    }

    return $event['loc_block_id'];
  }

  /**
   * Is venue in Event
   *
   * @param $id
   * @param $eventId
   * @return bool
   */
  public static function issetVenue($id, $eventId) {
    if (empty($id)) {
      return FALSE;
    }

    $locationBlockId = self::getLocaleId($eventId);

    if (empty($locationBlockId)) {
      return FALSE;
    }

    try {
      civicrm_api3('CiviMobileVenue', 'getsingle', [
        'id' => $id,
        'location_id' => $locationBlockId,
        'is_active' => 1
      ]);
    } catch (CiviCRM_API3_Exception $e) {
      return FALSE;
    }


    return TRUE;
  }

  /**
   * Get location date. In this location replaces address to venue address,
   * generate geo codes and return them in array.
   *
   * @param $params
   *
   * @return array
   */
  public static function getVenueGeocoderData($params) {
    if (empty($params['address'])) {
      return [
        'latitude' => '',
        'longitude' => '',
      ];
    }
    $addressId = civicrm_api3('LocBlock', 'getsingle', [
      'return' => ["address_id"],
      'id' => $params['location_id'],
    ])['address_id'];
    $locationAddressData = civicrm_api3('Address', 'getsingle', ['id' => $addressId]);
    $venueAddressData = $locationAddressData;
    $venueAddressData['street_address'] = $params['address'];
    unset($venueAddressData['geo_code_1'], $venueAddressData['geo_code_2']);
    if (!empty($venueAddressData['country_id'])) {
      $venueAddressData['country'] = CRM_Core_PseudoConstant::country($venueAddressData['country_id']);
    }
    if (!empty($venueAddressData['state_province_id'])) {
      $venueAddressData['state_province'] = CRM_Core_PseudoConstant::stateProvince($venueAddressData['state_province_id']);
    }
    CRM_Core_BAO_Address::addGeocoderData($venueAddressData);
    if (!isset($venueAddressData['geo_code_1']) || !isset($venueAddressData['geo_code_2'])) {
      $venueAddressData['geo_code_1'] = '';
      $venueAddressData['geo_code_2'] = '';
    }

    return [
      'latitude' => $venueAddressData['geo_code_1'],
      'longitude' => $venueAddressData['geo_code_2'],
    ];
  }

  /**
   * Rebuild venue after changing event location data,
   *
   * @param $localeId
   */
  public static function rebuildVenueGeoDate($localeId) {
    $venues = civicrm_api3('CiviMobileVenue', 'get', [
      'sequential' => 1,
      'location_id' => $localeId,
      'options' => ['limit' => 0],
    ])['values'];
    foreach ($venues as $venue) {
      civicrm_api3('CiviMobileVenue', 'create', [
        'id' => $venue['id'],
        'location_id' => $venue['location_id'],
        'name' => $venue['name'],
        'address' => $venue['address'],
      ]);
    }
  }

  public static function getColorList() {
    return [
      [
        'background' => 'rgb(178,223,219)',
        'border' => 'rgb(128,203,196)'
      ],
      [
        'background' => 'rgb(187,222,251)',
        'border' => 'rgb(144,202,249)'
      ],
      [
        'background' => 'rgb(197,202,233)',
        'border' => 'rgb(159,168,218)'
      ],
      [
        'background' => 'rgb(248,187,208)',
        'border' => 'rgb(244,143,177)'
      ],
      [
        'background' => 'rgb(255,205,210)',
        'border' => 'rgb(239,154,154)'
      ],
      [
        'background' => 'rgb(255,248,225)',
        'border' => 'rgb(255,236,179)'
      ],
      [
        'background' => 'rgb(255,224,178)',
        'border' => 'rgb(255,204,128)'
      ],
      [
        'background' => 'rgb(215,204,200)',
        'border' => 'rgb(188,170,164)'
      ],
      [
        'background' => 'rgb(227,253,244)',
        'border' => 'rgb(155,219,197)'
      ],
      [
        'background' => 'rgb(200,230,201)',
        'border' => 'rgb(165,214,167)'
      ]
    ];
  }

  /**
   * Return next color in list of available colors by location id
   *
   * @param $locationId
   * @return array
   */
  public static function getNextColorInListForLocation($locationId) {
    $colors = self::getColorList();

    $color = $colors[0];

    $venues = civicrm_api3('CiviMobileVenue', 'get', [
      'sequential' => 1,
      'location_id' => $locationId,
    ])['values'];
    if (!empty($venues) != 0) {
      $lastVenue = $venues[count($venues) - 1];

      foreach ($colors as $key => $value) {
        if ($value['background'] == $lastVenue['background_color']) {
          $color = $colors[($key + 1) % count($colors)];
        }
      }
    }

    return $color;
  }
}
