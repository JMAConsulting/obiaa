<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

/**
 * Class provide ActivityType helper methods
 */
class CRM_CiviMobileAPI_Utils_ActivityType {

  /**
   * Gets Contact's allowed Activity Types for the Contact
   *
   * @param $params
   *
   * @return array
   */
  public static function getContactActivityTypes($params) {
    $activityTypes = [];

    foreach (self::getAllActivityTypes($params) as $activityType) {
      $validActivityType = self::validateActivityType($activityType, $params['contact_id']);
      if (empty($validActivityType)) {
        continue;
      }

      //Disable "Email" and "Print PDF Letter" option because civimobile ver 3 doesn't have that functionality
      //TODO Remove this code, when civimobile implements that functionality
      if ($validActivityType['name'] == 'Email' || $validActivityType['name'] == "Print PDF Letter") {
        continue;
      }

      $activityTypes[] = $validActivityType;
    }

    if (!empty($params['limit'])) {
      $activityTypes = array_slice($activityTypes, 0, (int) $params['limit']);
    }

    return $activityTypes;
  }

  /**
   * Gets all active Activity Types
   *
   * @param $params
   *
   * @return array
   */
  public static function getAllActivityTypes($params) {
    $sortParams = explode(' ', $params['sort']);

    return civicrm_api4('OptionValue', 'get', [
      'where' => [
        ['option_group_id:name', '=', 'activity_type'],
        ['is_active', '=', TRUE],
      ],
      'orderBy' => !empty($sortParams[0]) ? [$sortParams[0] => $sortParams[1]] : ['label' => 'ASC'],
      'checkPermissions' => FALSE,
    ])->getArrayCopy();
  }

  /**
   * Validates and prepares Activity Type
   * (business logic gets from CRM/Activity/Form/ActivityLinks.php)
   *
   * @param $activityType
   * @param $contactId
   *
   * @return array|bool
   */
  private static function validateActivityType($activityType, $contactId) {
    $urlParams = "action=add&reset=1&cid={$contactId}&selectedChild=activity&atype=";
    $url = 'civicrm/activity/add';

    if ($activityType['name'] == 'Email') {
      if (!CRM_Utils_Mail::validOutBoundMail() || !$contactId) {
        return false;
      }
      list($name, $email, $doNotEmail, $onHold, $isDeceased) = CRM_Contact_BAO_Contact::getContactDetails($contactId);
      if (!$doNotEmail && $email && !$isDeceased) {
        $url = 'civicrm/activity/email/add';
        $activityType['label'] = E::ts('Send an Email');
      }
      else {
        return false;
      }
    }
    elseif ($activityType['name'] == 'SMS') {
      if (!$contactId || !CRM_SMS_BAO_Provider::activeProviderCount()) {
        return false;
      }
      $mobileTypeID = CRM_Core_PseudoConstant::getKey('CRM_Core_BAO_Phone', 'phone_type_id', 'Mobile');

      $phoneDetails = civicrm_api4('Phone', 'get', [
        'select' => [
          'contact_id.display_name',
          'phone',
          'contact_id.do_not_sms',
        ],
        'where' => [
          ['contact_id', '=', $contactId],
          ['is_primary', '=', true],
          ['phone_type_id', '=', $mobileTypeID],
        ],
        'checkPermissions' => false,
      ])->first();

      $name = $phoneDetails['contact_id.display_name'];
      $phone = $phoneDetails['phone'];
      $doNotSMS = $phoneDetails['contact_id.do_not_sms'];

      if (!$doNotSMS && $phone) {
        $url = 'civicrm/activity/sms/add';
      }
      else {
        return false;
      }
    }
    elseif ($activityType['name'] == 'Print PDF Letter') {
      $url = 'civicrm/activity/pdf/add';
    }
    elseif (!empty($activityType['filter']) || (!empty($activityType['component_id']) && $activityType['component_id'] != '1')) {
      return false;
    }

    $activityType['url'] = CRM_Utils_System::url($url, "{$urlParams}{$activityType['value']}", FALSE, NULL, FALSE);
    $activityType += ['icon' => 'fa-plus-square-o'];

    return $activityType;
  }

}
