<?php
/**
 *
 */

if (!defined('ABSPATH')) {
  // Exit if accessed directly.
  exit;
}


require_once 'lib/enqueue-assets.php';
require_once 'lib/theme-support.php';
require_once 'lib/customize.php';

/**
 * hide update notifications for non-admins
 */
function remove_core_updates() {	
	global $wp_version;
	return (object) array('last_checked' => time(), 'version_checked' => $wp_version);
}

$user = wp_get_current_user();
if (!empty($user->roles) && $user->roles[0] != 'administrator') {
	//hide updates for WordPress itself
	add_filter('pre_site_transient_update_core', 'remove_core_updates');
	//hide updates for all plugins
	add_filter('pre_site_transient_update_plugins', 'remove_core_updates');
	//hide updates for all themes
	add_filter('pre_site_transient_update_themes', 'remove_core_updates');
}