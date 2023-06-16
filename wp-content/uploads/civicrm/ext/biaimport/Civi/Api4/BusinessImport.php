<?php

/*
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC. All rights reserved.                        |
 |                                                                    |
 | This work is published under the GNU AGPLv3 license with some      |
 | permitted exceptions and without any warranty. For full license    |
 | and copyright information, see https://civicrm.org/licensing       |
 +--------------------------------------------------------------------+
 */

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC https://civicrm.org/licensing
 */

namespace Civi\Api4;

use CRM_Biaimport_ExtensionUtil as E;

/**
 * @searchable none
 * @since 5.62
 * @package Civi\Api4
 */
class BusinessImport extends Generic\AbstractEntity {


  /**
   * @param bool $checkPermissions
   * @return Generic\BasicGetFieldsAction
   */
  public static function getFields($checkPermissions = TRUE) {
    $spec = [
      [
        'name' => 'property_address',
        'title' => E::ts('Tax Roll Address'),
        'data_type' => 'String',
        'required' => TRUE,
      ],
      [
        'name' => 'property_tax_roll_unit',
        'title' => E::ts('Tax Roll Unit'),
        'data_type' => 'String',
      ],
      [
        'name' => 'property_street_address',
        'title' => E::ts('Business Mailing Address'),
        'data_type' => 'String',
      ],
      [
        'name' => 'property_street_unit',
        'title' => E::ts('Business Mailing Unit/Suite'),
        'data_type' => 'String',
      ],
      [
        'name' => 'unit_size',
        'title' => E::ts('Unit Size (Sq Ft)'),
        'data_type' => 'Integer',
      ],
      [
        'name' => 'unit_price',
        'title' => E::ts('Unit Price per Sq Ft'),
        'data_type' => 'Money',
      ],
      [
        'name' => 'unit_status',
        'title' => E::ts('Unit Status'),
        'data_type' => 'String',
      ],
      [
        'name' => 'unit_location',
        'title' => E::ts('Unit Location (Ground Floor, Floor #)'),
        'data_type' => 'String',
      ],
      [
        'name' => 'organization_name',
        'title' => E::ts('Business Name'),
        'data_type' => 'String',
      ],
      [
        'name' => 'first_name',
        'title' => E::ts('Business Contact First Name'),
        'data_type' => 'String',
      ],
      [
        'name' => 'last_name',
        'title' => E::ts('Business Contact Last Name'),
        'data_type' => 'String',
      ],
      [
        'name' => 'contact_position',
        'title' => E::ts('Business Contact Position'),
        'data_type' => 'String',
      ],
      [
        'name' => 'phone',
        'title' => E::ts('Telephone #'),
        'data_type' => 'String',
      ],
      [
        'name' => 'contact_email',
        'title' => E::ts('Business Contact E-mail'),
        'data_type' => 'String',
      ],
      [
        'name' => 'website',
        'title' => E::ts('Website URL'),
        'data_type' => 'String',
      ],
      [
        'name' => 'email',
        'title' => E::ts('Business Email'),
        'data_type' => 'String',
      ],
    ];
    $socialMedia = [
      'linkedin' => E::ts('LinkedIn'),
      'facebook' => E::ts('Facebook'),
      'instagram' => E::ts('Instagram'),
      'twitter' => E::ts('Twitter'),
      'ticktok' => E::ts('TickTok'),
    ];
    foreach ($socialMedia as $sm => $smTitle) {
      $spec[] = [
        'name' => $sm . '_url',
        'title' => E::ts('%1 URL', [1 => $smTitle]),
        'data_type' => 'String',
      ];
    }
    $spec[] = [
      'name' => 'owner_' . $owner . '_last_name',
      'title' => E::ts('Individual Owner %1 Last Name', [1 => $owner]),
      'data_type' => 'String',
    ];
    $spec[] = [
      'name' => 'owner_' . $owner . '_mobile_phone',
      'title' => E::ts('Individual Owner %1 Mobile Phone', [1 => $owner]),
      'data_type' => 'String',
    ];
    $spec[] = [
      'name' => 'owner_' . $owner . '_company_name',
      'title' => E::ts('Individual Owner %1 Company Name', [1 => $owner]),
      'data_type' => 'String',
    ];
    $demographicFields = [
      'francophone' => E::ts('Francophone'),
      'women' => E::ts('Women'),
      'youth_39_under' => E::ts('Youth 39&Under'),
      'lgbtiq' => E::ts('LGBTQ+'),
      'indigenous' => E::ts('Indigenous (First Nations, Inuit or Metis)'),
      'racialized' => E::ts('Racialized group memebr'),
      'newcomers' => E::ts('Newcomers, immigrants and refugees'),
      'black' => E::ts('Black community member'),
      'disabilities' => E::ts('Persons with disabilities'),
    ];
    foreach ($demographicFields as $key => $label) {
      $spec[] = [
        'name' => $key,
        'title' => $label,
        'data_type' => 'Boolean',
      ];
    }
    return (new Generic\BasicGetFieldsAction(__CLASS__, __FUNCTION__, function() use ($spec) {
      return $spec;
    }))->setCheckPermissions($checkPermissions);
  }

}
