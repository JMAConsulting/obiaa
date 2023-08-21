<?php

namespace Civi\CiviMobileAPI\PushNotification\Utils;

use Civi;
use CRM_CiviMobileAPI_BAO_PushNotification;
use CRM_Contact_BAO_Contact;
use CRM_Core_Config;
use CRM_Core_Session;
use CRM_Utils_Hook;
use CRM_CiviMobileAPI_ExtensionUtil as E;

class PushNotificationSender {

  const FCM_URL = 'https://push.civimobile.org/rest.php';
  const FIREBASE_URL = 'https://fcm.googleapis.com/v1/projects/civimobile/messages:send';

  public static function send($title, $message, $contacts, $data) {
    if (empty($contacts)) {
      return;
    }

    $isCustomApp = Civi::settings()->get('civimobile_is_custom_app');

    // filter contacts (only unique contacts, without logged in contact)
    $contacts = array_unique($contacts);
    $key = array_search(CRM_Core_Session::getLoggedInContactID(), $contacts);

    if ($key) {
      unset($contacts[$key]);
    }

    $tokens = array_unique(self::getContactTokens($contacts));

    if (empty($tokens)) {
      return;
    }

    $notificationBody = [
      'title' => self::compileMessage($title),
      'body' => self::compileMessage($message),
    ];

    if (!PushNotificationHelper::isSimilarNotification($notificationBody['body'])) {
      PushNotificationHelper::addNotifications($notificationBody['body']);
    } else {
      return;
    }

    $postFields = [
      'registration_ids' => $tokens,
      'notification' => $notificationBody,
      'priority' => 'high',
      'sound' => 'default',
      'data' => $data,
    ];

    $config = &CRM_Core_Config::singleton();
    $baseUrl = str_replace('/administrator/', '', $config->userFrameworkBaseURL);
    $key = $isCustomApp ? 'Bearer ' . Civi::settings()->get('civimobile_firebase_key') : Civi::settings()->get('civimobile_server_key');

    $requestHeader = [
      'Content-Type:application/json',
      'Site-Name:' . $baseUrl,
      'Authorization:' . $key,
    ];

    $nullObject = CRM_Utils_Hook::$_nullObject;
    CRM_Utils_Hook::singleton()->commonInvoke(2, $notificationBody, $requestHeader,
      $nullObject, $nullObject, $nullObject, $nullObject, 'civimobile_send_push', '');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $isCustomApp ? self::FIREBASE_URL : self::FCM_URL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeader);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));

    curl_exec($ch);
    curl_close($ch);
  }

  private static function getContactTokens($contactIDs) {
    $tokens = [];

    foreach ($contactIDs as $id) {
      $contactTokens = CRM_CiviMobileAPI_BAO_PushNotification::getAll(['contact_id' => $id]);

      if (empty($contactTokens)) {
        continue;
      }

      foreach ($contactTokens as $contactToken) {
        $tokens[] = $contactToken['token'];
      }
    }

    return $tokens;
  }

  public static function compileMessage($message, $contactId = null) {
    if (empty($contactId)) {
      $contactId = CRM_Core_Session::singleton()->getLoggedInContactID();
    }

    $params = ['id' => $contactId];
    $default = [];
    $contact = CRM_Contact_BAO_Contact::getValues($params, $default);
    $i = 1;
    $replace = [];

    foreach ((array)$contact as $k => $value) {
      if (strpos($message, '%' . $k) !== FALSE) {
        $message = str_replace('%' . $k, '%' . $i, $message);
        $replace[$i] = $value;
        $i++;
      }
    }

    return E::ts($message, $replace);
  }

}
