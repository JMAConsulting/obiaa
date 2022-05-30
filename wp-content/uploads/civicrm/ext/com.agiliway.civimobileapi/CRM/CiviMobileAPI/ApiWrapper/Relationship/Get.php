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

      $result['total_count'] = $relationship->count();
    }

    return $result;
  }

}
