<?php

/**
 * Plugin Name: ACF strict URL Validation
 * Description: Adds a filter to ensure all URLs entered in ACF fields are valid
 * Version: 1.0
 * Author: JMA
 * Author URI: https://jmaconsulting.biz
 */
function validate_url($valid, $value, array $field, string $input_name) {
  if (!$field['required'] && empty($value)) {
    return true;
  }
  if (!filter_var($value, FILTER_VALIDATE_URL)) {
    $valid = 'Enter a valid URL';
  }
  return $valid;
}

add_filter('acf/validate_value/type=url', 'validate_url', 10, 4);
