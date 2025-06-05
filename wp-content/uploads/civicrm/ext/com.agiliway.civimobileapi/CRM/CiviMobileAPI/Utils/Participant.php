<?php

/**
 * Class provide Participant helper methods
 */
class CRM_CiviMobileAPI_Utils_Participant {

  /**
   * Gets Contact ids registered on Event
   *
   * @param $eventId
   *
   * @return array
   */
  public static function getContactIds($eventId) {
    $contactIds = [];
    if (empty($eventId)) {
      return $contactIds;
    }

    $participants = civicrm_api4('Participant', 'get', [
      'select' => [
        'contact_id',
      ],
      'where' => [
        ['event_id', '=', $eventId],
      ],
      'checkPermissions' => FALSE,
    ])->getArrayCopy();

    if (empty($participants)) {
      return $contactIds;
    }

    foreach ($participants as $participant) {
      $contactIds[] = $participant['contact_id'];
    }

    return $contactIds;
  }

  /**
   * Gets Participant by Event id and Contact id
   *
   * @param $eventId
   *
   * @param $contactId
   *
   * @return array
   */
  public static function getByEventAndContactId($eventId, $contactId) {
    if (empty($eventId) || empty($contactId)) {
      return [];
    }

    return civicrm_api4('Participant', 'get', [
      'select' => [
        'status_id:name',
        'role_id:name',
        '*',
      ],
      'where' => [
        ['event_id', '=', $eventId],
        ['contact_id', '=', $contactId]
      ],
      'checkPermissions' => FALSE,
    ])->getArrayCopy();
  }

  /**
   * Gets Participant's Contribution
   *
   * @param $participantId
   *
   * @return \CRM_Contribute_BAO_Contribution|null
   */
  public static function getParticipantContribution($participantId) {
    $participantContributionId = CRM_Core_DAO::getFieldValue('CRM_Event_DAO_ParticipantPayment', $participantId, 'contribution_id', 'participant_id');

    if (empty($participantContributionId)) {
      return NULL;
    }

    $contribution = new CRM_Contribute_BAO_Contribution();
    $contribution->id = $participantContributionId;
    $contribution->find(TRUE);

    if (empty($contribution)) {
      return NULL;
    }

    return $contribution;
  }

  /**
   * Generate public key with random value
   *
   * @param $participantId
   *
   * @return bool|string
   */
  public static function generatePublicKey($participantId) {
    $salt = 'e7872a418810db83ac32ff9904a4cac735ce23a67a312df3db8bb15fa2c28ea9bcff42cfc15742ad3sdf3ac3sdf3a67a2a';
    $publicKey = md5($participantId . $salt . time()) . md5(rand(0, 10000) . $participantId . time()) . time();

    $length = strlen($publicKey);
    if ($length > 255) {
      return substr($publicKey, $length - 255);
    }

    return $publicKey;
  }

  /**
   * Returns array with names of participants
   *
   * @param $contactIds
   * @param $eventId
   * @return array
   */
  public static function getParticipantsNames($contactIds, $eventId) {
    if (empty($contactIds)) {
      return [];
    }

    $participants = civicrm_api4('Participant', 'get', [
      'select' => [
        'contact_id.display_name',
        'id',
        'contact_id',
      ],
      'where' => [
        ['event_id', '=', $eventId],
        ['contact_id', 'IN', $contactIds],
      ],
      'checkPermissions' => FALSE,
    ])->getArrayCopy();

    return self::getParticipantsShortDetails($participants);
  }

  /**
   * Returns short participants details (display_name,id,contact_id)
   *
   * @param $participants
   * @return array
   */
  public static function getParticipantsShortDetails($participants) {
    $names = [];

    foreach ($participants as $speaker) {
      $names[] = [
        'display_name' => $speaker["contact_id.display_name"],
        'id' => $speaker["id"],
        'contact_id' => $speaker["contact_id"]
      ];
    }

    return $names;
  }

}
