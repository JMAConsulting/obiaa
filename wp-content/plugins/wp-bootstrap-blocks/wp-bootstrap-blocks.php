<?php
/**
 * Plugin Name: Bootstrap Blocks
 * Plugin URI: https://github.com/tschortsch/bootstrap-blocks-wordpress-plugin
 * Description: Bootstrap Gutenberg Blocks for WordPress.
 * Version: 6.0.1
 * Requires at least: 6.8
 * Requires PHP: 7.4
 * Author: Jürg Hunziker
 * Author URI: https://juerghunziker.ch
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-bootstrap-blocks
 * Domain Path: /languages/
 *
 * @package wp-bootstrap-blocks
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define WP_BOOTSTRAP_BLOCKS_PLUGIN_FILE.
if ( ! defined( 'WP_BOOTSTRAP_BLOCKS_PLUGIN_FILE' ) ) {
	define( 'WP_BOOTSTRAP_BLOCKS_PLUGIN_FILE', __FILE__ );
}

// Include the main WP_Bootstrap_Blocks class.
if ( ! class_exists( \WP_Bootstrap_Blocks\WP_Bootstrap_Blocks::class ) ) {
	require_once plugin_dir_path( WP_BOOTSTRAP_BLOCKS_PLUGIN_FILE ) . 'src/class-wp-bootstrap-blocks.php';
}

// Initialize plugin
\WP_Bootstrap_Blocks\WP_Bootstrap_Blocks::instance();
