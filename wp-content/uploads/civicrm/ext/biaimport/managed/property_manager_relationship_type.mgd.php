<?php
use CRM_Biaimport_ExtensionUtil as E;

return [
  [
    'name' => 'property_onwers_members_contact_type',
    'entity' => 'ContactType',
    'params' => [
      'version' => 3,
      'name' => 'Members_Property_Owners_',
      'parent_id' => 'Organization',
      'label' => 'Property Owners (members)',
    ],
  ],
  [
    'name' => 'property_manager_relationship_type',
    'entity' => 'RelationshipType',
    'params' => [
      'version' => 3,
      'name_a_b' => 'Property_Manager_for',
      'label_a_b' => E::ts('Property Manager for'),
      'name_b_a' => 'Property_Manager_is',
      'label_b_a' => E::ts('Property Manager is'),
      'description' => E::ts('Property Manager for Property'),
      'contact_type_a' => 'Individual',
      'contact_type_b' => 'Organization',
      'contact_sub_type_a' => NULL,
      'contact_sub_type_b' => 'Members_Property_Owners_',
      'is_reserved' => 1,
      'is_active' => 1,
    ],
  ],
];
