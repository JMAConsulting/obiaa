<?php

/**
 * @deprecated will be deleted in version 7.0.0
 */
class CRM_CiviMobileAPI_ApiWrapper_ContributionPage implements API_Wrapper {

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
   * Adds extra field
   *
   * @param $apiRequest
   * @param $result
   *
   * @return array
   */
  public function toApiOutput($apiRequest, $result) {
    if (is_mobile_request()) {
      if (!empty($result['values'])) {
        foreach ($result['values'] as &$contributionPage) {
          $contributionPage['page_URL'] = CRM_CiviMobileAPI_Utils_CiviCRM::getContributionPageUrl($contributionPage['id']);
        }
      }

    }
    return $result;
  }
}
