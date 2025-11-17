<?php

class CRM_Obiaacustomizations_Contact {

public function fromApiInput($apiRequest) {
return $apiRequest;
}

public function toApiOutput($apiRequest, $result) {
  foreach ($result['values'] as $k => $value) {
    $businessContact = \Civi\Api4\Relationship::get(FALSE)
      ->addSelect('Business_Contact.Business_Contact_Position')
      ->addClause('OR', ['contact_id_a', '=', $value['id']], ['contact_id_b', '=', $value['id']])
      ->addWhere('relationship_type_id:name', '=', 'Employee of')
      ->addWhere('Business_Contact.Business_Contact_Position', 'IS NOT EMPTY')
      ->execute()
      ->first()['Business_Contact.Business_Contact_Position'] ?? NULL;
    if ($businessContact) {
      $result['values'][$k]['description'][] = $businessContact;
    }
  }
  return $result;
}

}
