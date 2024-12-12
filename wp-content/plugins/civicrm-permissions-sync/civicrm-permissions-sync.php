<?php /*
--------------------------------------------------------------------------------
Plugin Name: CiviCRM Permissions Sync
Plugin URI: https://develop.tadpole.cc/plugins/civicrm-permissions-sync
Description: Keeps CiviCRM permissions in sync with WordPress capabilities so that they are exposed to other plugins.
Author: Tadpole Collective
Author URI: https://tadpole.cc
Version: 1.1
License: GPLv2
Text Domain: civicrm-permissions-sync
Domain Path: /languages
--------------------------------------------------------------------------------
Based upon code in https://github.com/civicrm/civicrm-wordpress/blob/master/includes/civicrm.users.php
--------------------------------------------------------------------------------
*/



/*
 * Set the plugin sync mode.
 *
 * This must be set prior to activating the plugin.
 *
 * Possible values are:
 *
 * 'all':    Syncs permissions to both our custom role and the "Groups" plugin.
 * 'role':   Only syncs permissions to our custom role.
 * 'groups': Only syncs permissions to the "Groups" plugin.
 *
 * The existence of our sync role may confuse existing admin users who see it in
 * the WordPress UI and think that it serves a purpose other than allowing other
 * plugins to discover CiviCRM's permissions.
 *
 * An alternative to this is to use the "Groups" plugin and ensure that user
 * capabilities are derived solely from membership of a "Groups" group.
 *
 * @since 1.0
 */
if ( ! defined( 'CIVICRM_PERMISSIONS_SYNC_MODE' ) ) {
	define( 'CIVICRM_PERMISSIONS_SYNC_MODE', 'groups' );
}

// Version.
define( 'CIVICRM_PERMISSIONS_SYNC_VERSION', '1.1' );

// Store reference to this file.
define( 'CIVICRM_PERMISSIONS_SYNC_FILE', __FILE__ );

// Store URL to this plugin's directory.
if ( ! defined( 'CIVICRM_PERMISSIONS_SYNC_URL' ) ) {
	define( 'CIVICRM_PERMISSIONS_SYNC_URL', plugin_dir_url( CIVICRM_PERMISSIONS_SYNC_FILE ) );
}

// Store PATH to this plugin's directory.
if ( ! defined( 'CIVICRM_PERMISSIONS_SYNC_PATH' ) ) {
	define( 'CIVICRM_PERMISSIONS_SYNC_PATH', plugin_dir_path( CIVICRM_PERMISSIONS_SYNC_FILE ) );
}



/**
 * CiviCRM Permissions Sync class.
 *
 * A class for encapsulating plugin functionality.
 *
 * @since 1.0
 */
class CiviCRM_Permissions_Sync {

	/**
	 * Custom role name.
	 *
	 * @since 1.0
	 * @access public
	 * @var str $custom_role_name The name of the custom role.
	 */
	public $custom_role_name = 'civicrm_admin';

	/**
	 * Additional minimum capabilities.
	 *
	 * @since 1.0
	 * @access public
	 * @var array $min_capabilities The array of additional minimum capabilities.
	 */
	public $min_capabilities = array(
		'access_ajax_api' => 1,
		'view_my_invoices' => 1,
	);



	/**
	 * Initialise this object.
	 *
	 * @since 1.0
	 */
	public function __construct() {

		// Init translation.
		$this->translation();

		// Bail if CiviCRM plugin is not present.
		if ( ! function_exists( 'civi_wp' ) ) {
			return;
		}

		// Do upgrade tasks.
		$this->upgrade_tasks();

		// Register hooks.
		$this->register_hooks();

		/**
		 * Broadcast that this plugin is now loaded.
		 *
		 * @since 1.0
		 */
		do_action( 'civicrm_permissions_sync_loaded' );

	}



	/**
	 * Perform upgrade tasks.
	 *
	 * If this plugin is activated after CiviCRM itself is activated, then we
	 * need another way to trigger sync which doesn't rely on CiviCRM's hooks.
	 *
	 * @see $this->register_hooks()
	 *
	 * This method is written as a substitute for registering activation hooks
	 * because, in multisite, a network-activated plugin will not inform all
	 * sites in the network of it's activation.
	 *
	 * @see https://core.trac.wordpress.org/ticket/14170#comment:68
	 *
	 * The 'admin_init' hook is recommended for plugin upgrade tasks, so we can
	 * use that to perform permissions sync each time this plugin is upgraded.
	 *
	 * @since 1.0
	 */
	public function upgrade_tasks() {

		// Get installed plugin version for this site.
		$this->plugin_version = get_option( 'civicrm_permissions_sync_version', 'false' );

		/*
		// If this is a new install.
		if ( $this->plugin_version === 'false' ) {
			// Do something.
		}
		*/

		// If the version has changed.
		if ( $this->plugin_version != CIVICRM_PERMISSIONS_SYNC_VERSION ) {

			// Add minimum CiviCRM capabilities to all roles.
			add_action( 'admin_init', [ $this, 'capabilities_all_roles' ], 100 );

			// Sync late on init.
			add_action( 'admin_init', [ $this, 'capabilities_sync' ], 100 );

		}

		/*
		// For specific upgrades, use something like the following.
		if ( version_compare( CIVICRM_PERMISSIONS_SYNC_VERSION, '1.0.1', '>=' ) ) {
			// Do something.
		}
		*/

		// Store version if there has been a change.
		if ( $this->plugin_version != CIVICRM_PERMISSIONS_SYNC_VERSION ) {
			update_option( 'civicrm_permissions_sync_version', CIVICRM_PERMISSIONS_SYNC_VERSION );
			$this->plugin_version = CIVICRM_PERMISSIONS_SYNC_VERSION;
		}

	}



	/**
	 * Register hooks.
	 *
	 * The first two hooks referenced here are native to the CiviCRM WordPress
	 * plugin itself.
	 *
	 * If this plugin active when CiviCRM itself is activated, then these hooks
	 * provide a neat way of ensuring capabilities are synced at the point when
	 * CiviCRM is activated.
	 *
	 * @see $this->upgrade_tasks()
	 *
	 * @since 1.0
	 */
	public function register_hooks() {

		// Filter minimum CiviCRM capabilities.
		add_filter( 'civicrm_min_capabilities', [ $this, 'capabilities_minimum' ], 20, 1 );

		// Sync when CiviCRM activation action fires.
		add_action( 'civicrm_activation', [ $this, 'capabilities_sync' ], 20 );

		// Sync when a CiviCRM Extension's status changes from uninstalled to enabled.
		add_action( 'civicrm_install', [ $this, 'capabilities_sync' ], 20 );

		// Sync when a CiviCRM Extension's status changes from disabled to enabled.
		add_action( 'civicrm_enable', [ $this, 'capabilities_sync' ], 20 );

		// Sync when a CiviCRM Extension's status changes from enabled to disabled.
		add_action( 'civicrm_disable', [ $this, 'capabilities_sync' ], 20 );

	}



	/**
	 * Load translation.
	 *
	 * @since 1.0
	 */
	public function translation() {

		load_plugin_textdomain(
			'civicrm-permissions-sync', // Unique name.
			false, // Deprecated argument.
			dirname( plugin_basename( __FILE__ ) ) . '/languages/' // Relative path.
		);

	}



	/**
	 * Filter minimum CiviCRM capabilities.
	 *
	 * The standard CiviCRM install misses out a few capabilities which many
	 * installs need to function as expected. They are added here, but may
	 * themselves be filtered by hooking in to `civicrm_min_capabilities` and
	 * `init` with a priority greater than those used by this plugin.
	 *
	 * @since 1.0
	 *
	 * @param array $capabilities The existing minimum capabilities.
	 * @return array $capabilities The modified minimum capabilities.
	 */
	public function capabilities_minimum( $capabilities = array() ) {

		// Add our extra capabilities.
		foreach( $this->min_capabilities AS $capability => $value ) {
			$capabilities[$capability] = 1;
		}

		// --<
		return $capabilities;

	}



	/**
	 * Add minimum CiviCRM capabilities to all roles.
	 *
	 * This method adds a few capabilities which many CiviCRM installs need to
	 * function as expected.
	 *
	 * @since 1.0
	 */
	public function capabilities_all_roles() {

		// Fetch roles object.
		$wp_roles = wp_roles();

		// Add capabilities to all roles if not already added.
		foreach( $wp_roles->role_names AS $role_name => $title ) {
			$role = $wp_roles->get_role( $role_name );
			foreach( $this->min_capabilities AS $capability => $value ) {
				if ( ! $role->has_cap( $capability ) ) {
					$role->add_cap( $capability );
				}
			}
		}

	}



	/**
	 * Sync capabilities to WordPress.
	 *
	 * Most plugins that deal with capabilities discover them by inspecting the
	 * roles in WordPress. There are other places that some plugins also inspect
	 * such as Custom Post Types and plugins such as WooCommerce and bbPress. We
	 * don't need to concern ourselves with these subsequent inspections, since
	 * adding all CiviCRM permissions to a WordPress role is enough to make them
	 * discoverable.
	 *
	 * @since 1.0
	 */
	public function capabilities_sync() {

		// Bail if CiviCRM not initialised.
		if ( ! $this->is_civicrm_initialised() ) {
			return;
		}

		// Get all CiviCRM permissions, excluding disabled components and descriptions.
		$permissions = CRM_Core_Permission::basicPermissions( false, false );

		// Convert to WordPress capabilities.
		$capabilities = array();
		foreach( $permissions AS $permission => $title ) {
			$capabilities[] = CRM_Utils_String::munge( strtolower( $permission ) );
		}

		/**
		 * Allow administrator-level capabilities to be filtered.
		 *
		 * @since 1.0
		 *
		 * @param array $capabilities The complete set of CiviCRM capabilities.
		 * @return array $capabilities The modified set of CiviCRM capabilities.
		 */
		$capabilities = apply_filters( 'civicrm_permissions_sync_caps_admin', $capabilities );

		// Sync permissions to the "Groups" plugin depending on plugin mode.
		if ( in_array( CIVICRM_PERMISSIONS_SYNC_MODE, [ 'groups', 'all' ] ) ) {
			$this->capabilities_sync_to_groups( $capabilities );
		}

		// Sync permissions to our custom role depending on plugin mode.
		if ( in_array( CIVICRM_PERMISSIONS_SYNC_MODE, [ 'role', 'all' ] ) ) {
			$this->capabilities_sync_to_role( $capabilities );
		}

		// Clean up.
		$this->capabilities_delete_missing( $capabilities );

	}



	/**
	 * Sync capabilities to "Groups" plugin if present.
	 *
	 * @since 1.0
	 *
	 * @param array $capabilities The complete set of CiviCRM capabilities.
	 */
	public function capabilities_sync_to_groups( $capabilities ) {

		// Bail if we don't have the "Groups" plugin.
		if ( ! defined( 'GROUPS_CORE_VERSION' ) ) {
			return;
		}

		// Add the capabilities if not already added.
		foreach( $capabilities as $capability ) {
			if ( ! Groups_Capability::read_by_capability( $capability ) ) {
				Groups_Capability::create( array( 'capability' => $capability ) );
			}
		}

	}



	/**
	 * Sync capabilities to a custom role.
	 *
	 * @since 1.0
	 *
	 * @param array $capabilities The complete set of CiviCRM capabilities.
	 */
	public function capabilities_sync_to_role( $capabilities ) {

		// Get the role to apply all CiviCRM permissions to.
		$custom_role = $this->role_get();

		// Bail if something went wrong.
		if ( empty( $custom_role ) ) {
			return;
		}

		// Add the capabilities if not already added.
		foreach( $capabilities as $capability ) {
			if ( ! $custom_role->has_cap( $capability ) ) {
				$custom_role->add_cap( $capability );
			}
		}

	}



	/**
	 * Delete CiviCRM capabilities when they no longer exist.
	 *
	 * This can happen when an Extension which had previously added permissions
	 * is disabled or uninstalled, for example.
	 *
	 * @since 1.0
	 *
	 * @param array $capabilities The complete set of CiviCRM capabilities.
	 */
	public function capabilities_delete_missing( $capabilities ) {

		// Read the stored CiviCRM permissions array.
		$stored = $this->permissions_get();

		// Save and bail if we don't have any stored.
		if ( empty( $stored ) ) {
			$this->permissions_set( $capabilities );
			return;
		}

		// Find the capabilities that are missing in the current CiviCRM data.
		$not_in_current = array_diff( $stored, $capabilities );

		// Delete them from "Groups" depending on plugin mode.
		if ( in_array( CIVICRM_PERMISSIONS_SYNC_MODE, [ 'groups', 'all' ] ) ) {
			//$this->capabilities_delete_from_groups( $not_in_current );
		}

		// Delete them from the custom role depending on plugin mode.
		if ( in_array( CIVICRM_PERMISSIONS_SYNC_MODE, [ 'role', 'all' ] ) ) {
			//$this->capabilities_delete_from_role( $not_in_current );
		}

		// Overwrite the current permissions array.
		$this->permissions_set( $capabilities );

	}



	/**
	 * Delete capabilities from "Groups" plugin if present.
	 *
	 * @since 1.0
	 *
	 * @param array $capabilities The array of capabilities to delete.
	 */
	public function capabilities_delete_from_groups( $capabilities ) {

		// Bail if we don't have the "Groups" plugin.
		if ( ! defined( 'GROUPS_CORE_VERSION' ) ) {
			return;
		}

		// Delete the capabilities if not already deleted.
		foreach( $capabilities as $capability ) {
			$groups_cap = Groups_Capability::read_by_capability( $capability );
			if ( ! empty( $groups_cap->capability_id ) ) {
				Groups_Capability::delete( $groups_cap->capability_id );
			}
		}

	}



	/**
	 * Delete capabilities from a custom role.
	 *
	 * @since 1.0
	 *
	 * @param array $capabilities The array of capabilities to delete.
	 */
	public function capabilities_delete_from_role( $capabilities ) {

		// Get the role to delete CiviCRM permissions from.
		$custom_role = $this->role_get();

		// Bail if something went wrong.
		if ( empty( $custom_role ) ) {
			return;
		}

		// Delete the capabilities if not already deleted.
		foreach( $capabilities as $capability ) {
			if ( $custom_role->has_cap( $capability ) ) {
				$custom_role->remove_cap( $capability );
			}
		}

	}



	/**
	 * Retrieve our custom WordPress role.
	 *
	 * We need a role to which we add all CiviCRM permissions. This makes the
	 * capabilities discoverable by other plugins. This method creates the role
	 * if it doesn't already exist by cloning the 'adminstrator' role.
	 *
	 * @since 1.0
	 *
	 * @return WP_Role|void $custom_role The custom role, or void on failure.
	 */
	public function role_get() {

		// Fetch roles object.
		$wp_roles = wp_roles();

		// If the custom role already exists.
		if ( $wp_roles->is_role( $this->custom_role_name ) ) {

			// Get existing role.
			$custom_role = $wp_roles->get_role( $this->custom_role_name );

		} else {

			// Bail if the 'administrator' role is not there for some reason.
			if ( ! $wp_roles->is_role( 'administrator' ) ) {
				return;
			}

			// Grab the 'administrator' role.
			$admin = $wp_roles->get_role( 'administrator' );

			// Add new role.
			$custom_role = add_role(
				$this->custom_role_name,
				__( 'CiviCRM Administrator', 'civicrm-permissions-sync' ),
				$admin->capabilities
			);

		}

		// If void then log something.
		if ( empty( $custom_role ) ) {

			// Construct a message.
			$message = sprintf(
				__( 'Could not find CiviCRM sync role: "%s"', 'civicrm-permissions-sync' ),
				$this->custom_role_name
			);

			// Add log entry.
			$e = new Exception;
			$trace = $e->getTraceAsString();
			error_log( print_r( array(
				'method' => __METHOD__,
				'message' => $message,
				'backtrace' => $trace,
			), true ) );

		}

		// --<
		return $custom_role;

	}



	/**
	 * Get stored CiviCRM permissions.
	 *
	 * @since 1.0
	 *
	 * @return array $permissions The array of stored permissions.
	 */
	public function permissions_get() {

		// Get from option.
		$permissions = get_option( 'civicrm_permissions_sync_perms', 'false' );

		// If no option exists, cast return as array.
		if ( $permissions === 'false' ) {
			$permissions = array();
		}

		// --<
		return $permissions;

	}



	/**
	 * Set stored CiviCRM permissions.
	 *
	 * @since 1.0
	 *
	 * @param array $permissions The array of permissions to store.
	 */
	public function permissions_set( $permissions ) {

		// Set the option.
		update_option( 'civicrm_permissions_sync_perms', $permissions );

	}



	/**
	 * Check if CiviCRM is initialised.
	 *
	 * @since 1.0
	 *
	 * @return bool True if CiviCRM initialised, false otherwise.
	 */
	public function is_civicrm_initialised() {

		// Init only when CiviCRM is fully installed.
		if ( ! defined( 'CIVICRM_INSTALLED' ) ) return false;
		if ( ! CIVICRM_INSTALLED ) return false;

		// Bail if no CiviCRM init function.
		if ( ! function_exists( 'civi_wp' ) ) return false;

		// Try and initialise CiviCRM.
		return civi_wp()->initialize();

	}



} // Class ends.



/**
 * Get a reference to this plugin.
 *
 * @since 1.0
 *
 * @return CiviCRM_Permissions_Sync $civicrm_permissions_sync The plugin reference.
 */
function civicrm_permissions_sync() {

	// Hold the plugin instance in a static variable.
	static $civicrm_permissions_sync = false;

	// Instantiate plugin if not yet instantiated.
	if ( false === $civicrm_permissions_sync ) {
		$civicrm_permissions_sync = new CiviCRM_Permissions_Sync();
	}

	// --<
	return $civicrm_permissions_sync;

}

// Init plugin.
add_action( 'plugins_loaded', 'civicrm_permissions_sync' );



