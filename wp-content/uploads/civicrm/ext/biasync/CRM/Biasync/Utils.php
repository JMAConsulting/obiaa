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
    $biaAPICustomFields = wpcmrf_api('CustomField', 'get', [
      'sequential' => 1,
      'custom_group_id' => ['IN' => ["Membership_Status", "Ownership_Demographics", "Business_Details"]],
    ], $options, WPCMRF_ID)->getReply()['values'];
    foreach ($biaAPICustomFields as $biaAPICustomField) {
      $biaContactCustomFields[$biaAPICustomField['name']] = 'custom_' . $biaAPICustomField['id'];
    }
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
    $remoteSocialMediaGroup = wpcmrf_api('CustomGroup', 'get', [
      'name' => 'Social_Media',
    ], $options, WPCMRF_ID)->getReply();
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
    $properties = \Civi\Api4\Property::get(TRUE)
      ->addJoin('PropertyLog AS property_log', 'LEFT', ['id', '=', 'property_log.property_id'])
      ->addWhere('property_log.is_synced', '=', FALSE)
      ->execute();

    $options = $propertyIds = [];
    foreach ($properties as $property) {
      $propertyArray = (array) $property;
      $propertyCheck = wpcmrf_api('Property', 'get', ['source_record_id' => $property['id'], 'source_record' => get_bloginfo( 'name' ), 'sequential' => 1], $options, WPCMRF_ID)->getReply();
      $propertyIds[] = $property['id'];
      // No Property found.
      if (empty($propertyCheck['values'])) {
        $propertyArray['source_record_id'] = $property['id'];
        $propertyArray['source_record'] = get_bloginfo( 'name' );
        unset($propertyArray['id']);
        $prop = wpcmrf_api('Property', 'create', $propertyArray, $options, WPCMRF_ID)->getReply();
        $units = unit::get()->addWhere('property_id', '=', $property['id'])->execute();
        foreach ($units as $unit) {
          $unitArray = (array) $unit;
          $unitAddress = civicrm_api3('Address', 'get', ['id' => $unit['address_id']])['values'][$unit['address_id']];
          $unitArray['source_record_id'] = $unit['id'];
          unset($unitAddress['id']);
          unset($unitArray['id']);
          $remoteUnitAddress = wpcmrf_api('Address', 'create_property_address', $unitAddress, $options, WPCMRF_ID)->getReply();
          $unitArray['address_id'] = $remoteUnitAddress['id'];
          $unitArray['source_record'] = get_bloginfo( 'name' );
          $unitArray['property_id'] = $prop['id'];
          wpcmrf_api('Unit', 'create', $unitArray, $options, WPCMRF_ID)->getReply();
        }
      }
      else {
        // Property has been found.
        // Update id to be the remote id.
        // only trigger update if property has changed
        if (self::comparePropertyRecord($propertyArray, $propertyCheck['values'][0])) {
          $propertyArray['id'] = $propertyCheck['id'];
          $prop = wpcmrf_api('Property', 'create', $propertyArray, $options, WPCMRF_ID)->getReply();
        }
        $units = unit::get()->addWhere('property_id', '=', $property['id'])->execute();
        $unitIds = [];
        foreach ($units as $unit) {
          $unitArray = (array) $unit;
          $unitIds[] = $unit['id'];
          $unitAddress = civicrm_api3('Address', 'get', ['id' => $unit['address_id']])['values'][$unit['address_id']];
          $remoteUnit = wpcmrf_api('Unit', 'get', ['source_record_id' => $unit['id'], 'source_record' => get_bloginfo( 'name' ), 'sequential' => 1], $options, WPCMRF_ID)->getReply();
          // If we have a remote unit replace the id field in unitArray and the id of the unitAddress array with the relevant id from the remote unit record.
          if (!empty($remoteUnit['values'])) {
            $unitAddress['id'] = $remoteUnit['values'][0]['address_id'];
            $unitArray['id'] = $remoteUnit['id'];
          }
          else {
            // Otherwise we are going to be creating a unit so unset the id fields.
            unset($unitAddress['id']);
            unset($unitArray['id']);
          }
          // only trigger creation of unit if unit details have changed
          if (self::compareUnitRecord($unitArray, $remoteUnit['values'][0])) {
            $unitArray['property_id'] = $propertyCheck['id'];
            $remoteAddress = wpcmrf_api('Address', 'create_property_address', $unitAddress, $options, WPCMRF_ID)->getReply();
            $unitArray['address_id'] = $remoteAddress['id'];
            $unitArray['source_record_id'] = $unit['id'];
            $unitArray['source_record'] = get_bloginfo( 'name' );
            wpcmrf_api('Unit', 'create', $unitArray, $options, WPCMRF_ID)->getReply();
          }
        }
        $missingUnits = wpcmrf_api('Unit', 'get', ['property_id' => $propertyCheck['id'], 'source_record_id' => ['NOT IN' => $unitIds], 'source_record' => get_bloginfo('name')], $options, WPCMRF_ID)->getReply();
        foreach ($missingUnits['values'] as $missingUnit) {
          $businesses = wpcmrf_api('UnitBusiness', 'get', ['unit_id' => $missingUnit['id']], $options, WPCMRF_ID)->getReply();
          foreach ($businesses['values'] as $business) {
            wpcmrf_api('UnitBusiness', 'delete', ['id' => $business['id']], $options, WPCMRF_ID);
          }
          wpcmrf_api('Unit', 'delete', ['id' => $missingUnit['id']], $options, WPCMRF_ID);
        }
      }
      $log = \Civi\Api4\PropertyLog::update(TRUE)
        ->addWhere('property_id','=',$property['id'])
        ->addValue('is_synced',TRUE)
        ->execute();
    }
    $missingProperties = wpcmrf_api('Property', 'get', ['source_record_id' => ['NOT IN' => $propertyIds], 'source_record' => get_bloginfo('name')], $options, WPCMRF_ID)->getReply();
    foreach ($missingProperties['values'] as $missingProperties) {
      $owners = wpcmrf_api('PropertyOwner', 'get', ['property_id' => $missingProperties['id']], $options, WPCMRF_ID)->getReply();
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
   * Compare local property record and remote property record
   * @param array $biaPropertyRecord - APIv4 Array of Property.get from local BIA
   * @param array $centralPropertyRecord - APIv3 Array of Property.get from Central Site
   *
   * @return bool
   */
  private static function comparePropertyRecord(array $biaPropertyRecord, array $centralPropertyRecord): bool {
    $recordsDiffer = FALSE;
    foreach ($biaPropertyRecord as $fieldName => $value) {
      if ($fieldName !== 'id' && (!empty($value) && (!isset($centralPropertyRecord[$fieldName]) || $value != $centralPropertyRecord[$fieldName]) || (empty($value) && !empty($centralPropertyRecord[$fieldName])))) {
        $recordsDiffer = TRUE;
        \Civi::log()->debug('BIA Sync: Property fields differ ' . $fieldName, ['local value' => $value, 'remote value' => $centralPropertyRecord[$fieldName]]);
      }
    }
    return $recordsDiffer;
  }

  /**
   * Compare local unit record and remote Unit record
   * @param array $biaUnitRecord - APIv4 Array of Unit.get from local BIA
   * @param array $centralUnitRecord - APIv3 Array of Unit.get from Central Site
   *
   * @return bool
   */
  private static function compareUnitRecord(array $biaUnitRecord, array $centralUnitRecord): bool {
    $recordsDiffer = FALSE;
    foreach ($biaUnitRecord as $fieldName => $value) {
      if ($fieldName !== 'id' && $fieldName !== 'property_id' && $fieldName !== 'address_id' && ((!empty($value) && (!isset($centralUnitRecord[$fieldName]) || $value != $centralUnitRecord[$fieldName])) || (empty($value) && !empty($centralUnitRecord[$fieldName])))) {
        $recordsDiffer = TRUE;
        \Civi::log()->debug('BIA Sync: Unit fields differ ' . $fieldName, ['local value' => $value, 'remote value' => $centralUnitRecord[$fieldName]]);
      }
    }
    return $recordsDiffer;
  }

  /**
   * Sync Property related activities
   * @param int $biaSourceContactId
   * @param int $centralBiaContactId
   * @param string $activityBiaSource
   * @param string $activityBiaId
   */
  protected static function syncActivities($biaSourceContactId, $centralBiaContactId, $activityBiaSource, $activityBiaId, $options): void {
    $activities = \Civi\Api4\Activity::get(TRUE)
      ->addWhere('target_contact_id', '=', $biaSourceContactId)
      ->addWhere('activity_type_id', 'IN', [82, 83, 84, 86, 87])
      ->addWhere('Is_Synced_Activities.is_synced', '=', 0)
      ->execute();

    foreach ($activities['values'] as $activity) {
      $check = wpcmrf_api('Activity', 'get', ['custom_' . $activityBiaSource => get_bloginfo( 'name' ), 'custom_' . $activityBiaId => $activity['id']], $options, WPCMRF_ID)->getReply();
      if ($check['count'] > 0) {
        continue;
      }
      $activity['target_contact_id'] = $centralBiaContactId;
      $activity['source_contact_id'] = 'user_contact_id';
      unset($activity['source_contact_name']);
      $activity['custom_' . $activityBiaSource] = get_bloginfo('name');
      $activity['custom_' . $activityBiaId] = $activity['id'];
      unset($activity['id']);
      wpcmrf_api('Activity', 'create', $activity, $options, WPCMRF_ID)->getReply();
    }
  }

  /**
   * Sync a contact to a remote site
   * @param array $contact - APIv3 Contact Record
   * @param array $syncParams - Parameters need to do Sync.
   */
  protected static function syncContact($contact, $syncParams): void {
    $isSynced = \Civi\Api4\Contact::get(TRUE)
      ->addWhere('id','=',$contact['id'])
      ->addSelect('Synced.is_synced')
      ->execute();

    if($isSynced == false) 
    {
      [$biaContactID, $biaSource, $biaRef, $contactCustomFields, $localSocialMediaAPIFields, $biaContactCustomFields, $domainDefaultInformation, $biaRegionField, $activityBiaSource, $activityBiaId, $membershipCustomFields, $remoteSocialMediaAPIFields] = $syncParams;
      $options = $contactAddress = $unitBusinesses = $properties = [];
        // Get the Central BIA contact ID if exists, else create a new contact.
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
      $contact_sub_type = is_array($contact['contact_sub_type']) ? $contact['contact_sub_type'] : CRM_Utils_Array::explodePadded($contact['contact_sub_type']);
      if (in_array('Members_Property_Owners_', $contact_sub_type)) {
        $contactAddress = civicrm_api3('Address', 'get', ['contact_id' => $contact['id'], 'is_primary' => 1, 'sequential' => 1])['values'][0];
        $properties = PropertyOwner::get(FALSE)->addWhere('owner_id', '=', $contact['id'])->execute();
        // lets also look to see if we are a member business.
        if (in_array('Members_Businesses_', $contact_sub_type)) {
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
              $biaUnitBusiness['unit_id'] = wpcmrf_api('Unit', 'get', ['source_record_id' => $business['unit_id'], 'source_record' => get_bloginfo( 'name' )], $options, WPCMRF_ID)->getReply()['id'];
              wpcmrf_api('UnitBusiness', 'create', $biaUnitBusiness, $options, WPCMRF_ID);
            }
          }
          if (!empty($properties)) {
            foreach ($properties as $property) {
              $biaProperty = $property;
              unset($biaProperty['id']);
              $biaProperty['property_id'] = wpcmrf_api('Property', 'get', ['source_record_id' => $property['property_id'], 'source_record' => get_bloginfo( 'name' )], $options, WPCMRF_ID)->getReply()['id'];
              $biaProperty['owner_id'] = $ff['id'];
              $check = wpcmrf_api('PropertyOwner', 'get', ['property_id' => $biaProperty['property_id'], 'owner_id' => $biaProperty['owner_id']], $options, WPCMRF_ID)->getReply();
              wpcmrf_api('PropertyOwner', 'create', $biaProperty, $options, WPCMRF_ID);
            }
          }
        }
      }
      else {
        // Contact is found in Central BIA, Lets work out if we need to update or not.
        $contactParams = self::prepareContactParams($contact, $contactCustomFields, $membershipCustomFields, $localSocialMediaAPIFields, $additionalContactCustomInfo, $biaContactCustomFields, $domainDefaultInformation, $biaContactID, $biaSource, $biaRef, $biaRegionField, $remoteSocialMediaAPIFields);
        $contactParams['id'] = $biaContact['id'];
        if (self::compareContactRemoteRecord($contactParams, $biaContact['id'], $biaContactCustomFields)) {
          wpcmrf_api('Contact', 'create', $contactParams, $options, WPCMRF_ID)->getReply();
        }
        self::syncActivities($contact['id'], $biaContact['id'], $activityBiaSource, $activityBiaId, $options);
        if (!empty($contactAddress)) {
          $biaAddress = wpcmrf_api('Address', 'get', ['contact_id' => $biaContact['id'], 'is_primary' => 1, 'sequential' => 1], $options, WPCMRF_ID)->getReply();
          if (self::compareRemoteAddressRecord($contactAddress, $biaAddress['values'][0])) {
            if (!empty($biaAddress['values'])) {
              $contactAddress['id'] = $biaAddress['id'];
            }
            $contactAddress['contact_id'] = $biaContact['id'];
            wpcmrf_api('Address', 'create', $contactAddress, $options, WPCMRF_ID)->getReply();
          }
        }
        if (!empty($unitBusinesses)) {
          foreach ($unitBusinesses as $business) {
            $biaUnitBusiness = (array) $business;
            $biaUnitBusiness['business_id'] = $biaContact['id'];
            $biaUnitBusiness['unit_id'] = wpcmrf_api('Unit', 'get', ['source_record_id' => $business['unit_id'], 'source_record' => get_bloginfo( 'name' )], $options, WPCMRF_ID)->getReply()['id'];
            $remoteBiaUnitBusiness = wpcmrf_api('UnitBusiness', 'get', ['unit_id' => $biaUnitBusiness['unit_id'], 'business_id' => $biaContact['id'], 'sequential' => 1], $options, WPCMRF_ID)->getReply();
            if (empty($remoteBiaUnitBusiness['values'])) {
              unset($biaUnitBusiness['id']);
              wpcmrf_api('UnitBusiness', 'create', $biaUnitBusiness, $options, WPCMRF_ID);
            }
          }
        }
        if (!empty($properties)) {
          foreach ($properties as $property) {
            $biaProperty = $property;
            unset($biaProperty['id']);
            $biaProperty['property_id'] = wpcmrf_api('Property', 'get', ['source_record_id' => $property['property_id'], 'source_record' => get_bloginfo( 'name' )], $options, WPCMRF_ID)->getReply()['id'];
            $biaProperty['owner_id'] = $biaContact['id'];
            $check = wpcmrf_api('PropertyOwner', 'get', ['property_id' => $biaProperty['property_id'], 'owner_id' => $biaProperty['owner_id'], 'sequential' => 1], $options, WPCMRF_ID)->getReply();
            if (!empty($check['values'])) {
              if ($biaProperty['is_voter'] != $check['values'][0]['is_voter']) {
                $biaProperty['id'] = $check['id'];
                wpcmrf_api('PropertyOwner', 'create', $biaProperty, $options, WPCMRF_ID);
              }
            }
            else {
              wpcmrf_api('PropertyOwner', 'create', $biaProperty, $options, WPCMRF_ID);
            }
          }
        }
      }
      $results = \Civi\Api4\Contact::update(TRUE)
        ->addValue('Synced_.is_synced', 1)
        ->addWhere('id', '=', 1)
        ->execute();
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

  private static function compareContactRemoteRecord($contactParams, $biaContactId, $biaContactCustomFields): bool {
    $options = $differences = [];
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
      ], $options, WPCMRF_ID);
    }
    return (bool) count($differences) > 0;
  }

  private static function compareRemoteAddressRecord(array $localAddressRecord, array $centralAddressRecord): bool {
    $recordsDiffer = FALSE;
    foreach ($localAddressRecord as $fieldName => $value) {
      if ($fieldName !== 'id' && $fieldName !== 'contact_id' && ((!empty($value) && (!isset($centralAddressRecord[$fieldName]) || $value !== $centralAddressRecord[$fieldName])) || (empty($value) && !empty($centralAddressRecord[$fieldName])))) {
        \Civi::log()->debug('BIA Sync: Address fields differ ' . $fieldName, ['local value' => $value, 'remote value' => $centralAddressRecord[$fieldName]]);
        return $recordsDiffer = TRUE;
      }
    }
    return $recordsDiffer;
  }

}
