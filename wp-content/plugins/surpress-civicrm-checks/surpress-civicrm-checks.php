<?php
/**
 * Plugin Name:     Suppresses CiviCRM Checks
 * Plugin URI:      https://lab.civicrm.org/partners/civicoop/wordpress/surpress-civicrm-checks
 * Description:     Suppresses a number of health checks that are the effect of using CiviCRM
 * Author:          Klaas Eikelboom (klaas.eikelboom@civicoop.org)
 * Author URI:      https://civicoop.org
 * Text Domain:     civimove
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Civimove
 */
function surpress_civicrm_checks_tests( $tests ) {
  unset( $tests['direct']['rest_availability'] );
  unset( $tests['direct']['php_sessions'] );
  unset( $tests['async']['loopback_requests'] );
  return $tests;
}
add_filter( 'site_status_tests', 'surpress_civicrm_checks_tests' );
