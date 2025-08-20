<?php

require_once 'businesscontacttoken.civix.php';
// phpcs:disable
use CRM_Businesscontacttoken_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function businesscontacttoken_civicrm_config(&$config): void {
  _businesscontacttoken_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function businesscontacttoken_civicrm_install(): void {
  _businesscontacttoken_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function businesscontacttoken_civicrm_enable(): void {
  _businesscontacttoken_civix_civicrm_enable();
}

function businesscontacttoken_civicrm_container($container) {
  $container->addResource(new \Symfony\Component\Config\Resource\FileResource(__FILE__));
  $container->findDefinition('dispatcher')->addMethodCall('addListener', ['civi.token.eval', 'businesscontacttoken_evaluate_token']);
  $container->findDefinition('dispatcher')->addMethodCall('addListener', ['civi.token.list', 'businesscontacttoken_register_token']);
}

function businesscontacttoken_register_token(\Civi\Token\Event\TokenRegisterEvent $e) {
  $context = $e->getTokenProcessor()->context;
  if (key_exists('schema', $context) && in_array('contactId', $context['schema'])) {
    $e->entity(CRM_Businesscontacttoken_Token::TOKEN)
      ->register('contact_id', E::ts('Update Business Links'));
  }
}

function businesscontacttoken_evaluate_token(\Civi\Token\Event\TokenValueEvent $e) {
  $context = $e->getTokenProcessor()->context;
  foreach ($e->getRows() as $row) {
    if (isset($row->context['contactId'])) {
      $contactId = $row->context['contactId'];
      $businessFormLinks = CRM_Businesscontacttoken_Token::businessFormLinks($contactId);
      $row->format('text/html');
      $row->tokens(CRM_Businesscontacttoken_Token::TOKEN, 'contact_id', $businessFormLinks);
    } elseif (key_exists('schema', $context) && in_array('contactId', $context['schema'])) {
      $contactId = $row->context['schema']['contactId'];
      $businessFormLinks = CRM_Businesscontacttoken_Token::businessFormLinks($contactId);
      $row->format('text/html');
      $row->tokens(CRM_Businesscontacttoken_Token::TOKEN, 'contact_id', $businessFormLinks);
    }
  }
}
