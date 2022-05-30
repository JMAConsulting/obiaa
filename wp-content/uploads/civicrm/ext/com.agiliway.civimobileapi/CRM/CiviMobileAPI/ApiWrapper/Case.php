<?php

/**
 * @deprecated will be deleted in version 7.0.0
 */
class CRM_CiviMobileAPI_ApiWrapper_Case implements API_Wrapper {

  /**
   * Interface for interpreting api input
   *
   * @param array $apiRequest
   *
   * @return array
   */
  public function fromApiInput($apiRequest) {
    $contactIds = [];
    $caseIds = [];

    if (!empty($apiRequest['params']['contact_display_name']) || !empty($apiRequest['params']['activity_type'])) {
      if (!empty($apiRequest['params']['contact_display_name'])) {
        $contactIds = CRM_CiviMobileAPI_Utils_CaseSummary::getContactsIdByName($apiRequest['params']['contact_display_name']);

        if (!$contactIds) {
          $apiRequest['contacts_is_not_found'] = 1;
        }
      }

      if (!empty($apiRequest['params']['activity_type'])) {
        $caseIds = CRM_CiviMobileAPI_Utils_CaseSummary::getCaseIdByActivities($apiRequest['params']['activity_type']);
      }

      if (!empty($apiRequest['params']['id']) && !empty($apiRequest['params']['activity_type'])) {
        $caseIds = array_intersect($caseIds, $apiRequest['params']['id']['IN']);
      }

      $apiRequest['params']['contact_id'] = !empty($contactIds) ? ['IN' => $contactIds] : NULL;
      $apiRequest['params']['id'] = (!empty($params['id']) || !empty($apiRequest['params']['activity_type'])) ? ["IN" => $caseIds] : NULL;
    }

    return $apiRequest;
  }

  /**
   * Adds next fields:
   * - short_description
   * - image_URL for each contact
   *
   * @param $apiRequest
   * @param $result
   *
   * @return array
   */
  public function toApiOutput($apiRequest, $result) {
    if (is_mobile_request()) {
      if (!empty($result['values'])) {
        $contactIds = $this->getCaseContactsId($result['values']);
        if ($apiRequest['contacts_is_not_found'] ?? null) {
          return [
            'count' => 0,
            'values' => [],
            'version' => 3,
          ];
        }

        try {
          $contacts = civicrm_api3('Contact', 'get', [
            'sequential' => 1,
            'contact_is_deleted' => 0,
            'id' => ["IN" => $contactIds],
            'options' => ['limit' => 0],
          ])['values'];
        } catch (CiviCRM_API3_Exception $e) {
          $contacts = [];
        }

        if (!empty($contacts)) {
          foreach ($contacts as $contact) {
            foreach ($result['values'] as &$case) {
              if ($contact['id'] == $case['contact_id'][1]) {
                if ($contact['contact_is_deleted']) {
                  $case['is_active'] = 0;
                } else {
                  $case['is_active'] = 1;
                }
                $case['contact_display_name'] = !empty($contact['display_name']) ? $contact['display_name'] : '';
                $case['contact_first_name'] = !empty($contact['first_name']) ? $contact['first_name'] : '';
                $case['contact_last_name'] = !empty($contact['last_name']) ? $contact['last_name'] : '';
                $case['contact_type'] = !empty($contact['contact_type']) ? $contact['contact_type'] : '';
                $case['contact_image_URL'] = !empty($contact['image_URL']) ? $contact['image_URL'] : '';
              }
            }
          }
        }
      }

      $editAllCase = CRM_Core_Permission::check('access all cases and activities');
      $editMyCase = CRM_Core_Permission::check('access my cases and activities');

      $result['your_roles'] = [];

      if (isset($result['contacts'])) {
        foreach ($result['contacts'] as $key => $contact) {
          if (!isset($contact['image_URL'])) {
            try {
              $imageUrl = civicrm_api3('Contact', 'getvalue', [
                'return' => 'image_URL',
                'id' => $contact['contact_id'],
              ]);
            } catch (Exception $e) {
              $imageUrl = '';
            }

            $result['contacts'][$key]['image_URL'] = $imageUrl;
          }

          if ($contact['contact_id'] == CRM_Core_Session::singleton()->get('userID')) {
            $result['your_roles'][] = $contact['role'];
          }
        }
      }

      $result['can_create_activity'] = $editMyCase || $editAllCase ? 1 : 0;
      $result['can_add_all_roles'] = $editAllCase ? 1 : 0;
      $result['can_add_ordinary_roles'] = $editAllCase ? 1 : 0;
    }

    return $result;
  }

  /**
   * Get case contact's Id
   *
   * @param $contributions
   * @return array
   */
  private function getCaseContactsId($cases) {
    $contactIds = [];
    if (!empty($cases)) {
      if (is_array($cases) || is_object($cases)) {
        foreach ($cases as $case) {
          $contactIds[] = $case['contact_id'][1];
        }
      }
    }

    return $contactIds;
  }

}
