<?php
/*
	Plugin Name: Obiaa Admin Role
	Plugin URI: https://jmaconsulting.biz
	Description: Create Obiaa Admin role to ensure that Obiaa Admins don't have access to WP Admin
	Version: 1.0
	Author: JMA Consulting
	Author URI: https://jmaconsulting.biz
  Text Domain: obiaa-admin-role
	License: GPL2

	Copyright 2024-07024  JMA Consulting  (email : support@jmaconsulting.biz)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function obiaa_admin_role() {
  $admin_capabilities = [
    'access_ajax_api' => 1,
    'access_all_custom_data' => 1,
    'access_civicontribute' => 1,
    'access_civicrm' => 1,
    'access_civievent' => 1,
    'access_civimail' => 1,
    'access_civimail_subscribe_unsubscribe_pages' => 1,
    'access_civimember' => 1,
    'access_civireport' => 1,
    'access_contact_dashboard' => 1,
    'access_contact_reference_fields' => 1,
    'access_deleted_contacts' => 1,
    'access_report_criteria' => 1,
    'access_uploaded_files' => 1,
    'activate_plugins' => 1,
    'add_contact_notes' => 1,
    'add_contacts' => 1,
    'administer_civicrm' => 1,
    'administer_civicrm_data' => 1,
    'administer_civicrm_system' => 1,
    'administer_tagsets' => 1,
    'close_own_manual_batches' => 1,
    'create_manual_batch' => 1,
    'create_posts' => 1,
    'create_users' => 1,
    'delete_activities' => 1,
    'delete_contacts' => 1,
    'delete_in_civicase' => 1,
    'delete_in_civicontribute' => 1,
    'delete_in_civievent' => 1,
    'delete_in_civimail' => 1,
    'delete_in_civimember' => 1,
    'delete_others_pages' => 1,
    'delete_others_posts' => 1,
    'delete_own_manual_batches' => 1,
    'delete_pages' => 1,
    'delete_plugins' => 1,
    'delete_posts' => 1,
    'delete_private_pages' => 1,
    'delete_private_posts' => 1,
    'delete_published_pages' => 1,
    'delete_published_posts' => 1,
    'delete_themes' => 1,
    'delete_users' => 1,
    'edit_all_contacts' => 1,
    'edit_all_events' => 1,
    'edit_contributions' => 1,
    'edit_dashboard' => 1,
    'edit_event_participants' => 1,
    'edit_groups' => 1,
    'edit_inbound_email_basic_information' => 1,
    'edit_inbound_email_basic_information_and_content' => 1,
    'edit_memberships' => 1,
    'edit_message_templates' => 1,
    'edit_my_contact' => 1,
    'edit_others_pages' => 1,
    'edit_others_posts' => 1,
    'edit_own_api_keys' => 1,
    'edit_own_manual_batches' => 1,
    'edit_pages' => 1,
    'edit_plugins' => 1,
    'edit_posts' => 1,
    'edit_private_pages' => 1,
    'edit_private_posts' => 1,
    'edit_published_pages' => 1,
    'edit_published_posts' => 1,
    'edit_system_workflow_message_templates' => 1,
    'edit_theme_options' => 1,
    'edit_themes' => 1,
    'edit_user_driven_message_templates' => 1,
    'edit_users' => 1,
    'export' => 1,
    'export_own_manual_batches' => 1,
    'groups_access' => 1,
    'groups_admin_groups' => 1,
    'groups_admin_options' => 1,
    'groups_restrict_access' => 1,
    'import' => 1,
    'import_contacts' => 1,
    'install_languages' => 1,
    'install_plugins' => 1,
    'install_themes' => 1,
    'list_users' => 1,
    'make_online_contributions' => 1,
    'manage_categories' => 1,
    'manage_event_profiles' => 1,
    'manage_links' => 1,
    'manage_options' => 1,
    'manage_payment_pages' => 1,
    'manage_tags' => 1,
    'merge_duplicate_contacts' => 1,
    'moderate_comments' => 1,
    'profile_create' => 1,
    'profile_edit' => 1,
    'profile_listings' => 1,
    'profile_listings_and_forms' => 1,
    'profile_view' => 1,
    'promote_users' => 1,
    'publish_pages' => 1,
    'publish_posts' => 1,
    'read' => 1,
    'read_private_pages' => 1,
    'read_private_posts' => 1,
    'register_for_events' => 1,
    'remove_users' => 1,
    'reopen_own_manual_batches' => 1,
    'resume_plugins' => 1,
    'resume_themes' => 1,
    'save_report_criteria' => 1,
    'see_groups' => 1,
    'see_tags' => 1,
    'send_sms' => 1,
    'sign_civicrm_petition' => 1,
    'switch_themes' => 1,
    'unfiltered_html' => 1,
    'unfiltered_upload' => 1,
    'update_core' => 1,
    'update_plugins' => 1,
    'update_themes' => 1,
    'upload_files' => 1,
    'ure_create_capabilities' => 1,
    'ure_create_roles' => 1,
    'ure_delete_capabilities' => 1,
    'ure_delete_roles' => 1,
    'ure_edit_roles' => 1,
    'ure_manage_options' => 1,
    'ure_reset_roles' => 1,
    'view_all_activities' => 1,
    'view_all_contacts' => 1,
    'view_event_info' => 1,
    'view_event_participants' => 1,
    'view_my_contact' => 1,
    'view_my_invoices' => 1,
    'view_own_manual_batches' => 1,
    'view_public_civimail_content' => 1,
    'view_site_health_checks' => 1,
  ];
  $wp_roles = wp_roles();
  // Add the 'obiaa_admin' role with admin capabilities.
  if (!in_array('obiaa_admin', $wp_roles->roles)) {
    add_role('obiaa_admin', __('Obiaa Admin', 'obiaa-admin-role'), $admin_capabilities);
    $args = [
      'role' => 'administrator',
      'orderby' => 'user_nicename',
      'order' => 'ASC',
    ];
    $users = get_users($args);
    foreach ($users as $user) {
      if (!str_contains($user->user_email, 'jmaconsulting.biz')) {
        /** @var \WP_User $user */
        $user->add_role('obiaa_admin');
        $user->remove_role('administrator');
      }
    }
  }
  $roles_to_hide = [
    'anonymous_user',
    'obiaa_admin',
    'subscriber',
    'author',
    'editor',
    'contributor',
    'bia_staff',
  ];
  $sections_to_hide = [
    'index.php',
    'separator1',
    'edit.php',
    'upload.php',
    'edit.php?post_type=page',
    'edit-comments.php',
    'groups-admin',
    'separator2',
    'themes.php',
    'plugins.php',
    'users.php',
    'tools.php',
    'options-general.php',
    'edit.php?post_type=acf-field-group',
    'separator-last',
 ];
 $sub_sections_to_hide = [
   'CiviCRM__1',
   'CiviCRM__2',
  ];
  $adminizesettings = _mw_adminimize_get_option_value();
  foreach ($roles_to_hide as $role_name) {
    $adminizesettings['mw_adminimize_disabled_menu_' . $role_name . '_items'] = $sections_to_hide;
    $adminizesettings['mw_adminimize_disabled_submenu_' . $role_name . '_items'] = $sub_sections_to_hide;
  }
  wp_cache_delete( 'mw_adminimize' );
  update_option( 'mw_adminimize', $adminizesettings);
  wp_cache_add( 'mw_adminimize', $adminizesettings);
}

add_action('init', 'obiaa_admin_role');

/**
 * Ensure that BIA Staff Role does not have any ability to publish posts / create users or do system admin
 */
function bia_staff_role_permissions() {
  $capabilities_to_remove = [
    'create_posts',
    'create_users',
    'delete_others_pages',
    'delete_others_posts',
    'delete_pages',
    'delete_plugins',
    'delete_posts',
    'delete_private_pages',
    'delete_private_posts',
    'delete_published_pages',
    'delete_published_posts',
    'delete_themes',
    'delete_users',
    'edit_pages',
    'edit_plugins',
    'edit_posts',
    'edit_private_pages',
    'edit_private_posts',
    'edit_published_pages',
    'edit_published_posts',
    'edit_users',
    'install_languages',
    'install_plugins',
    'install_themes',
    'publish_pages',
    'publish_posts',
    'read',
    'read_private_pages',
    'read_private_posts',
    'update_core',
    'update_plugins',
    'update_themes',
    'upload_files',
    'ure_create_capabilities',
    'ure_create_roles',
    'ure_delete_capabilities',
    'ure_delete_roles',
    'ure_edit_roles',
    'ure_manage_options',
    'ure_reset_roles',
    'edit_groups',
  ];
  $role = get_role('bia_staff');
  if ($role) {
    foreach ($capabilities_to_remove as $capability) {
      if ($role->has_cap($capability)) {
        $role->remove_cap($capability);
      }
    }
  }
}

add_action('init', 'bia_staff_role_permissions');
