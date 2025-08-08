<?php

namespace Civi\CiviMobileAPI\AIBasedGenerator\Utils;

use Civi;
use CRM_Core_Config;
use CRM_Utils_Hook;

class AIBasedGeneratorHelper {

  public static function generate($params = '', $userInputAndData = []) {

    $conversation = [];

    $generateType = $params['generateType'];
    $campaignText = !empty($params['campaign']) ? ", which is part of the '{$params['campaign']}' campaign" : "";
    $message = 'Generate text in format for CKEDITOR for' ;
    $plainMessage = 'Generate plain text without markdown for';
    $betterMailingPrompt = 'Add styles to html elements itself to have proper support for mailing services and create more modern look.';

    if ($generateType == 'event-description' && !empty($params['title']) && !empty($params['type'])) {
      $message .= "'{$params['title']}' {$params['type']} event description.";
    }
    else if ($generateType == 'petition-introduction' && !empty($params['title'])) {
      $message .= "an introduction for the petition titled '{$params['title']}'{$campaignText}.";
    }
    else if ($generateType == 'thank-you-message' && !empty($params['title']) && !empty($params['thank_you_title'])) {
      $message .= "a thank-you message for the petition titled '{$params['title']}'{$campaignText}. The thank-you title is '{$params['thank_you_title']}'.";
    }
    else if ($generateType == 'survey-instructions' && !empty($params['title']) && !empty($params['activity_type'])) {
      $message .= "survey instructions for interviewers for the survey titled '{$params['title']}'{$campaignText}. The survey activity type is '{$params['activity_type']}'.";
    }
    else if ($generateType == 'campaign-goals' && !empty($params['title']) && !empty($params['type'])) {
      $message .= "campaign goals for '{$params['title']}' campaign with the campaign type '{$params['campaign_type']}'.";
    }
    else if ($generateType == 'campaign-description' && !empty($params['title']) && !empty($params['type'])) {
      $message = "$plainMessage campaign description for title '{$params['title']}' with the campaign type '{$params['campaign_type']}'.";
    }
    else if (($generateType == 'mailing-html' || $generateType == 'contact-mail-html') && !empty($params['subject'])) {
      $message .= "new mailing with subject '{$params['subject']}'. $betterMailingPrompt";
    }
    else if (($generateType == 'mailing-plain-text' || $generateType == 'contact-mail-plain-text') && !empty($params['subject'])) {
      $message = "$plainMessage new mailing with subject '{$params['subject']}'.";
    }
    else if ($generateType == 'message-template-html' && !empty($params['title'])) {
      $message .= "new message template for title '{$params['title']}'";
      if ($params['subject']) {
        $message .= " and subject '{$params['subject']}'.";
      }
      $message .= $betterMailingPrompt;
    }
    else if ($generateType == 'message-template-plain-text' && !empty($params['title'])) {
      $message = "$plainMessage new message template for title '{$params['title']}'";
      if ($params['subject']) {
        $message .= " and subject '{$params['subject']}'.";
      }
    }

    if (!empty($generateType)) {
      $conversation[] = [
        'role' => 'user',
        'content' => $message . 'Do not add any instructions how to use it'
      ];
    }

    if (!empty($userInputAndData)) {
      foreach ($userInputAndData as $data) {
        $conversation[] = $data;
      }
    }

    $apiUrl = self::getAiApiUrl();

    $postFields = [
      'model' => Civi::settings()->get('civimobile_ai_model'),
    ];

    if (strpos($apiUrl, 'v1/responses')) {
      $postFields['input'] = $conversation;
    }
    else {
      $postFields['messages'] = $conversation;
    }

    $config = &CRM_Core_Config::singleton();
    $baseUrl = str_replace('/administrator/', '', $config->userFrameworkBaseURL);

    $key = 'Bearer ' . Civi::settings()->get('civimobile_ai_secret_key');

    $requestHeader = [
      'Content-Type: application/json',
      'Site-Name: ' . $baseUrl,
      'Authorization: ' . $key,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeader);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postFields));

    $apiResponse = curl_exec($ch);
    curl_close($ch);

    $decodedResponse = json_decode($apiResponse, true);

    $content = NULL;

    if (isset($decodedResponse['output'][0]['content'][0]['text'])) {
      $content = $decodedResponse['output'][0]['content'][0]['text'];
    }
    elseif (isset($decodedResponse['choices'][0]['message']['content'])) {
      $content = $decodedResponse['choices'][0]['message']['content'];
    }

    if ($content === NULL) {
      return ['error' => json_encode($decodedResponse)];
    }

    return ['message' => $content];
  }

  /**
   * Returns AI API URL.
   *
   * @return string
   */
  private static function getAiApiUrl(): string {
    $aiApiUrl = Civi::settings()->get('civimobile_ai_api_url');
    if (empty($aiApiUrl)) {
      $aiApiUrl = 'https://api.openai.com/v1/responses';
    }
    return $aiApiUrl;
  }
}