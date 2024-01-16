<?php

use Civi\Api4\Activity;
use Civi\Api4\Contact;
use Civi\Api4\Property;
use Civi\Api4\PropertyLog;
use Civi\Api4\PropertyOwner;
use Civi\Api4\Unit;
use Civi\Api4\UnitBusiness;

// Which CiviMcrestFace profile should be used when performing the sync
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
    $properties = Property::get(TRUE)
      ->addJoin('PropertyLog AS property_log', 'LEFT', ['id', '=', 'property_log.property_id'])
      ->addClause('OR', ['property_log.is_synced', '=', FALSE], ['property_log.property_id', 'IS NULL'])
      ->execute();

    $options = $propertyIds = [];
    foreach ($properties as $property) {
      $propertyArray = (array) $property;
      $propertyIds[] = $property['id'];
      // Set params for Property creation/update
      $propertyArray['source_record'] = get_bloginfo('name');
      $propertyArray['source_record_id'] = $property['id'];
      unset($propertyArray['id']);
      // Update/create a property on the central site
      $propertyCheck = wpcmrf_api('Biasync', 'create', ['entity' => 'Property', 'params'=> $propertyArray], $options, WPCMRF_ID)->getReply();

      // If a property was created by Biasync
      if ($propertyCheck['values'][0]['new_entity_created'] == 1) {
        $units = Unit::get()->addWhere('property_id', '=', $property['id'])->execute();
        foreach ($units as $unit) {
          $unitArray = (array) $unit;
          unset($unit['id']);
          $unitAddress = civicrm_api3('Address', 'get', ['id' => $unit['address_id']])['values'][$unit['address_id']];
          $unitArray['source_record_id'] = $unit['id'];
          $unitArray['unitAddress'] = $unitAddress;
          $unitArray['source_record'] = get_bloginfo( 'name' );
          $unitArray['property_id'] = $propertyCheck['values'][0]['entity_id'];
          $unitArray['unitArray'] = $unitArray;
          wpcmrf_api('Biasync', 'create', ['entity' => 'Unit', 'params' => $unitArray], $options, WPCMRF_ID)->getReply();
        }
      }
      \Civi::$statics['biasync']['post_sync_property_update'] = TRUE;
      PropertyLog::update(TRUE)
        ->addWhere('property_id','=', $property['id'])
        ->addValue('is_synced',TRUE)
        ->execute();
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
    $activities = Activity::get(TRUE)
      ->addWhere('target_contact_id', '=', $biaSourceContactId)
      ->addWhere('activity_type_id', 'IN', [82, 83, 84, 86, 87])
      ->addWhere('Is_Synced_Activities.is_synced', '=', 0)
      ->execute();

    foreach ($activities['values'] as $activity) {
      $activity['target_contact_id'] = $centralBiaContactId;
      $activity['source_contact_id'] = 'user_contact_id';
      $activity['custom_' . $activityBiaId] = $activity['id'];
      $activity['custom_' . $activityBiaSource] = get_bloginfo( 'name' );
      unset($activity['id']);
      unset($activity['source_contact_name']);
      // Update/create an Activity on the central site
      wpcmrf_api('Biasync', 'create', ['entity' => 'Activity', 'params' => $activity], $options, WPCMRF_ID)->getReply();
      \Civi::$statics['biasync']['post_sync_activity_update'] = TRUE;
      Activity::update(TRUE)
        ->addWhere('id', '=', $activity['id'])
        ->addValue('Is_Synced_Activities.is_synced', 1)
        ->execute();
    }
  }

  /**
   * Sync a contact to a remote site
   * @param array $contact - APIv3 Contact Record
   * @param array $syncParams - Parameters need to do Sync.
   */
  protected static function syncContact($contact, $syncParams): void {
    $needsSync = Contact::get(TRUE)
      ->addWhere('id','=',$contact['id'])
      ->addWhere('Is_Synced_Contacts.is_synced', '=', 0)
      ->execute();

    if (!empty($needsSync)) {
      [$biaContactID, $biaSource, $biaRef, $contactCustomFields, $localSocialMediaAPIFields, $biaContactCustomFields, $domainDefaultInformation, $biaRegionField, $activityBiaSource, $activityBiaId, $membershipCustomFields, $remoteSocialMediaAPIFields] = $syncParams;
      $additionalContactCustomInfo = civicrm_api3('Contact', 'get', [
        'id' => $contact['id'],
        'return' => array_merge(array_keys($contactCustomFields), array_keys($localSocialMediaAPIFields)),
      ])['values'][$contact['id']];
      $options = $contactAddress = $unitBusinesses = $properties = [];
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
      $contactParams = self::prepareContactParams($contact, $contactCustomFields, $membershipCustomFields, $localSocialMediaAPIFields, $additionalContactCustomInfo, $biaContactCustomFields, $domainDefaultInformation, $biaContactID, $biaSource, $biaRef, $biaRegionField, $remoteSocialMediaAPIFields);
      $ff = wpcmrf_api('Biasync', 'create', ['entity' => 'Contact', 'params' => ['contactParams' => $contactParams, 'biaContactCustomFields' => $biaContactCustomFields]], $options, WPCMRF_ID)->getReply();
      $remoteContactId = $ff['values'][0]['entity_id'];
      self::syncActivities($contact['id'], $remoteContactId, $activityBiaSource, $activityBiaId, $options);
      if (!empty($contactAddress)) {
        unset($contactAddress['id']);
        $contactAddress['contact_id'] = $remoteContactId;
        wpcmrf_api('biasync', 'create', ['entity' => 'Address', 'params' => $contactAddress], $options, WPCMRF_ID)->getReply();
      }
      if (!empty($unitBusinesses)) {
        foreach ($unitBusinesses as $business) {
          $biaUnitBusiness = (array) $business;
          unset($biaUnitBusiness['id']);
          $biaUnitBusiness['business_source_record_id'] = $biaUnitBusiness['business_id'];
          unset($biaUnitBusiness['business_id']);
          $biaUnitBusiness['unit_source_record_id'] = $biaUnitBusiness['unit_id'];
          unset($biaUnitBusiness['unit_id']);
          $biaUnitBusiness['unit_source_record'] = $biaUnitBusiness['business_source_record'] = get_bloginfo( 'name' );
          wpcmrf_api('biasync', 'create', ['entity' => 'UnitBusines', 'params' => $biaUnitBusiness], $options, WPCMRF_ID);
        }
      }
      if (!empty($properties)) {
        foreach ($properties as $property) {
          $biaProperty = $property;
          unset($biaProperty['id']);
          $biaProperty['property_source_record_id'] = $property['property_id'];
          $biaProperty['property_source_record'] = get_bloginfo( 'name' );
          $biaProperty['owner_id'] = $remoteContactId;
          wpcmrf_api('biasync', 'create', ['entity' => 'PropertyOwner', 'params' => $biaProperty], $options, WPCMRF_ID);
        }
      }
      \Civi::$statics['biasync']['post_sync_contact_update'] = TRUE;
      Contact::update(TRUE)
        ->addValue('Is_Synced_Contacts.is_synced', 1)
        ->addWhere('id', '=', $contact['id'])
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

}
