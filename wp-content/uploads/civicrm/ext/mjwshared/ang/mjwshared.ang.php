<?php
// This file declares an Angular module which can be autoloaded
// in CiviCRM. See also:
// \https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules/n
return [
  'js' => [
    'ang/mjwshared.js',
    'ang/mjwshared/*.js',
    'ang/mjwshared/*/*.js',
  ],
  'css' => [
    'ang/mjwshared.css',
  ],
  'partials' => [
    'ang/mjwshared',
  ],
  'requires' => [
    'crmUi',
    'crmUtil',
    'ngRoute',
  ],
  'settings' => [],
];
