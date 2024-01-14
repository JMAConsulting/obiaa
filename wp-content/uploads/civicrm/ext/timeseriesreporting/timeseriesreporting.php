<?php

require_once 'timeseriesreporting.civix.php';
// phpcs:disable
use CRM_Timeseriesreporting_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function timeseriesreporting_civicrm_config(&$config): void {
  _timeseriesreporting_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function timeseriesreporting_civicrm_install(): void {
  _timeseriesreporting_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function timeseriesreporting_civicrm_enable(): void {
  _timeseriesreporting_civix_civicrm_enable();
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 * @throws CRM_Core_Exception
 */
//function timeseriesreporting_civicrm_preProcess($formName, &$form): void {
//
//}

function convertNestedArrayToString($array, $indentation = '') {
  $output = '';

  foreach ($array as $key => $value) {
    if (is_array($value)) {
      $output .= $indentation . $key . ":\n";
      $output .= convertNestedArrayToString($value, $indentation . '    ');
    } else {
      $output .= $indentation . $key . ': ' . $value . "\n";
    }
  }

  return $output;
}

/*
 * Listen for changes in the custom fields for the Contact record.
 * Store current data for the contact record custom field ot the activity type.
 */
/**
 * @throws CRM_Core_Exception
 * @throws \Civi\Core\Exception\DBQueryException
 * @throws \Civi\API\Exception\UnauthorizedException
 */
function timeseriesreporting_civicrm_customPre(string $op, int $groupId, int $entityId, array &$params): void {
  // get custom group from $groupId to determine whether the entity is a contact
  Civi::log()->debug(convertNestedArrayToString($params));
  $CUSTOM_GROUP_KEY = 'tsr_leg_custom_group_' . $groupId;
  $serializedCustomGroup = wp_cache_get($CUSTOM_GROUP_KEY);
  if ($serializedCustomGroup === false) {
    // not found in cache
    $customGroup = \Civi\Api4\CustomGroup::get(TRUE)
      ->addWhere('id', '=', $groupId)
      ->addClause('OR', ['extends', '=', 'Individual'], ['extends', '=', 'Organization'])
      ->execute()->first();
    // store in cache
    wp_cache_set($CUSTOM_GROUP_KEY, serialize($customGroup), 'tsr_leg_custom_group');
  } else {
    $customGroup = unserialize($serializedCustomGroup);
  }
  if ($op === 'edit' && !is_null($customGroup)) {
    // Retrieve Contact Changed Option Value from cache
    $CONTACT_CHANGED_KEY = 'tsr_contact_changed';
    $CUSTOM_FIELD_KEY = 'tsr_custom_field' . $groupId;
    $serializedContactChanged = wp_cache_get($CUSTOM_GROUP_KEY);
    $serializedCustomFields = wp_cache_get($CUSTOM_FIELD_KEY);
    if ($serializedContactChanged === false) {
      $contactChangedOption = \Civi\Api4\OptionValue::get()
        ->addWhere('option_group_id:name', '=', 'activity_type')
        ->addWhere('name', '=', 'Contact Changed')
        ->execute()->first();
      wp_cache_set($CONTACT_CHANGED_KEY, serialize($contactChangedOption));
    } else {
      $contactChangedOption = unserialize($serializedContactChanged);
    }
    // get the TsrGroupRefs entity that links the original and the tsr custom groups
    $groupRef = \Civi\Api4\TsrGroupRefs::get(TRUE)
      ->addWhere('original_custom_group', '=', $groupId)
      ->execute()->first();
    $copyGroup = \Civi\Api4\CustomGroup::get(TRUE)
      ->addWhere('id', '=', $groupRef['tsr_custom_group'])
      ->addWhere('extends', '=', 'Activity')
      ->execute()->first();
    if ($serializedCustomFields === false) {
      // get all custom fields associated with our custom group to create our activity creation params
      $customFields = \Civi\Api4\CustomField::get()
        ->addJoin('CustomGroup AS custom_group', 'LEFT', ['custom_group_id', '=', 'custom_group.id'])
        ->addWhere('custom_group_id', '=', $groupId)
        ->execute();
      wp_cache_set($CUSTOM_FIELD_KEY, serialize($customFields), 'tsr_custom_fields');
    } else {
      $customFields = unserialize($serializedCustomFields);
    }
    // Activity Creation Params to pass into API
    $activityCreateRecords = ['activity_type_id' => $contactChangedOption['id'],
      'source_contact_id'=> $entityId];
    foreach ($customFields as $customField) {
      // no point in reducing runtime here as matching algorithms with faster asymptotic times may end up bloating
      $val = null;
      foreach ($params as $field) {
        if ($field['custom_field_id'] == $customField['id']) {
          $val = ($field['serialize'] == 1) ? implode(" ", CRM_Utils_Array::explodePadded($field['value'])) : $field['value'];
        }
      }
      $activityCreateRecords[$copyGroup['name'] . '.' . $customField['name']] = $val;
    }
//    Civi::log()->debug(convertNestedArrayToString($activityCreateRecords));
    civicrm_api4('Activity', 'create', [
      'values' => $activityCreateRecords,
    ]);
  }
}

/*
 * Returns a query string acceptable by CRM_Core_DAO given the customPre $params argument
 */
function _convertParamsToQuery(array $params, $customGroup, $entityId): string {
  // Create an array holding all the values
  $copyValues = [];
  $qs = "";
  foreach ($params as $field) {
    // deserialize value as required
    $val = ($field['serialize'] == 1) ? implode(" ", CRM_Utils_Array::explodePadded($field['value'])) : $field['value'];
  }
  // construct sql query string
  if (!empty($copyValues)){
    $columnNames = array_keys($copyValues);
    // surround each value with single quotes
    $columnValues = array_map(function($value) {return "'" . $value . "'";}, $copyValues);
    $qs = "INSERT INTO " . $customGroup['table_name'] . "(entity_id, " . implode(", ", $columnNames) . ") VALUES (" . $entityId . ", ";
    $qs .= implode(", ", $columnValues) . ")";
  }
  return $qs;
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
//function timeseriesreporting_civicrm_navigationMenu(&$menu): void {
//  _timeseriesreporting_civix_insert_navigation_menu($menu, 'Mailings', [
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ]);
//  _timeseriesreporting_civix_navigationMenu($menu);
//}
