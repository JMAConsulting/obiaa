<?php

use Civi\Api4\Property;
use Civi\Api4\PropertyOwner;
use Civi\Api4\Unit;
use Civi\Api4\UnitBusiness;

define('WPCMRF_ID', 1);

class CRM_Biasync_Utils {

  public static function syncToBIA() {
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
    $contactCustomFields = [];
    $contactCustomFieldsAPI = civicrm_api3('CustomField', 'get', [
      'sequential' => 1,
      'custom_group_id' => ['IN' => ["Membership_Status", "Ownership_Demographics", "Business_Details"]],
      'options' => ['limit' => 0],
    ]);
    foreach ($contactCustomFieldsAPI['values'] as $apiCustomField) {
      $contactCustomFields['custom_' . $apiCustomField['id']] = $apiCustomField['name'];
    }
    $biaContactCustomFields = [];
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
    $localSocialMediaAPIFields = [];
    foreach ($localSocialMediaFields as $localSocialMediaField) {
      $localSocialMediaAPIFields['custom_' . $localSocialMediaField['id']] = $localSocialMediaField['name'];
    }
    $remoteSocialMediaGroup = wpcmrf_api('CustomGroup', 'get', [
      'name' => 'Social_Media',
    ], $options, WPCMRF_ID)->getReply();
    $remoteSocialMediaFields = wpcmrf_api('CustomField', 'get', [
      'custom_group_id' => $remoteSocialMediaGroup['id'],
    ], $options, WPCMRF_ID)->getReply()['values'];
    $remoteSocialMediaAPIFields = [];
    foreach ($remoteSocialMediaFields as $remoteSocialMediaField) {
      $remoteSocialMediaAPIFields[$remoteSocialMediaField['name']] = 'custom_' . $remoteSocialMediaField['id'];
    }

    foreach ($contacts['values'] as $contact) {
      $options = [];
      // Get the BIA contact ID if exists, else create a new contact.
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
      $contactAddress = $unitBusinesses = $properties = [];
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
        $options = [];
        // No contact exists, proceed to create.
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
          if (!empty($contact[$apiField]) && !empty($remoteSocialMediaAPIFields[$customFieldName])) {
            $contactParams[$remoteSocialMediaAPIFields[$customFieldName]] = 1;
          }
        }
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
        $contactParams = [
          'contact_type' => $contact['contact_type'],
          'contact_sub_type' => $contact['contact_sub_type'],
          'first_name' => !empty($contact['first_name']) ? self::obfuscate_string($contact['first_name']) : '',
          'last_name' => !empty($contact['last_name']) ? self::obfuscate_string($contact['last_name']) : '',
          'organization_name' => !empty($contact['organization_name']) ? self::obfuscate_string($contact['organization_name']) : '',
          'id' => $biaContact['id'],
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
          if (!empty($contact[$apiField]) && !empty($remoteSocialMediaAPIFields[$customFieldName])) {
            $contactParams[$remoteSocialMediaAPIFields[$customFieldName]] = 1;
          }
        }
        wpcmrf_api('Contact', 'create', $contactParams, $options, WPCMRF_ID)->getReply();
        self::syncActivities($contact['id'], $biaContact['id'], $activityBiaSource, $activityBiaId, $options);
        if (!empty($contactAddress)) {
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
            $biaUnitBusiness['unit_id'] = wpcmrf_api('Unit', 'get', ['source_record_id' => $business['unit_id'], 'source_record' => get_bloginfo( 'name' )], $options, WPCMRF_ID)->getReply()['id'];
            $remoteBiaUnitBusiness = wpcmrf_api('UnitBusiness', 'get', ['unit_id' => $biaUnitBusiness['unit_id'], 'business_id' => $biaContact['id']], $options, WPCMRF_ID)->getReply();
            if (!empty($remoteBiaUnitBusiness['values'])) {
              $biaUnitBusiness['id'] = $remoteBiaUnitBusiness['id'];
            }
            else {
              unset($biaUnitBusiness['id']);
            }
            wpcmrf_api('UnitBusiness', 'create', $biaUnitBusiness, $options, WPCMRF_ID);
          }
        }
        if (!empty($properties)) {
          foreach ($properties as $property) {
            $biaProperty = $property;
            unset($biaProperty['id']);
            $biaProperty['property_id'] = wpcmrf_api('Property', 'get', ['source_record_id' => $property['property_id'], 'source_record' => get_bloginfo( 'name' )], $options, WPCMRF_ID)->getReply()['id'];
            unset($biaProperty['id']);
            $biaProperty['owner_id'] = $biaContact['id'];
            $check = wpcmrf_api('PropertyOwner', 'get', ['property_id' => $biaProperty['property_id'], 'owner_id' => $biaProperty['owner_id']], $options, WPCMRF_ID)->getReply();
            if (!empty($check['values'])) {
              $biaProperty['id'] = $check['id'];
            }
            wpcmrf_api('PropertyOwner', 'create', $biaProperty, $options, WPCMRF_ID);
          }
        }
      }
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

  protected static function syncProperties($options) {
    $properties = Property::get()->execute();
    $options = $propertyIds = [];
    foreach ($properties as $property) {
      $propertyArray = (array) $property;
      $propertyCheck = wpcmrf_api('Property', 'get', ['source_record_id' => $property['id'], 'source_record' => get_bloginfo( 'name' )], $options, WPCMRF_ID)->getReply();
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
          unset($unitAddress['id']);
	  unset($unitArray['id']);
	  $unitAddress['contact_id'] = 'Null';
          $remoteUnitAddress = wpcmrf_api('Address', 'create', $unitAddress, $options, WPCMRF_ID)->getReply();
          $unitArray['address_id'] = $remoteUnitAddress['id'];
          $unitArray['source_record_id'] = $unit['id'];
          $unitArray['source_record'] = get_bloginfo( 'name' );
          $unitArray['property_id'] = $prop['id'];
          wpcmrf_api('Unit', 'create', $unitArray, $options, WPCMRF_ID)->getReply();
        }
      }
      else {
        // Property has been found.
        // Update id to be the remote id.
        $propertyArray['id'] = $propertyCheck['id'];
        $prop = wpcmrf_api('Property', 'create', $propertyArray, $options, WPCMRF_ID)->getReply();
        $units = unit::get()->addWhere('property_id', '=', $property['id'])->execute();
        $unitIds = [];
        foreach ($units as $unit) {
          $unitArray = (array) $unit;
          $unitIds[] = $unit['id'];
          $unitAddress = civicrm_api3('Address', 'get', ['id' => $unit['address_id']])['values'][$unit['address_id']];
          $remoteUnit = wpcmrf_api('Unit', 'get', ['source_record_id' => $unit['id'], 'source_record' => get_bloginfo( 'name' ), 'sequential' => 1], $options, WPCMRF_ID)->getReply();
          // If we have a remote unit replace the id field in unitArray and the id of the unitAddress array with the relevant id from the remote unit record.
          $unitAddress['contact_id'] = 'Null';
          if (!empty($remoteUnit['values'])) {
            $unitAddress['id'] = $remoteUnit['values'][0]['address_id'];
            $unitArray['id'] = $remoteUnit['id'];
          }
          else {
            // Otherwise we are going to be creating a unit so unset the id fields. 
            unset($unitAddress['id']);
            unset($unitArray['id']);
          }
          $unitArray['property_id'] = $propertyCheck['id'];
          $remoteAddress = wpcmrf_api('Address', 'create', $unitAddress, $options, WPCMRF_ID)->getReply();
          $unitArray['address_id'] = $remoteAddress['id'];
          $unitArray['source_record_id'] = $unit['id'];
          $unitArray['source_record'] = get_bloginfo( 'name' );
          wpcmrf_api('Unit', 'create', $unitArray, $options, WPCMRF_ID)->getReply();
        }
        $missingUnits = wpcmrf_api('Unit', 'get', ['property_id' => $propertyCheck['id'], 'source_recor_id' => ['NOT IN' => $unitIds], 'source_record' => get_bloginfo('name')], $options, WPCMRF_ID)->getReply();
        foreach ($missingUnits['values'] as $missingUnit) {
          $businesses = wpcmrf_api('UnitBusiness', 'get', ['unit_id' => $missingUnit['id']], $options, WPCMRF_ID)->getReply();
	  foreach ($businesses['values'] as $business) {
            wpcmrf_api('UnitBusiness', 'delete', ['id' => $business['id']], $options, WPCMRF_ID);
          }
          wpcmrf_api('Unit', 'delete', ['id' => $missingUnit['id']], $options, WPCMRF_ID);
        }
      }
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

  protected static function syncActivities($biaSourceContactId, $centralBiaContactId, $activityBiaSource, $activityBiaId, $options) {
    $activities = civicrm_api3('Activity', 'get', [
      'target_contact_id' => $biaSourceContactId,
      'activity_type_id' => ['IN' => [82, 83, 84, 86, 87]],
      'options' => ['limit' => 0],
    ]);
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

}
