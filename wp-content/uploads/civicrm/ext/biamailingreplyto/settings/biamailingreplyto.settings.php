<?php
use CRM_Biamailingreplyto_ExtensionUtil as E;

return [
  'biamailingreplyto_primary_contact_id' => [
    'name' => 'biamailingreplyto_primary_contact_id',
    'type' => 'Integer',
    'description' => E::ts('Primary Contact for use in setting the reply to on civimails'),
    'default' => 0,
    'html_type' => 'entity_reference',
    'entity_reference_options' => ['entity' => 'contact'],
    'title' => E::ts('Primary Contact ID'),
    'is_domain' => 1,
    'settings_pages' => ['biamailingreplyto' => ['weight' => 10]],
  ],
];   
