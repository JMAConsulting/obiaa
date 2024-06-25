<?php

require_once 'minifier.civix.php';
require_once __DIR__.'/vendor/autoload.php';
use CRM_Minifier_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function minifier_civicrm_config(&$config) {
  _minifier_civix_civicrm_config($config);

  if (isset(Civi::$statics[__FUNCTION__])) { return; }
  Civi::$statics[__FUNCTION__] = 1;

  // Add listeners for CiviCRM hooks that might need altering by other scripts
  // Make sure this runs after everything else.
  Civi::dispatcher()->addListener('hook_civicrm_buildAsset', 'minifier_symfony_civicrm_buildAsset', -999);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function minifier_civicrm_install() {
  _minifier_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function minifier_civicrm_enable() {
  _minifier_civix_civicrm_enable();
}

/**
 * @param \Civi\Core\Event\GenericHookEvent $event
 * @param $hook
 */
function minifier_symfony_civicrm_buildAsset($event, $hook) {
  if (empty($event->content) && !empty($event->params['path'])) {
    $event->content = file_get_contents($event->params['path']);
  }
  $assetType = explode('.', $event->asset);
  if (count($assetType) < 2) {
    // We can't detect the type if asset does not have 2 or more parts (eg. asset.js)
    return;
  }
  if ($assetType[count($assetType) - 2] === 'min') {
    // Don't minify already minified assets (eg. asset.min.js)
    return;
  }

  if (empty($event->mimeType) && !empty($event->params['mimetype'])) {
    $event->mimeType = $event->params['mimetype'];
  }

  if (!\Civi::service('asset_builder')->isCacheEnabled()) {
    return;
  }

  switch (end($assetType)) {
    case 'css':
      $compressor = new tubalmartin\CssMin\Minifier;
      $event->content = $compressor->run($event->content);
      break;

    case 'js':
      if (isset($event->params['modules']) || (strpos($event->content, 'function(angular') !== FALSE)) {
        // This is an angular asset - don't minify
        return;
      }
      $jz = new \Patchwork\JSqueeze();
      $event->content = $jz->squeeze($event->content, TRUE, TRUE, FALSE);
      break;

    default:
      return;
  }
}
