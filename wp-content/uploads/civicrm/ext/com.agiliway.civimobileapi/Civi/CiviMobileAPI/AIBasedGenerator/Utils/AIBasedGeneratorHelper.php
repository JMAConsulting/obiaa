<?php

namespace Civi\CiviMobileAPI\AIBasedGenerator\Utils;

use Civi;
use CRM_Core_Config;
use CRM_Utils_Hook;

class AIBasedGeneratorHelper {

  const OPENAI_URL = 'https://api.openai.com/v1/chat/completions';

  public static function generate($title = '', $type = '', $userInputAndData = []) {

    $conversation = [];

    if (!empty($title) && !empty($type)) {
      $conversation[] = [
        'role' => 'user',
        'content' => "Generate text for '$title' $type event description."
      ];
    }

    if (!empty($userInputAndData)) {
      foreach ($userInputAndData as $data) {
        $conversation[] = $data;
      }
    }

    $postFields = [
      'model' => 'gpt-3.5-turbo',
      'messages' => $conversation,
    ];

    $config = &CRM_Core_Config::singleton();
    $baseUrl = str_replace('/administrator/', '', $config->userFrameworkBaseURL);

    $key = 'Bearer ' . Civi::settings()->get('civimobile_openai_secret_key');

    $requestHeader = [
      'Content-Type: application/json',
      'Site-Name: ' . $baseUrl,
      'Authorization: ' . $key,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, self::OPENAI_URL);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeader);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));

    $apiResponse = curl_exec($ch);
    curl_close($ch);

    return json_decode($apiResponse, true);
  }
}