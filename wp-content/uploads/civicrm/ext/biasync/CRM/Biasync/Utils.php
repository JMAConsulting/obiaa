<?php

use Civi\Api4\Property;
use Civi\Api4\PropertyOwner;
use Civi\Api4\Unit;
use Civi\Api4\UnitBusiness;
use Civi\Api4\Contact;

define('WPCMRF_ID', 1);

class CRM_Biasync_Utils {

  public static function syncToBIA(): bool {
    $options = ['limit' => 0];
    self::syncProperties($options);

    // Pull in the custom fields from the BIA
    /** --------------------------------------REPLACE---------------------------------------- */
    $biaCustomFields = wpcmrf_api('CustomField', 'get', [
      'sequential' => 1,
      'name' => ['IN' => ["BIA_Contact_ID", "BIA_Source", "BIA_Contact_Reference", "BIA_Activity_Source", "BIA_Activity_Source_ID"]],
    ], $options, WPCMRF_ID)->getReply()['values'];

    foreach ($biaCustomFields as $field) {
      if ($field['name'] == 'BIA_Contact_ID') {
        $biaContactID = $field['id'];
      }
      if ($field['name'] == 'BIA_Source') {
        $biaSource = $field['id'];
      }
      if ($field['name'] == 'BIA_Contact_Reference') {
        $biaRef = $field['id'];
      }
      if ($field['name'] == 'BIA_Activity_Source') {
        $activityBiaSource = $field['id'];
      }
      if ($field['name'] == 'BIA_Activity_Source_ID') {
        $activityBiaId = $field['id'];
      }
    }

    // Get a list of contacts to be synced.
    // TODO: Batch this using CiviCRM queue runner/specify a limit to be synced per cron run.

    $contacts = civicrm_api3('Contact', 'get', [
      'contact_sub_type' => ['IN' => ['Members_Property_Owners_', 'Members_Businesses_']],
      'options' => ['limit' => 0],
    ]);

    // Additional Custom fields to sync.
    $contactCustomFields = $biaContactCustomFields = $localSocialMediaAPIFields = $remoteSocialMediaAPIFields = $syncedContactIds = [];
    $contactCustomFieldsAPI = civicrm_api3('CustomField', 'get', [
      'sequential' => 1,
      'custom_group_id' => ['IN' => ["Membership_Status", "Ownership_Demographics", "Business_Details"]],
      'options' => ['limit' => 0],
    ]);
    foreach ($contactCustomFieldsAPI['values'] as $apiCustomField) {
      $contactCustomFields['custom_' . $apiCustomField['id']] = $apiCustomField['name'];
    }

    /** --------------------------------------REPLACE---------------------------------------- */
    $biaAPICustomFields = wpcmrf_api('CustomField', 'get', [
      'sequential' => 1,
      'custom_group_id' => ['IN' => ["Membership_Status", "Ownership_Demographics", "Business_Details"]],
    ], $options, WPCMRF_ID)->getReply()['values'];
    foreach ($biaAPICustomFields as $biaAPICustomField) {
      $biaContactCustomFields[$biaAPICustomField['name']] = 'custom_' . $biaAPICustomField['id'];
    }

    /** --------------------------------------REPLACE---------------------------------------- */
    $membershipCustomFields = wpcmrf_api('CustomField', 'get', [
      'sequential' => 1,
      'name' => ['IN' => ['BIA', 'Region']],
    ], $options, WPCMRF_ID)->getReply()['values'];
    $biaRegionField = civicrm_api3('CustomField', 'get', ['name' => 'What_region_is_this_BIA_in_']);
    $domainContactId = CRM_Core_BAO_Domain::getDomain()->contact_id;
    $domainDefaultInformation = civicrm_api3('Contact', 'get', [
      'return' => ['organization_name', 'custom_' . $biaRegionField['id']],
      'id' => $domainContactId,
    ])['values'][$domainContactId];
    $localSocialMediaGroup = civicrm_api3('CustomGroup', 'get', ['name' => 'Social_Media']);
    $localSocialMediaFields = civicrm_api3('CustomField', 'get', ['custom_group_id' => $localSocialMediaGroup['id']])['values'];
    foreach ($localSocialMediaFields as $localSocialMediaField) {
      $localSocialMediaAPIFields['custom_' . $localSocialMediaField['id']] = $localSocialMediaField['name'];
    }

    /** --------------------------------------REPLACE---------------------------------------- */
    $remoteSocialMediaGroup = wpcmrf_api('CustomGroup', 'get', [
      'name' => 'Social_Media',
    ], $options, WPCMRF_ID)->getReply();

    /** --------------------------------------REPLACE---------------------------------------- */
    $remoteSocialMediaFields = wpcmrf_api('CustomField', 'get', [
      'custom_group_id' => $remoteSocialMediaGroup['id'],
    ], $options, WPCMRF_ID)->getReply()['values'];
    foreach ($remoteSocialMediaFields as $remoteSocialMediaField) {
      $remoteSocialMediaAPIFields[$remoteSocialMediaField['name']] = 'custom_' . $remoteSocialMediaField['id'];
    }
    $syncParams = [$biaContactID, $biaSource, $biaRef, $contactCustomFields, $localSocialMediaAPIFields, $biaContactCustomFields, $domainDefaultInformation, $biaRegionField, $activityBiaSource, $activityBiaId, $membershipCustomFields, $remoteSocialMediaAPIFields];
    foreach ($contacts['values'] as $contact) {
      $syncedContactIds[] = $contact['id'];
      self::syncContact($contact, $syncParams);
    }
    $nonSyncedActivityContactIds = Contact::get(FALSE)
      ->addJoin('ActivityContact AS activity_contact', 'INNER', ['activity_contact.contact_id', '=', 'id'])
      ->addJoin('Activity AS activity', 'INNER', ['activity.id', '=', 'activity_contact.activity_id'])
      ->addWhere('id', 'NOT IN', $syncedContactIds)
      ->addWhere('activity.activity_type_id', 'IN', [82, 83, 84, 86, 87])
      ->addWhere('activity_contact.record_type_id', '=', 2)
      ->execute()
      ->column('id');
    $nonSyncedActivityContacts = civicrm_api3('Contact', 'get', ['id' => ['IN' => $nonSyncedActivityContactIds], 'options' => ['limit' => 0]]);
    foreach ($nonSyncedActivityContacts['values'] as $nonSyncedActivityContact) {
      self::syncContact($nonSyncedActivityContact, $syncParams);
    }
    return TRUE;
  }

  private static function obfuscate_string($str) {
    $cmsURL = Civi::paths()->getVariable('wp.frontend.base', 'url');
    $parts = explode('.', str_replace('https://', '', $cmsURL));
    $salt = $parts[0];
    $salt = str_pad($salt, 22, 0);
    return password_hash($str, PASSWORD_BCRYPT);
  }

  /**
   * Sync Properties and Units to central site
   * @param array $options APIv3Options.
   */
  protected static function syncProperties($options) {
    $properties = Property::get()->execute();
    $options = $propertyIds = [];
    foreach ($properties as $property) {
      $propertyIds[] = $property['id'];
      $propertyArray = (array) $property;
      $propertyArray['source_record'] = get_bloginfo('name');
      $propertyArray['source_record_id'] = $property['id'];
      $propertyArray['source_record_id'] = $property['id'];
      $propertyCheck = wpcmrf_api('Biasync', 'create', ['entity' => 'Property', 'params'=>$propertyArray], WPCMRF_ID)->getReply();

      // No property found, Biasync had to create one
      if ($propertyCheck['values'][0]['new_entity_created'] == 1) {
        $units = unit::get()->addWhere('property_id', '=', $property['id'])->execute();
        foreach ($units as $unit) {
          $unitArray = (array) $unit;
          $unitAddress = civicrm_api3('Address', 'get', ['id' => $unit['address_id']])['values'][$unit['address_id']];
          $unitArray['source_record_id'] = $unit['id'];
          unset($unitAddress['id']);
          unset($unitArray['id']);
          $unitAddress['contact_id'] = 'Null';
          $remoteUnitAddress = wpcmrf_api('Address', 'create', $unitAddress, $options, WPCMRF_ID)->getReply();
          $unitArray['address_id'] = $remoteUnitAddress['id'];
          $unitArray['source_record'] = get_bloginfo( 'name' );
          $unitArray['property_id'] = $propertyCheck['values'][0]['entity_id'];
          wpcmrf_api('Unit', 'create', $unitArray, $options, WPCMRF_ID)->getReply();
        }
      }
      // A property was found and Biasync updated it
      else {
        $units = unit::get()->addWhere('property_id', '=', $property['id'])->execute();
        $unitIds = [];
        foreach ($units as $unit) {
          $unitIds[] = $unit['id'];
          $unitArray = (array) $unit;
          $unitArray['property_id'] = $propertyCheck['values']['entity_id'];
          $unitArray['source_record_id'] = $unit['id'];
          $unitArray['source_record'] = get_bloginfo( 'name' );
          $unitAddress = civicrm_api3('Address', 'get', ['id' => $unit['address_id']])['values'][$unit['address_id']];
          $unitAddress['contact_id'] = 'Null';
          $params['unitAddress'] = $unitAddress;
          $params['unitArray'] = $unitArray;
          $params['source_record_id'] = $unit['id'];
          $params['source_record'] = $unitArray['source_record_id'];
          
          $remoteUnit = wpcmrf_api('Biasnyc', 'create', ['entity' => 'Unit', 'params' => $params], WPCMRF_ID)->getReply();
        }

        /** --------------------------------------REPLACE---------------------------------------- */
        $missingUnits = wpcmrf_api('Unit', 'get', ['property_id' => $propertyCheck['id'], 'source_record_id' => ['NOT IN' => $unitIds], 'source_record' => get_bloginfo('name')], $options, WPCMRF_ID)->getReply();
        foreach ($missingUnits['values'] as $missingUnit) {

          /** --------------------------------------REPLACE---------------------------------------- */
          $businesses = wpcmrf_api('UnitBusiness', 'get', ['unit_id' => $missingUnit['id']], $options, WPCMRF_ID)->getReply();
          foreach ($businesses['values'] as $business) {
            wpcmrf_api('UnitBusiness', 'delete', ['id' => $business['id']], $options, WPCMRF_ID);
          }
          wpcmrf_api('Unit', 'delete', ['id' => $missingUnit['id']], $options, WPCMRF_ID);
        }
      }
    }

    /** --------------------------------------REPLACE---------------------------------------- */
    $missingProperties = wpcmrf_api('Property', 'get', ['source_record_id' => ['NOT IN' => $propertyIds], 'source_record' => get_bloginfo('name')], $options, WPCMRF_ID)->getReply();
    foreach ($missingProperties['values'] as $missingProperties) {
      /** --------------------------------------REPLACE---------------------------------------- */
      $owners = wpcmrf_api('PropertyOwner', 'get', ['property_id' => $missingProperties['id']], $options, WPCMRF_ID)->getReply();
      /** --------------------------------------REPLACE---------------------------------------- */
      $units = wpcmrf_api('Unit', 'get', ['property_id' => $missingProperties['id']], $options, WPCMRF_ID)->getReply();
      foreach ($owners['values'] as $owner) {
        wpcmrf_api('PropertyOwner', 'delete', ['id' => $owner['id']], $options, WPCMRF_ID);
      }
      foreach ($units['values'] as $unit) {
        wpcmrf_api('Unit', 'delete', ['id' => $unit['id']], $options, WPCMRF_ID);
      }
    }
  }

  /**
   * Sync Property related activities
   * @param int $biaSourceContactId
   * @param int $centralBiaContactId
   * @param string $activityBiaSource
   * @param string $activityBiaId
   */
  protected static function syncActivities($biaSourceContactId, $centralBiaContactId, $activityBiaSource, $activityBiaId, $options): void {
    $activities = civicrm_api3('Activity', 'get', [
      'target_contact_id' => $biaSourceContactId,
      'activity_type_id' => ['IN' => [82, 83, 84, 86, 87]],
      'options' => ['limit' => 0],
    ]);
    foreach ($activities['values'] as $activity) {

      $activity['custom_' . $activityBiaSource] = get_bloginfo('name');
      $activity['target_contact_id'] = $centralBiaContactId;
      $activity['source_contact_id'] = 'user_contact_id';
      $activity['custom_' . $activityBiaId] = $activity['id'];
      $activity['activityBiaId'] = $activityBiaId;
      $activity['$activityBiaSource'] = $activityBiaSource;
      unset($activity['id']);
      unset($activity['source_contact_name']);

      $check = wpcmrf_api('Biasync', 'create', ['entity' => 'Activity', 'params' => $activity], WPCMRF_ID)->getReply();
    }
  }

  /**
   * Sync a contact to a remote site
   * @param array $contact - APIv3 Contact Record
   * @param array $syncParams - Parameters need to do Sync.
   */
  protected static function syncContact($contact, $syncParams): void {
    list($biaContactID, $biaSource, $biaRef, $contactCustomFields, $localSocialMediaAPIFields, $biaContactCustomFields, $domainDefaultInformation, $biaRegionField, $activityBiaSource, $activityBiaId, $membershipCustomFields, $remoteSocialMediaAPIFields) = $syncParams;
    $options = $contactAddress = $unitBusinesses = $properties = [];
      // Get the BIA contact ID if exists, else create a new contact.
      /** --------------------------------------REPLACE---------------------------------------- */
    $biaContact = wpcmrf_api('Contact', 'get', [
      'sequential' => 1,
      'return' => ['first_name', 'last_name', 'email', 'phone'],
      'custom_' . $biaContactID => $contact['id'],
      'custom_' . $biaSource => get_bloginfo( 'name' ),
    ], $options, WPCMRF_ID)->getReply()['values'][0];
    $additionalContactCustomInfo = civicrm_api3('Contact', 'get', [
       'id' => $contact['id'],
       'return' => array_merge(array_keys($contactCustomFields), array_keys($localSocialMediaAPIFields)),
    ])['values'][$contact['id']];
    if (in_array('Members_Property_Owners_', $contact['contact_sub_type'])) {
      $contactAddress = civicrm_api3('Address', 'get', ['contact_id' => $contact['id'], 'is_primary' => 1, 'sequential' => 1])['values'][0];
      $properties = PropertyOwner::get(FALSE)->addWhere('owner_id', '=', $contact['id'])->execute();
      // lets also look to see if we are a member business.
      if (in_array('Members_Businesses_', $contact['contact_sub_type'])) {
        $unitBusinesses = UnitBusiness::get(FALSE)->addWhere('business_id', '=', $contact['id'])->execute();
      }
    }
    else {
      // We are just a member business.
      $unitBusinesses = UnitBusiness::get(FALSE)->addWhere('business_id', '=', $contact['id'])->execute();
    }
    if (empty($biaContact['id'])) {
      // No contact exists, proceed to create.
      $contactParams = self::prepareContactParams($contact, $contactCustomFields, $membershipCustomFields, $localSocialMediaAPIFields, $additionalContactCustomInfo, $biaContactCustomFields, $domainDefaultInformation, $biaContactID, $biaSource, $biaRef, $biaRegionField, $remoteSocialMediaAPIFields);
      $ff = wpcmrf_api('Contact', 'create', $contactParams, $options, WPCMRF_ID)->getReply();
      self::syncActivities($contact['id'], $ff['id'], $activityBiaSource, $activityBiaId, $options);
      if (!empty($contactAddress)) {
        unset($contactAddress['id']);
        $contactAddress['contact_id'] = $ff['id'];
        wpcmrf_api('Address', 'create', $contactAddress, $options, WPCMRF_ID)->getReply();
        if (!empty($unitBusinesses)) {
          foreach ($unitBusinesses as $business) {
            $biaUnitBusiness = (array) $business;
            $biaUnitBusiness['business_id'] = $ff['id'];
            /** --------------------------------------REPLACE---------------------------------------- */
            $biaUnitBusiness['unit_id'] = wpcmrf_api('Unit', 'get', ['source_record_id' => $business['unit_id'], 'source_record' => get_bloginfo( 'name' )], $options, WPCMRF_ID)->getReply()['id'];
            wpcmrf_api('UnitBusiness', 'create', $biaUnitBusiness, $options, WPCMRF_ID);
          }
        }
        if (!empty($properties)) {
          foreach ($properties as $property) {
            $biaProperty = $property;
            unset($biaProperty['id']);

            /** --------------------------------------REPLACE---------------------------------------- */
            $biaProperty['property_id'] = wpcmrf_api('Property', 'get', ['source_record_id' => $property['property_id'], 'source_record' => get_bloginfo( 'name' )], $options, WPCMRF_ID)->getReply()['id'];
            $biaProperty['owner_id'] = $ff['id'];

            /** --------------------------------------REPLACE---------------------------------------- */
            $check = wpcmrf_api('PropertyOwner', 'get', ['property_id' => $biaProperty['property_id'], 'owner_id' => $biaProperty['owner_id']], $options, WPCMRF_ID)->getReply();
            if (!empty($check['values'])) {
              $biaProperty['id'] = $check['id'];
            }
            wpcmrf_api('PropertyOwner', 'create', $biaProperty, $options, WPCMRF_ID);
          }
        }
      }
    }
    else {
      // Contact is found, we update it.
      $contactParams = self::prepareContactParams($contact, $contactCustomFields, $membershipCustomFields, $localSocialMediaAPIFields, $additionalContactCustomInfo, $biaContactCustomFields, $domainDefaultInformation, $biaContactID, $biaSource, $biaRef, $biaRegionField, $remoteSocialMediaAPIFields);
      $contactParams['id'] = $biaContact['id'];
      self::compareRemoteRecord($contactParams, $biaContact['id'], $biaContactCustomFields);
      wpcmrf_api('Contact', 'create', $contactParams, $options, WPCMRF_ID)->getReply();
      self::syncActivities($contact['id'], $biaContact['id'], $activityBiaSource, $activityBiaId, $options);
      if (!empty($contactAddress)) {
        /** --------------------------------------REPLACE---------------------------------------- */
        $biaAddress = wpcmrf_api('Address', 'get', ['contact_id' => $biaContact['id'], 'is_primary' => 1], $options, WPCMRF_ID)->getReply();
        if (!empty($biaAddress['values'])) {
          $contactAddress['id'] = $biaAddress['id'];
        }
        $contactAddress['contact_id'] = $biaContact['id'];
        wpcmrf_api('Address', 'create', $contactAddress, $options, WPCMRF_ID)->getReply();
      }
      if (!empty($unitBusinesses)) {
        foreach ($unitBusinesses as $business) {
          $biaUnitBusiness = (array) $business;
          $biaUnitBusiness['business_id'] = $biaContact['id'];
          /** --------------------------------------REPLACE---------------------------------------- */
          $biaUnitBusiness['source_record_id'] = $business['unit_id'];
          $biaUnitBusiness['source_record'] = get_bloginfo('name');
          $biaUnitBusiness['business_id'] = $biaContact['id'];
          
          $biaUnitBusiness['unit_id'] = wpcmrf_api('Unit', 'get', ['source_record_id' => $business['unit_id'], 'source_record' => get_bloginfo( 'name' )], $options, WPCMRF_ID)->getReply()['id'];
          /** --------------------------------------REPLACE---------------------------------------- */
          $remoteBiaUnitBusiness = wpcmrf_api('Biasync', 'create', ['entity' => 'UnitBusiness', 'params' => $biaUnitBusiness], WPCMRF_ID)->getReply();
          


        }
      }
      if (!empty($properties)) {
        foreach ($properties as $property) {
          $biaProperty = $property;
          unset($biaProperty['id']);
          /** --------------------------------------REPLACE---------------------------------------- */
          $biaProperty['property_id'] = wpcmrf_api('Property', 'get', ['source_record_id' => $property['property_id'], 'source_record' => get_bloginfo( 'name' )], $options, WPCMRF_ID)->getReply()['id'];
          unset($biaProperty['id']);
          $biaProperty['owner_id'] = $biaContact['id'];
          /** --------------------------------------REPLACE---------------------------------------- */
          $check = wpcmrf_api('PropertyOwner', 'get', ['property_id' => $biaProperty['property_id'], 'owner_id' => $biaProperty['owner_id']], $options, WPCMRF_ID)->getReply();
          if (!empty($check['values'])) {
            $biaProperty['id'] = $check['id'];
          }
          wpcmrf_api('PropertyOwner', 'create', $biaProperty, $options, WPCMRF_ID);
        }
      }
    }
  }

  /**
   * Format contact Parameters ready for passing over to the remote site
   * @param array $contact
   * @param array $contactCustomFields
   * @param array $membershipCustomFields
   * @param array $localSocialMediaAPIFields
   * @param array $additionalContactCustomInfo
   * @param array $biaContactCustomFields
   * @param array $domainDefaultInformation
   * @param string $biaContactID
   * @param string $biaSource
   * @param string $biaRef
   * @param array $biaRegionField
   * @param array $remoteSocialMediaAPIFields
   *
   * @return array
   */
  private static function prepareContactParams($contact, $contactCustomFields, $membershipCustomFields, $localSocialMediaAPIFields, $additionalContactCustomInfo, $biaContactCustomFields, $domainDefaultInformation, $biaContactID, $biaSource, $biaRef, $biaRegionField, $remoteSocialMediaAPIFields): array {
    $contactParams = [
      'contact_type' => $contact['contact_type'],
      'contact_sub_type' => $contact['contact_sub_type'],
      'first_name' => !empty($contact['first_name']) ? self::obfuscate_string($contact['first_name']) : '',
      'last_name' => !empty($contact['last_name']) ? self::obfuscate_string($contact['last_name']) : '',
      'organization_name' => !empty($contact['organization_name']) ? self::obfuscate_string($contact['organization_name']) : '',
      'custom_' . $biaContactID => $contact['id'],
      'custom_' . $biaSource => get_bloginfo( 'name' ),
      'custom_' . $biaRef => CRM_Utils_System::url('civicrm/contact/view', "reset=1&cid=" . $contact['id'], TRUE),
    ];
    foreach ($contactCustomFields as $api3Key => $fieldName) {
      if (!empty($additionalContactCustomInfo[$api3Key])) {
        $contactParams[$biaContactCustomFields[$fieldName]] = $additionalContactCustomInfo[$api3Key];
      }
    }
    foreach ($membershipCustomFields as $membershipCustomField) {
      if ($membershipCustomField['name'] === 'Region') {
        $contactParams['custom_' . $membershipCustomField['id']] = $domainDefaultInformation['custom_' . $biaRegionField['id']];
      }
      else {
        $contactParams['custom_' . $membershipCustomField['id']] = $domainDefaultInformation['organization_name'];
      }
    }
    foreach ($localSocialMediaAPIFields as $apiField => $customFieldName) {
      if (!empty($additionalContactCustomInfo[$apiField]) && !empty($remoteSocialMediaAPIFields[$customFieldName])) {
        $contactParams[$remoteSocialMediaAPIFields[$customFieldName]] = 1;
      }
    }
    return $contactParams;
  }

  private static function compareRemoteRecord($contactParams, $biaContactId, $biaContactCustomFields): void {
    $options = $differences = [];
    /** --------------------------------------REPLACE---------------------------------------- */
    $remoteRecord = wpcmrf_api('Contact', 'get', ['id' => $biaContactId, 'return' => array_values($biaContactCustomFields)], $options, WPCMRF_ID)->getReply();
    foreach ($biaContactCustomFields as $customFieldName => $customField) {
      if (isset($contactParams[$customField]) && $contactParams[$customField] != $remoteRecord['values'][0][$customField]) {
        $differences[$customFieldName] = $remoteRecord['values'][$biaContactId][$customField];
      }
    }
    if (!empty($differences)) {
      $message = '<p>The following contact details were changed in the remote sync</p>';
      foreach ($differences as $customField => $originalValue) {
        $message .= '<p>' . $customField . ' Original value was ' . $originalValue . '</p>';
      }
      wpcmrf_api('Activity', 'create', [
        'source_contact_id' => 'user_contact_id',
        'target_contact_id' => $biaContactId,
        'activity_type_id' => 'changed_contact_details',
        'subject' => 'Contact Details changed via sync from bia site',
        'details' => $message,
        'status_id' => 'Completed',
      ], $options, WPCMRF_ID)->getReply();
    }
  }

}
