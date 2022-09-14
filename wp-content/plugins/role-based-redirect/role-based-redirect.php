<?php
/**
 * Plugin Name: Role Based Redirect
 * Description: Redirect users to specific page after login, based on their the role and also option for hide adminbar and restrict dashboard access.
 * Author: MohammedYasar Khalifa
 * Author URI: https://myasark.wordpress.com/
 * Version: 1.4
 * License: GPLv2
 */
// Exit if accessed directly.
if (!defined('ABSPATH')) exit;
class RBRurls {
    function __construct() {
        add_action('admin_menu', array($this, 'rbrurls_plugin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'load_admin_style'),10,1);
        add_filter('login_redirect', array($this, 'rbrurls_login_redirect'), 10, 3);
        add_action('after_setup_theme', array($this, 'rbrurls_remove_admin_bar'));
        add_action('admin_init', array($this, 'rbrurls_no_admin_access'), 1);
        add_action('wp_logout', array($this, 'rbrurls_logout_redirect'));
    }
    function load_admin_style($hook) { 
        if( $hook == 'toplevel_page_rbrurls-settings' ) {
             wp_register_style('rbrurls_css', plugins_url('/assets/css/custom.css', __FILE__));
                wp_enqueue_style('rbrurls_css');
            }
    }
    function rbrurls_plugin_menu() {
        add_menu_page('Users Redirect', 'Users Redirect', 'administrator', 'rbrurls-settings', array($this, 'rbrurls_settings'), plugins_url('/images/icon.png', __FILE__), 70);
    }
    function rbrurls_settings() {
        if (current_user_can("administrator")) {
            include (plugin_dir_path(__FILE__) . 'includes/redirect.php');
        }
    }
    function rbrurls_login_redirect($redirect_to, $request, $user) {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}rbrurls_redirect");
        if (isset($user->roles) && is_array($user->roles)) {
            //check for subscribers
            foreach ($results as $result) {
                $desirerole = $result->role_type;
                if ($result->url_login_dropdown) {
                    $desireurl = $result->url_login_dropdown;
                } else {
                    $desireurl = $result->url_login_textbox;
                }
                if (in_array($desirerole, $user->roles)) {
                    $redirect_to = $desireurl;
                }
            }
        }
        return $redirect_to;
    }
    function rbrurls_logout_redirect() {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}rbrurls_redirect");
        foreach ($results as $result) {
            $desirerole = $result->role_type;
            if ($result->url_logout_dropdown) {
                $desireurl = $result->url_logout_dropdown;
            } else {
                $desireurl = $result->url_logout_textbox;
            }
            if ($desireurl) {
                if (current_user_can($desirerole)) {
                    $redirect_url = $desireurl;
                    wp_safe_redirect($redirect_url);
                    exit;
                }
            } else {
                wp_safe_redirect(wp_login_url());
                exit;
            }
        }
    }
    function rbrurls_remove_admin_bar() {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}rbrurls_redirect");
        foreach ($results as $result) {
            if ($result->adminbar == 'yes') {
                if (current_user_can($result->role_type)) {
                    add_filter( 'show_admin_bar', '__return_false', PHP_INT_MAX );
                }
            }
        }
    }
    function rbrurls_no_admin_access() {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}rbrurls_redirect");
        foreach ($results as $result) {
            if ($result->restrict_dashboard == 'yes') {
                if (current_user_can($result->role_type)) {
                    wp_redirect(home_url());
                    die();
                }
            }
        }
    }
}
$RBRurls = new RBRurls();
register_activation_hook(__FILE__, 'rbrurls_install');
function rbrurls_install() {
    global $wpdb;
    global $table_name;
    $table_name = $wpdb->prefix . "rbrurls_redirect";
    if ($table_name != $wpdb->get_var("SHOW TABLES LIKE '$table_name'")) {
        $sql = "CREATE TABLE $table_name (
            `role_id` INT(20) NOT NULL AUTO_INCREMENT,
            `role_type` VARCHAR(20) NOT NULL,
            `url_login_dropdown` VARCHAR(255) NOT NULL,
            `url_login_textbox` VARCHAR(255) NOT NULL,
            `url_logout_dropdown` VARCHAR(255) NOT NULL,
			`url_logout_textbox` VARCHAR(255) NOT NULL,
			`adminbar` VARCHAR(10) NOT NULL,
			`restrict_dashboard` VARCHAR(10) NOT NULL,
            PRIMARY KEY (`role_id`)
            )";
        //$wpdb->query($sql);
        require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
function rbrurls_remove_database() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'rbrurls_redirect';
    $sql = "DROP TABLE IF EXISTS $table_name;";
    $wpdb->query($sql);
}
register_deactivation_hook(__FILE__, 'rbrurls_remove_database');
?>
