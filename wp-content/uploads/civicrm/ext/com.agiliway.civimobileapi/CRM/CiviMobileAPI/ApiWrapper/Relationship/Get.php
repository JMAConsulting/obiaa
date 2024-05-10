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

      $result['total_count'] = $this->getTotalCount($relationship);
    }

    return $result;
  }

  private function getTotalCount($relationship) {

    $relatedContact = !empty($relationship->contact_id_b) ? $relationship->contact_id_b : CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_Relationship', $relationship->id, 'contact_id_b');

    $query = '
      SELECT COUNT(r.id)
      FROM civicrm_relationship r
      LEFT JOIN civicrm_contact ct ON ct.id = r.contact_id_a
      WHERE r.contact_id_b = %1 AND r.is_active = 1 AND ct.is_deleted = 0
    ';

    $numRelated = CRM_Core_DAO::singleValueQuery($query, [
      1 => [$relatedContact, 'Integer']
    ]);

    return $numRelated;
  }
}
