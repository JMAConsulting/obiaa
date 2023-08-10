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
 *
 * hide update notifications
 *
 */
function hide_update_notices() {
  $user = wp_get_current_user();
  if (!($user->roles[0] == 'administrator')) {
    remove_all_actions('admin_notices');
  }
}

add_action('admin_head', 'hide_update_notices', 1);
