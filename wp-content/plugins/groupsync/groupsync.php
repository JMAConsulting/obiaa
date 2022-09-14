<?php

/*
Plugin Name: Group sync
Plugin URI: https://jmaconsulting.biz
Description: Plugin that adds customisation for CiviCRM Groups Sync.
Version: 1.0
Author: Monish Deb
License: GPLv2 or later
*/

/**
 * Groups Sync Class.
 *
 * A class that encapsulates plugin functionality.
 *
 * @since 0.1
 */
class Groups_Sync {

  /**
   * Constructor.
   *
   * @since 0.1
   */
  public function __construct() {
    // Initialise.
    add_action( 'plugins_loaded', array( $this, 'initialise' ) );
  }

  /**
   * Do stuff on plugin init.
   *
   * @since 0.1
   */
  public function initialise() {
    // Only do this once.
    static $done;
    if ( isset( $done ) AND $done === true ) {
      return;
    }

    // Finally, register hooks.
    $this->register_hooks();

    $done = true;
  }

  /**
   * Register hooks.
   *
   * @since 0.1
   */
  public function register_hooks() {
    add_action( 'civicrm_pre', array( $this, 'add_missing_wp_user_by_contact_id' ), 9, 4 );
    add_action( 'groups_created_user_group', array( $this, 'add_missing_civi_contact_by_user_id' ), 9, 2);
  }

  /**
   * Check if CiviCRM is initialised.
   *
   * @since 0.1
   *
   * @return bool True if CiviCRM initialised, false otherwise.
   */
  public function is_civicrm_initialised() {
    // Bail if no CiviCRM init function.
    if ( ! function_exists( 'civi_wp' ) ) return false;

    // Try and initialise CiviCRM.
    return civi_wp()->initialize();
  }

  /**
   * Get the "Groups" group ID using a CiviCRM group ID.
   *
   * @since 0.1
   *
   * @param int $user_id The numeric ID of the WP user.
   * @return int $group_id The ID of the "Groups" group, or false on failure.
   */
  public function add_missing_civi_contact_by_user_id( $user_id, $group_id ) {
    // Bail if no CiviCRM.
    if ( ! $this->is_civicrm_initialised() ) {
      return false;
    }

    // Make sure CiviCRM file is included.
    require_once( 'CRM/Core/BAO/UFMatch.php' );

    // Search using CiviCRM's logic.
    $contact_id = CRM_Core_BAO_UFMatch::getContactId( $user_id );

    if (empty($contact_id)) {
      if (!$user = get_userdata($user_id)) return false;

      $userInfo = new WP_User( $user_id );
      $params = [
        'contact_type' => 'Individual',
        'first_name' => $userInfo->first_name,
        'last_name' => $userInfo->last_name,
        'email' => $user->data->user_email,
      ];

      // find duplicate contact based on Unsupervised rule
      $dedupe_params = CRM_Dedupe_Finder::formatParams($params, $params['contact_type']);
      $dedupe_params['check_permission'] = false;
      $ids = CRM_Dedupe_Finder::dupesByParams($dedupe_params, $params['contact_type'], 'Unsupervised');
      if ($ids) {
        $params['id'] = $ids['0'];
      }

      $contact_id = civicrm_api3('Contact', 'create', $params)['id'];

      $ufMatch = [
        'uf_id' => $user_id,
        'contact_id' => $contact_id,
        'uf_name' => $params['email'],
      ];
      CRM_Core_BAO_UFMatch::create($ufMatch);
    }
  }

  /**
   * Add a missing WP user which is not linked to CiviCRM contact
   *
   * @since 0.1
   *
   * @param string $op The type of database operation.
   * @param string $object_name The type of object.
   * @param integer $civicrm_group_id The ID of the CiviCRM group.
   * @param array $contact_ids The array of CiviCRM Contact IDs.
   */
  public function add_missing_wp_user_by_contact_id( $op, $object_name, $civicrm_group_id, $contact_ids ) {
    // Target our operation.
    if ( $op != 'create' ) return;

    // Target our object type.
    if ( $object_name != 'GroupContact' ) return;

    // Get "Groups" group ID.
    $wp_group_id = $this->group_get_wp_id_by_contact_id( $civicrm_group_id );

    // Sanity check.
    if ( $wp_group_id === false ) {
      return;
    }

    // Bail if no CiviCRM.
    if ( ! $this->is_civicrm_initialised() ) {
      return false;
    }

    // Make sure CiviCRM file is included.
    require_once( 'CRM/Core/BAO/UFMatch.php' );

    // Loop through added contacts.
    if ( count( $contact_ids ) > 0 ) {
      foreach( $contact_ids as $contact_id ) {
        // Search using CiviCRM's logic.
        $user_id = CRM_Core_BAO_UFMatch::getUFId( $contact_id );

        // Cast user ID as boolean if we didn't get one.
        if ( empty( $user_id ) ) {
          $contact = civicrm_api3('Contact', 'getsingle', ['id' => $contact_id]);

          // if there is no primarry email address then skip creating WP user
          if (empty($contact['email'])) {
            return;
          }
	  $wpUser = get_user_by('email', $contact['email']);
	  if (empty($wpUser->ID)) {
          $username = $this->generateUserName($contact);
          $user_data = [
            'ID' => '',
            'user_pass' => $this->randomPassword(),
            'user_login' => $username,
            'user_email' => strtolower($contact['email']),
            'first_name' => $contact['first_name'],
            'last_name' => $contact['last_name'],
            'nickname' => $username,
            'role' =>  get_option('default_role'),
          ];
	  $user_id = wp_insert_user($user_data);
	  wp_new_user_notification($user_id, '', 'user');
	  }
	  else {
            $user_id = $wpUser->ID;
	  }
          $ufMatch = [
            'uf_id' => $user_id,
            'contact_id' => $contact_id,
            'uf_name' => $user_data['user_email'],
          ];
          CRM_Core_BAO_UFMatch::create($ufMatch);
        }
      }
    }
  }

  /**
   * Generate a safe WordPress user name for use
   * @param array $params
   */
  public function generateUserName($params) {
    // Check to see if a the user name exists.
    $username = strtolower(trim(sanitize_user(implode('.', [$params['first_name'], $params['last_name']]))));
    $existingUsers = get_users( array( 'search' => $username ) );
    if (!empty($existingUsers)) {
      $userCount = count($existingUsers) + 1;
      return $username . $userCount;
    }
    return $username;
  }

  /**
   * Generate a password for use
   */
  public function randomPassword() {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
    $length = rand(10, 16);
    $password = substr( str_shuffle(sha1(rand() . time()) . $chars ), 0, $length );
    return $password;
  }

  /**
   * Get the "Groups" group ID using a CiviCRM group ID.
   *
   * @since 0.1
   *
   * @param int $group_id The numeric ID of the CiviCRM group.
   * @return int|bool $wp_group_id The ID of the "Groups" group, or false on failure.
   */
  public function group_get_wp_id_by_contact_id( $group_id ) {
    // Get the synced CiviCRM group.
    $civicrm_group = civicrm_api( 'Group', 'getsingle', array(
      'version' => 3,
      'id' => $group_id,
    ));

    // Bail on failure.
    if ( isset( $civicrm_group['is_error'] ) AND $civicrm_group['is_error'] == '1' ) {
      return false;
    }

    // Bail if there's no "source" field.
    if ( empty( $civicrm_group['source'] ) ) {
      return false;
    }

    // Get ID from source string.
    $tmp = explode( 'synced-group-', $civicrm_group['source'] );
    $wp_group_id = isset( $tmp[1] ) ? absint( trim( $tmp[1] ) ) : false;

    // Return the ID of the "Groups" group.
    return $wp_group_id;
  }

}


function groups_sync() {
  // Store instance in static variable.
  static $groups_sync = false;

  // Maybe return instance.
  if ( false === $groups_sync ) {
    $groups_sync = new Groups_Sync();
  }

  // --<
  return $groups_sync;
}

groups_sync();
