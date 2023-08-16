<?php

class CRM_CiviMobileAPI_ApiWrapper_Survey_Getsingle implements API_Wrapper {

  /**
   * Interface for interpreting api input
   *
   * @param array $apiRequest
   *
   * @return array
   */
  public function fromApiInput($apiRequest) {
    return $apiRequest;
  }

  /**
   * Adds next fields:
   * - short_description
   *
   * @param $apiRequest
   * @param $result
   *
   * @return array
   */
  public function toApiOutput($apiRequest, $result) {

    $result['short_instructions'] = $result['instructions'] ? html_entity_decode(mb_substr(strip_tags(preg_replace('/\s\s+/', ' ', $result['instructions'])), 0, 200), ENT_QUOTES | ENT_HTML401) : '';

    return $result;
  }
}
