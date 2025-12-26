<?php

/**
 * @deprecated will be deleted in version 7.0.0
 */
class CRM_CiviMobileAPI_ApiWrapper_Relationship_Get implements API_Wrapper {

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
   * Interface for interpreting api output
   *
   * @param $apiRequest
   * @param $result
   *
   * @return array
   */
  public function toApiOutput($apiRequest, $result) {
    if (!empty($apiRequest['params']['contact_id_b'])) {
      $relationship = new CRM_Contact_DAO_Relationship();

      $relationship->is_active = 1;
      $relationship->contact_id_b = $apiRequest['params']['contact_id_b'];

      $relationship->selectAdd();
      $relationship->selectAdd('contact_id_b, is_active');
      $relationship->find(TRUE);

      $result['total_count'] = $this->getTotalCount($apiRequest['params']['contact_id_b']);
    }

    return $result;
  }

  private function getTotalCount($contactIdB) {
    if (is_array($contactIdB)) {
      $contactIds = $contactIdB['IN'] ?? $contactIdB;
    } else {
      $contactIds = [$contactIdB];
    }

    $contactIds = array_filter(array_map('intval', (array) $contactIds));

    if (empty($contactIds)) {
      return 0;
    }

    $params = [];
    $placeholders = [];

    foreach ($contactIds as $index => $contactId) {
      $key = $index + 1;
      $placeholders[] = "%{$key}";
      $params[$key] = [$contactId, 'Integer'];
    }

    $query = "
    SELECT COUNT(r.id) AS total
    FROM civicrm_relationship r
    LEFT JOIN civicrm_contact ct ON ct.id = r.contact_id_a
    WHERE r.is_active = 1
      AND ct.is_deleted = 0
      AND r.contact_id_b IN (" . implode(',', $placeholders) . ")
  ";

    return (int) CRM_Core_DAO::singleValueQuery($query, $params);
  }
}
