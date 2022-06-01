<?php
 
/**
 
 * @package ACF OBIAA
 
 */
 
/*
 
Plugin Name: ACF-OBIAA
 
Plugin URI: https://obiaa.com/
 
Description: Plugin to handle customization related to ACFE

Version: 1.0.0
 
Author: Edsel Roque Lopez
 
Author URI: https://jmaconsulting.biz
 
License: GPLv2 or later
 
Text Domain: acfe
 
*/

add_action('acfe/form/submit/add-staff-assignee', 'add_staff_assignee', 10, 2);
function add_staff_assignee($form, $action) {
  // Get the previous action.
  $activityID = acfe_form_get_action('create-activity')['id'] ?? NULL;
  if (!empty($activityID)) {
    // Get the primary contact
    $primaryContact = \Civi\Api4\Contact::get(FALSE)
      ->addSelect('id')
      ->addWhere('Staff_Information.Primary_Staff_Member_', '=', 1)
      ->setLimit(1)
      ->execute()[0]['id'];
    if (empty($primaryContact)) {
      // Fetch the first staff member we find, and mark as primary.
      $primaryContact = \Civi\Api4\Contact::get(FALSE)
        ->addSelect('id')
        ->addWhere('contact_sub_type', '=', 'OBIAA_Staff')
        ->setLimit(1)
        ->execute()[0]['id'];
      \Civi\Api4\Contact::update(FALSE)
        ->addValue('Staff_Information.Primary_Staff_Member_', 1)
        ->addWhere('id', '=', $primaryContact)
        ->execute();
    }
    if (empty($primaryContact)) {
      // If we still don't have a primary contact by now, assign to the default contact ID.
      $primaryContact = \Civi\Api4\Domain::get(FALSE)
        ->addSelect('contact_id')
        ->setLimit(1)
        ->execute()[0]['contact_id'];
    }
    if (!empty($primaryContact)) {
      // We assign this to the activity.
      \Civi\Api4\Activity::update(FALSE)
        ->addValue('assignee_contact_id', [
          $primaryContact,
        ])
        ->addWhere('id', '=', $activityID)
        ->execute();
    }
  }
}


