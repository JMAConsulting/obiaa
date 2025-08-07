<?php

/**
 * Plugin Name:  WP SES Config Set
 * Plugin URI:   https://www.wpbeginner.com
 * Description:  Support for SES Configuration Sets
 * Version:      1.0
 * Author:       JMA
 * Author URI:   https://jmaconsulting.biz/
 * License:      GPL2
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  wp-ses-config-set
 * Domain Path:  /languages
 **/

function add_settings_page() {
  add_options_page('SES Configuration Set', 'SES Configuration', 'manage_options', 'ses-configuration', 'render_plugin_settings_page');
}
add_action('admin_menu', 'add_settings_page');

function render_plugin_settings_page() {
?>
  <h1>SES Configuration</h1>
  <form action="options.php" method="post">
    <?php
    settings_fields('ses_config_plugin_options');
    do_settings_sections('ses_config_plugin'); ?>
    <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e('Save'); ?>" />
  </form>
<?php
}

function register_settings() {
  register_setting('ses_config_plugin_options', 'ses_config_plugin_options', 'ses_config_plugin_options_validate');
  // register_setting('ses_config_plugin_options', 'ses_config_plugin_options', 'test');
  add_settings_section('api_settings', '', 'plugin_section_text', 'ses_config_plugin');

  add_settings_field('plugin_setting_ses_config_set', 'SES Configuration Set', 'plugin_setting_ses_config_set', 'ses_config_plugin', 'api_settings');
}
add_action('admin_init', 'register_settings');

function plugin_section_text() {
}

function plugin_setting_ses_config_set() {
  $options = get_option('ses_config_plugin_options');
  echo "<input id='plugin_setting_ses_config_set' name='ses_config_plugin_options[ses_config_set]' type='text' value='" . esc_attr($options['ses_config_set']) . "' />";
}

function set_default_configuration_set() {
  $host = parse_url(get_site_url(), PHP_URL_HOST);
  $default = str_replace('.', '-', $host) . '-ses-cs';
  update_option('ses_config_plugin_options', ['ses_config_set' => $default]);
}

register_activation_hook(__FILE__, 'set_default_configuration_set');

function wp_ses_config_set_custom_mail_header($args) {
  if (! is_array($args['headers'])) {
    $args['headers'] = explode("\n", str_replace("\r\n", "\n", $args['headers']));
  }
  $args['headers'][] = 'X-SES-CONFIGURATION-SET: ' . esc_attr(get_option('ses_config_plugin_options')['ses_config_set']);
  return $args;
}
add_filter('wp_mail', 'wp_ses_config_set_custom_mail_header');
