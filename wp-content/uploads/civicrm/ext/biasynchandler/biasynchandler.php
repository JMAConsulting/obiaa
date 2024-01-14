<?php

require_once 'biasynchandler.civix.php';
// phpcs:disable
use CRM_Biasynchandler_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function biasynchandler_civicrm_config(&$config): void {
  _biasynchandler_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function biasynchandler_civicrm_install(): void {
  _biasynchandler_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function biasynchandler_civicrm_enable(): void {
  _biasynchandler_civix_civicrm_enable();
}
