<?php

namespace Civi\CiviMobileAPI\PushNotification\Utils;

use Civi;
use CRM_CiviMobileAPI_BAO_PushNotification;
use CRM_Contact_BAO_Contact;
use CRM_Core_Config;
use CRM_Core_Session;
use CRM_Utils_Hook;
use CRM_CiviMobileAPI_ExtensionUtil as E;

use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Pool;

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
    if (($key = array_search(CRM_Core_Session::getLoggedInContactID(), $contacts)) !== false) {
      unset($contacts[$key]);
    }

    $tokens = self::getContactTokens($contacts);

    if (empty($tokens)) {
      return;
    }

    $notificationBody = [
      'title' => $title,
      'body' => self::compileMessage($message),
    ];

    if (!PushNotificationHelper::isSimilarNotification($notificationBody['body'])) {
      PushNotificationHelper::addNotifications($notificationBody['body']);
    }

    $tokensForRequest = array_column($tokens, 'token');
    $result = [];

    if ($isCustomApp)
      $result = self::sendFirebasePush($notificationBody, $tokensForRequest, $data);
    else
      $result = self::sendCivimobilePush($notificationBody, $tokensForRequest, $data);

    if (!empty($result['failed_auth'])) {
      foreach ($tokens as $i => $token) {
        if($result['failed_auth'][$i]) {
          CRM_CiviMobileAPI_BAO_PushNotification::del($token['id']);
        }
      }
    }
  }

  private static function sendFirebasePush($notificationBody, $tokens, $data) {
    $token = self::generateFirebaseToken();

    if(!$token)
      return [];

    $key = 'Bearer ' . $token;

    $requestHeader = [
      'Content-Type' => 'application/json',
      'Authorization' => $key,
    ];

    $client = new Client([
      'base_uri' => self::FIREBASE_URL,
      'http_errors' => false,
    ]);

    $requests = function ($tokens) use ($notificationBody, $data, $requestHeader, $client) {
      foreach ($tokens as $token) {
        yield function () use ($client, $notificationBody, $data, $requestHeader, $token) {
          $messageData = [
            'message' => [
              'token' => $token,
              'notification' => $notificationBody,
              'data' => $data,
              'android' => [
                'priority' => 'high',
              ],
            ],
          ];

          return $client->postAsync('', [
            'headers' => $requestHeader,
            'json' => $messageData,
            'version' => 2.0,
          ]);
        };
      }
    };

    $pool = new Pool($client, $requests($tokens), [
      'concurrency' => 10,
      'fulfilled' => function ($response, $index) use (&$result) {
        $result[$index] = $response->getBody()->getContents();
      },
      'rejected' => function ($reason, $index) use (&$result) {
        $result[$index] = $reason->getMessage();
      },
    ]);

    $promise = $pool->promise();
    $promise->wait();

    $formattedResult = [];

    if (!empty($result)) {
      ksort($result);
      foreach ($result as $item) {
        $itemDecoded = json_decode($item, TRUE);

        if (isset($itemDecoded['error']) && $itemDecoded['error']['status'] == 'NOT_FOUND') {
          $formattedResult['failed_auth'][] = 1;
        }
        else {
          $formattedResult['failed_auth'][] = 0;
        }
      }
    }

    return $formattedResult;
  }

  private static function sendCivimobilePush($notificationBody, $tokens, $data) {
    $messageData = [
      'tokens' => $tokens,
      'notification' => $notificationBody,
      'data' => $data,
    ];

    $config = &CRM_Core_Config::singleton();
    $baseUrl = str_replace('/administrator/', '', $config->userFrameworkBaseURL);
    $key = Civi::settings()->get('civimobile_server_key');

    $requestHeader = [
      'Content-Type:application/json',
      'Site-Name:' . $baseUrl,
      'Authorization:' . $key,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, self::FCM_URL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeader);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($messageData));

    $res = curl_exec($ch);
    curl_close($ch);

    return json_decode($res, true);
  }

  private static function generateFirebaseToken()
  {
    $serviceAccount = json_decode(Civi::settings()->get('civimobile_firebase_key'), true);

    $privateKey = $serviceAccount['private_key'];
    $clientEmail = $serviceAccount['client_email'];

    $now = time();
    $payload = [
      "iss" => $clientEmail,
      "scope" => "https://www.googleapis.com/auth/cloud-platform",
      "aud" => "https://oauth2.googleapis.com/token",
      "iat" => $now,
      "exp" => $now + 3600,
    ];

    $jwt = JWT::encode($payload, $privateKey, 'RS256');

    $client = new Client();
    try {
      $response = $client->post("https://oauth2.googleapis.com/token", [
        'form_params' => [
          "grant_type" => "urn:ietf:params:oauth:grant-type:jwt-bearer",
          "assertion" => $jwt,
        ],
      ]);
    } catch (GuzzleException $e) {
      return false;
    }

    $data = json_decode($response->getBody(), true);
    return $data['access_token'];
  }

  private static function getContactTokens($contactIDs) {
    $tokens = [];
    foreach ($contactIDs as $id) {
      $contactTokens = CRM_CiviMobileAPI_BAO_PushNotification::getAll(['contact_id' => $id]);

      if (empty($contactTokens)) {
        continue;
      }

      foreach ($contactTokens as $contactToken) {
        $tokens[] = [
          'id' => $contactToken['id'],
          'token' => $contactToken['token'],
        ];
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
    $displayName = CRM_Contact_BAO_Contact::getValues($params, $default)->display_name;

    if (!empty($displayName)) {
      $message = str_replace('%display_name', CRM_Contact_BAO_Contact::getValues($params, $default)->display_name, $message);
    }

    return $message;
  }

}
