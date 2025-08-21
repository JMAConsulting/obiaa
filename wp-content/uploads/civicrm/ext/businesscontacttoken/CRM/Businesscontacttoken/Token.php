<?php

class CRM_Businesscontacttoken_Token {
  const TOKEN = 'update_business';

  public static function businessFormLinks($contactId) {
    $relationships = \Civi\Api4\Relationship::get(FALSE)
      ->addSelect('contact.id', 'contact.display_name')
      ->addJoin('Contact AS contact', 'INNER', ['contact_id_b', '=', 'contact.id'])
      ->addWhere('contact_id_a', '=', $contactId)
      ->addWhere('contact.contact_sub_type', '=', 'Members_Businesses_')
      ->addWhere('relationship_type_id:name', '=', 'Employee of')
      ->execute();
    // $businesses = \Civi\Api4\Contact::get(FALSE)
    //   ->addJoin('Relationship', 'INNER', ['id', '=', 'relationship.contact_id_a'])
    //   ->addWhere('contact_sub_type', '=', 'Members_Businesses_')
    //   ->execute();
    $list = '<ul>';
    foreach ($relationships as $relationship) {
      $bid = $relationship['contact.id'];
      $businessName = $relationship['contact.display_name'];
      $checksum = CRM_Contact_BAO_Contact_Utils::generateChecksum($contactId);
      $url = CRM_Utils_System::baseCMSURL() . "update-business?cid=$contactId&bid=$bid&cs=$checksum";
      $list .= "<li><a href=\"$url\">$businessName</a></li>";
    }
    $list .= '</ul>';
    return $list;
  }
}
