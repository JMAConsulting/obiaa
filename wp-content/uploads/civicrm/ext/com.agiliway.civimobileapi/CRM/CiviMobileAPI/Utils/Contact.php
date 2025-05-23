<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

/**
 * Class provide extension version helper methods
 */
class CRM_CiviMobileAPI_Utils_Contact {

  /**
   * @param int $contactId
   *
   * @throws \CiviCRM_API3_Exception
   */
  public static function logoutFromMobile($contactId) {
    civicrm_api4('Contact', 'update', [
      'values' => [
        'api_key' => '',
      ],
      'where' => [
        ['id', '=', $contactId],
      ],
      'checkPermissions' => FALSE,
    ]);

    $pushNotification = new CRM_CiviMobileAPI_BAO_PushNotification();
    $pushNotification->contact_id = $contactId;
    $pushNotification->find(TRUE);

    if (!empty($pushNotification->id)) {
      CRM_CiviMobileAPI_BAO_PushNotification::del($pushNotification->id);
    }

    CRM_Core_Session::setStatus(E::ts('Your Api key has removed and all device disconnected from account.'));

    CRM_Utils_System::redirect($_SERVER['HTTP_REFERER']);
  }

  /**
   * Gets display_name by Contact id
   *
   * @param int $contactId
   *
   * @return string
   */
  public static function getDisplayName($contactId) {
    if (empty($contactId)) {
      return '';
    }

    $displayName = civicrm_api4('Contact', 'get', [
      'select' => [
        'display_name',
      ],
      'where' => [
        ['id', '=', $contactId],
      ],
      'checkPermissions' => FALSE,
    ])->first()['display_name'];

    return $displayName ?? '';
  }

  /**
   * Gets current Contact id
   *
   * @return null|string
   */
  public static function getCurrentContactId() {
    $session = CRM_Core_Session::singleton();
    if (CRM_Contact_BAO_Contact_Utils::isContactId($session->get('userID'))) {
      return  $session->get('userID');
    }

    return false;
  }

  /**
   * Remove Contact's avatar
   *  -clears url on that file in Contact table
   *  -remove file on server
   *
   * @param $contactId
   *
   * @return bool
   */
  public static function removeContactAvatar($contactId) {
    $avatarFileName = CRM_CiviMobileAPI_Utils_File::getContactAvatarFileName($contactId);

    try {
      civicrm_api4('Contact', 'update', [
        'values' => [
          'image_URL' => '',
        ],
        'where' => [
          ['id', '=', $contactId],
        ],
        'checkPermissions' => FALSE,
      ]);
    } catch (CRM_Core_Exception $e) {
      return false;
    }

    if (!empty($avatarFileName)) {
      return CRM_CiviMobileAPI_Utils_File::removeUploadFile($avatarFileName);
    }

    return true;
  }

  /**
   * Is contact has 'api_key'
   *
   * @param int $contactId
   *
   * @return bool
   */
  public static function isContactHasApiKey($contactId) {
    if (empty($contactId)) {
      return FALSE;
    }

    $apiKey = CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_Contact', (int) $contactId, 'api_key');

    return !empty($apiKey);
  }

}
