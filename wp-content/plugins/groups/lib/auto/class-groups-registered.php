<?php
/**
 * class-groups-registered.php
 *
 * Copyright (c) "kento" Karim Rahimpur www.itthinx.com
 *
 * This code is released under the GNU General Public License.
 * See COPYRIGHT.txt and LICENSE.txt.
 *
 * This code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * This header and all notices must be kept intact.
 *
 * @author Karim Rahimpur
 * @package groups
 * @since groups 1.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * "Registered" group automation.
 */
class Groups_Registered {

	const REGISTERED_GROUP_NAME = 'Registered';

	const BATCH_LIMIT = 100;

	/**
	 * Creates groups for registered users.
	 * Must be called explicitly or hooked into activation.
	 *
	 * As of Groups 2.2.0 this does not trigger the 'groups_created_user_group' action for each entry.
	 */
	public static function activate() {

		global $wpdb;

		// create a group for the blog if it doesn't exist
		if ( !( $group = Groups_Group::read_by_name( self::REGISTERED_GROUP_NAME ) ) ) {
			$group_id = Groups_Group::create( array( 'name' => self::REGISTERED_GROUP_NAME ) );
		} else {
			$group_id = $group->group_id;
		}
		if ( $group_id ) {
			$user_group_table = _groups_get_tablename( 'user_group' );
			$query = $wpdb->prepare(
			// phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
				"INSERT IGNORE INTO $user_group_table " .
				"SELECT ID, %d FROM $wpdb->users",
				Groups_Utility::id( $group_id )
			);
			$rows = $wpdb->query( $query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
		}
	}

	/**
	 * Initialize hooks that handle addition and removal of users and blogs.
	 */
	public static function init() {

		// @since 3.3.1
		add_action( 'init', array( __CLASS__, 'wp_init' ) );

		// When a blog is added, create a new "Registered" group for that blog.
		add_action( 'wpmu_new_blog', array( __CLASS__, 'wpmu_new_blog' ), 10, 2 );

		// Remove group when a blog is deleted? When a blog is deleted,
		// Groups_Controller::delete_blog() takes appropriate action.

		// When a user is added, add it to the "Registered" group.
		add_action( 'user_register', array( __CLASS__, 'user_register' ) );

		// Note : When a user is deleted this is handled from core.

		// When a user is added to a blog, add it to the blog's "Registered" group.
		add_action( 'add_user_to_blog', array( __CLASS__, 'add_user_to_blog' ), 10, 3 );

		// Note : When a user is removed from a blog it's handled from core.
	}

	/**
	 * Hooked on the init action.
	 *
	 * @since 3.3.1
	 */
	public static function wp_init() {
		// For translation of the "Registered" group(s)
		// @since 3.3.1 postponed to after the init action fired
		__( 'Registered', 'groups' );
	}

	/**
	 * Create "Registered" group for new blog and add its admin user.
	 *
	 * @see Groups_Controller::wpmu_new_blog()
	 *
	 * @param int $blog_id
	 * @param int $user_id blog's admin user's id
	 * @param string $domain (optional)
	 * @param string $path (optional)
	 * @param int $site_id (optional)
	 * @param array $meta (optional)
	 */
	public static function wpmu_new_blog( $blog_id, $user_id, $domain = null, $path = null, $site_id = null, $meta = null ) {
		if ( is_multisite() ) {
			Groups_Controller::switch_to_blog( $blog_id );
		}
		if ( !( $group = Groups_Group::read_by_name( self::REGISTERED_GROUP_NAME ) ) ) {
			$group_id = Groups_Group::create( array( 'name' => self::REGISTERED_GROUP_NAME ) );
		} else {
			$group_id = $group->group_id;
		}
		// add the blog's admin user to the group
		if ( $group_id ) {
			// Do NOT use Groups_User::user_is_member( ... ) here as we do not allow the result of this to be filtered:
			if ( !Groups_User_Group::read( $user_id, $group_id ) ) {
				Groups_User_Group::create( array( 'user_id' => $user_id, 'group_id' => $group_id ) );
			}
		}
		if ( is_multisite() ) {
			Groups_Controller::restore_current_blog();
		}
	}

	/**
	 * Assign a newly created user to its "Registered" group.
	 *
	 * @param int $user_id
	 */
	public static function user_register( $user_id ) {

		$registered_group = Groups_Group::read_by_name( self::REGISTERED_GROUP_NAME );
		if ( !$registered_group ) {
			$registered_group_id = Groups_Group::create( array( 'name' => self::REGISTERED_GROUP_NAME ) );
		} else {
			$registered_group_id = $registered_group->group_id;
		}
		if ( $registered_group_id ) {
			// Multisite: If a new user registers with the main blog,
			// the user is added, but it doesn't appear on the Users admin
			// screen of the main blog. It doesn't have the Subscriber role
			// (or any other) for that blog, unless it is explicitly added by the
			// blog's admin to the site. In other words, a user that has just
			// registered with the site's main blog can access the profile page
			// on the back end, but doesn't appear as a user to the site's admin.
			// Currently, on WP 3.3.2, like it or not, it's like that.
			// Unless the user actually has a capability (role)
			// for a blog, it won't appear in the blog's users list. After
			// registering with the blog, the user does not have a capability.
			// Thus, we need to check that is_user_member_of_blog( $user_id ) here.
			if ( !is_multisite() || is_user_member_of_blog( $user_id ) ) {
				Groups_User_Group::create(
					array(
						'user_id'  => $user_id,
						'group_id' => $registered_group_id
					)
				);
			}
		}
	}

	/**
	 * Assign a user to its "Registered" group for the given blog.
	 *
	 * @param int    $user_id User ID.
	 * @param string $role    User role.
	 * @param int    $blog_id Blog ID.
	 */
	public static function add_user_to_blog( $user_id, $role, $blog_id ) {

		if ( is_multisite() ) {
			Groups_Controller::switch_to_blog( $blog_id );
		}

		global $wpdb;

		// Check if the group table exists, if it does not exist, we are
		// probably here because the action has been triggered in the middle
		// of wpmu_create_blog() before the wpmu_new_blog action has been
		// triggered. In that case, just skip this as the user will be added
		// later when wpmu_new_blog is triggered, the activation sequence has
		// created the tables and all users of the new blog are added to
		// that blog's "Registered" group.
		$group_table = _groups_get_tablename( 'group' );
		if ( $wpdb->get_var( "SHOW TABLES LIKE '" . $group_table . "'" ) == $group_table ) { // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$registered_group = Groups_Group::read_by_name( self::REGISTERED_GROUP_NAME );
			if ( !$registered_group ) {
				$registered_group_id = Groups_Group::create( array( 'name' => self::REGISTERED_GROUP_NAME ) );
			} else {
				$registered_group_id = $registered_group->group_id;
			}
			if ( $registered_group_id ) {
				Groups_User_Group::create(
					array(
						'user_id'  => $user_id,
						'group_id' => $registered_group_id
					)
				);
			}
		}

		if ( is_multisite() ) {
			Groups_Controller::restore_current_blog();
		}
	}

}
Groups_Registered::init();
