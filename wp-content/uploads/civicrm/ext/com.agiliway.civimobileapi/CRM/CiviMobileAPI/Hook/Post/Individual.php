<?php

class CRM_CiviMobileAPI_Hook_Post_Individual {

  public static function run($op, $objectName, $objectId) {
    if ($objectName == 'Individual' && $op == 'edit') {
      try {
        $contact = CRM_Contact_BAO_Contact::findById($objectId);
        $apiKey = $contact->api_key;
      } catch (\CiviCRM_API3_Exception $e) {
        $apiKey = NULL;
      }
    }
  }
}
