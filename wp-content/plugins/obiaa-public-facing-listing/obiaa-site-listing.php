<?php

/**
 * Plugin Name: obiaa-site-listing
 * Description: Public facing site listings
 * Version: 0.1
 **/
function get_listings() {
  //Get plugin settings
  $options = get_option('obiaa_site_listing_settings_options');

  $api_key = $options['obiaa_site_listing_settings_api_key'];
  $url = $options['obiaa_site_listing_settings_url'];
  if (!isset($api_key) || !isset($url)) {
    return;
  }

  // Get contacts
  $params = [
    'select' => ['organization_name', 'image_URL', 'Business_Category.Parent_Class:label', 'Business_Category.Child_Class:label', 'Business_Category.Child_Class_Unique:label', 'address.street_address', 'address.supplemental_address_1', 'address.city', 'address.state_province_id:abbr', 'address.postal_code', 'address.country_id:abbr'],
    'join' => [['Address AS address', 'LEFT', ['id', '=', 'address.contact_id']]],
    'where' => [['contact_sub_type', '=', 'Members_Businesses_'], ['is_deleted', '=', FALSE], ['Business_Category.Opt_out_of_Public_Listing_:label', '=', 'No']],
    'limit' => '0',
  ];
  $request = stream_context_create(
        [
          'http' => [
            'method' => 'POST',
            'header' => [
              'Content-Type: application/x-www-form-urlencoded',
              "X-Civi-Auth: Bearer {$api_key}",
            ],
            'content' => http_build_query(['params' => json_encode($params)]),
          ],
        ]
    );
  $contacts = json_decode(@file_get_contents($url . 'civicrm/ajax/api4/Contact/get', FALSE, $request), TRUE);
  if (empty($contacts) || array_key_exists('error_message', $contacts)) {
    return;
  }

  // Format data for jsGrid
  $data = [];
  $d = [];
  // Filters
  $filters = [
    'Business_Category.Parent_Class:label' => 'category',
    'Business_Category.Child_Class:label' => 'subCategory',
    'Business_Category.Child_Class_Unique:label' => 'businessType',
  ];
  // Address
  $addressKeys = ['address.street_address', 'address.supplemental_address_1', 'address.city', 'address.state_province_id:abbr', 'address.postal_code', 'address.country_id:abbr'];
  foreach ($contacts['values'] as $contact) {
    $address = $category = $subCategory = $businessType = $row = [];
    $i = 0;
    foreach ($contact as $key => $val) {
      $row[$key] = [];
      if (in_array($key, $addressKeys)) {
        if ($val) {
          $address[$key] = $val;
        }
        if (++$i == count($contacts['values']) + 1) {
          $row['address'] = implode(
          ', ', array_map(
            function ($v, $k) {
                    return $k == 'address.postal_code' ? $v : '<br>' . $v;
            }, $address, array_keys($address)
          )
          );
        }
      }
      elseif (in_array($key, array_keys($filters))) {
        $row[$filters[$key]] = implode(',', $val);
      }
      else {
        array_push($row[$key], $val);
      }
    }
    array_push($data, $row);
  }

  //Add JS to page and pass filters + contacts
  add_js($data);

  return '<div id="jsGrid"></div>';
}

function add_js($data) {
  // Add js
  wp_enqueue_script('obiaa-site-listing', plugin_dir_url(__FILE__) . 'js/obiaa-site-listing.js', array('jquery'));
  wp_enqueue_script('jsgrid', plugin_dir_url(__FILE__) . 'js/jsgrid/dist/jsgrid.min.js');
  // Add css
  wp_enqueue_style('jsgrid-css', plugin_dir_url(__FILE__) . 'js/jsgrid/dist/jsgrid.min.css');
  wp_enqueue_style('jsgrid-theme-css', plugin_dir_url(__FILE__) . 'js/jsgrid/dist/jsgrid-theme.min.css');

  // Pass data to JS
  wp_localize_script('obiaa-site-listing', 'contacts', $data);

}

add_shortcode("site_listings", "get_listings");

/**
 * Add settings page, menu
 */
function obiaa_site_listing_settings_init() {

  //Register new setting
  register_setting(
        'obiaa_site_listing_settings', 'obiaa_site_listing_settings_options', [
          'type' => 'array',
          'sanitize_callback' => 'obiaa_site_listing_settings_sanitize_options',
        ]
    );

  //Add section
  add_settings_section(
        'obiaa_site_listing_settings_section',
        __('Enter API Key and URL', 'obiaa_site_listing_settings'), 'obiaa_site_listing_settings_section_callback',
        'obiaa_site_listing_settings'
    );
  //Add fields to section
  add_settings_field(
        'obiaa_site_listing_settings_api_key',
        __('API Key', 'obiaa_site_listing_settings'),
        'obiaa_site_listing_settings_api_key_cb',
        'obiaa_site_listing_settings',
        'obiaa_site_listing_settings_section',
        array(
          'label_for'         => 'obiaa_site_listing_settings_api_key',
          'class'             => 'obiaa_site_listing_settings_row',
        )
    );
  add_settings_field(
        'obiaa_site_listing_settings_url',
        __('URL', 'obiaa_site_listing_settings'),
        'obiaa_site_listing_settings_url_cb',
        'obiaa_site_listing_settings',
        'obiaa_site_listing_settings_section',
        array(
          'label_for'         => 'obiaa_site_listing_settings_url',
          'class'             => 'obiaa_site_listing_settings_row',
        )
    );
}

add_action('admin_init', 'obiaa_site_listing_settings_init');

function obiaa_site_listing_settings_section_callback($args) {
    ?>
    <p id="<?php echo esc_attr($args['id']); ?>"><?php esc_html_e('Please enter API Key and URL', 'obiaa_site_listing'); ?></p>
    <?php
}

function obiaa_site_listing_settings_api_key_cb($args) {
  $options = get_option('obiaa_site_listing_settings_options');
    ?><input
  type="text"
  placeholder="CiviCRM User API Key"
  style="width: 500px;"
  id="<?php echo esc_attr($args['label_for']); ?>"      
  name="obiaa_site_listing_settings_options[<?php echo esc_attr($args['label_for']); ?>]"
  value="<?php echo esc_attr($options[$args['label_for']]); ?>"/>      
    <?php
}

function obiaa_site_listing_settings_url_cb($args) {
  $options = get_option('obiaa_site_listing_settings_options');

    ?><input
      type="text"
      style="width: 500px;"
      placeholder="https://obiaa.com/"
      id="<?php echo esc_attr($args['label_for']); ?>"      
      name="obiaa_site_listing_settings_options[<?php echo esc_attr($args['label_for']); ?>]"
      value="<?php echo esc_attr($options[$args['label_for']]); ?>"/>      
    <?php
}

/**
 * Top level menu page
 */
function obiaa_site_listing_settings_options_page() {
  add_menu_page(
        'OBIAA Site Listing Settings',
        'OBIAA Site Listing Options',
        'manage_options',
        'obiaa_site_listing_settings',
        'obiaa_site_listing_settings_options_page_html'
    );
}

//Register page to admin menu hook
add_action('admin_menu', 'obiaa_site_listing_settings_options_page');

/**
 * Top level menu callback
 */
function obiaa_site_listing_settings_options_page_html() {
  if (!current_user_can('manage_options')) {
    return;
  }

  if (isset($_GET['settings-updated']) && empty(get_settings_errors('obiaa_site_listing_settings_messages'))) {
    add_settings_error('obiaa_site_listing_settings_messages', 'obiaa_site_listing_settings_message', __('Settings Saved', 'obiaa_site_listing_settings'), 'updated');
  }

  settings_errors('obiaa_site_listing_settings_messages');
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
    <?php
    settings_fields('obiaa_site_listing_settings');
    do_settings_sections('obiaa_site_listing_settings');
    submit_button('Save Settings');
    ?>
        </form>
    </div>
    <?php
}

/**
 * Settings Validation
 */
function obiaa_site_listing_settings_sanitize_options($data) {
  $options = get_option('obiaa_site_listing_settings_options');

  $has_errors = FALSE;

  if (empty($data['obiaa_site_listing_settings_api_key']) || empty($data['obiaa_site_listing_settings_url'])) {
    $has_errors = TRUE;
  }

  $url = $data['obiaa_site_listing_settings_url'];
  $params = [
    'limit' => 1,
  ];
  $request = stream_context_create(
        [
          'http' => [
            'method' => 'POST',
            'header' => [
              'Content-Type: application/x-www-form-urlencoded',
              "X-Civi-Auth: Bearer {$data['obiaa_site_listing_settings_api_key']}",
            ],
            'content' => http_build_query(['params' => json_encode($params)]),
          ],
        ]
    );
  $contacts = json_decode(@file_get_contents($url . 'civicrm/ajax/api4/Contact/get', FALSE, $request), TRUE);

  if (empty($contacts) || $contacts['error_message']) {
    $has_errors = TRUE;
  }

  if ($has_errors) {
    add_settings_error('obiaa_site_listing_settings_messages', 'obiaa_site_listing_settings_message', __('Invalid API Key or URL . Please try again', 'obiaa_site_listing_settings'), 'error');
    $data = $options;
  }

  return $data;
}
