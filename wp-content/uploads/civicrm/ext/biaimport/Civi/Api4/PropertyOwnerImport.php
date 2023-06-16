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
class PropertyOwnerImport extends Generic\AbstractEntity {


  /**
   * @param bool $checkPermissions
   * @return Generic\BasicGetFieldsAction
   */
  public static function getFields($checkPermissions = TRUE) {
    $spec = [
      [
        'name' => 'roll_no',
        'title' => E::ts('Assessment Roll Number'),
        'data_type' => 'String',
        'required' => TRUE,
      ],
      [
        'name' => 'property_name',
        'title' => E::ts('Property Name'),
        'data_type' => 'String',
      ],
      [
        'name' => 'property_address',
        'title' => E::ts('Property Address'),
        'data_type' => 'String',
        'required' => TRUE,
      ],
      [
        'name' => 'city',
        'title' => E::ts('City'),
        'data_type' => 'String',
      ],
      [
        'name' => 'postal_code',
        'title' => E::ts('postal_code'),
        'data_type' => 'String',
      ],
      [
        'name' => 'property_manager_first_name',
        'title' => E::ts('Property Manager first name'),
        'data_type' => 'String',
      ],
      [
        'name' => 'property_manager_last_name',
        'title' => E::ts('Property Manager last name'),
        'data_type' => 'String',
      ],
      [
        'name' => 'property_manager_email',
        'title' => E::ts('Property Manger email'),
        'data_type' => 'String',
      ],
      [
        'name' => 'property_manager_phone',
        'title' => E::ts('Property Manager Phone'),
        'data_type' => 'String',
      ],
    ];
    $owners = [1, 2, 3, 4];
    foreach($owners as $owner) {
      $spec[] = [
        'name' => 'owner_' . $owner . '_first_name',
        'title' => E::ts('Individual Owner %1 First Name', [1 => $owner]),
        'data_type' => 'String',
      ];
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
      $spec[] = [
        'name' => 'owner_' . $owner . '_email',
        'title' => E::ts('Individual Owner %1 Business Email', [1 => $owner]),
        'data_type' => 'String',
      ];
      $spec[] = [
        'name' => 'owner_' . $owner . '_phone',
        'title' => E::ts('Individual Owner %1 Business Phone', [1 => $owner]),
        'data_type' => 'String',
      ];
      $spec[] = [
        'name' => 'owner_' . $owner . '_street_address',
        'title' => E::ts('Individual Owner %1 Street Address', [1 => $owner]),
        'data_type' => 'String',
      ];
      $spec[] = [
        'name' => 'owner_' . $owner . '_unit',
        'title' => E::ts('Individual Owner %1 Unit', [1 => $owner]),
        'data_type' => 'String',
      ];
      $spec[] = [
        'name' => 'owner_' . $owner . '_supplemental_address_1',
        'title' => E::ts('Individual Owner %1 Supplemental Address', [1 => $owner]),
        'data_type' => 'String',
      ];
      $spec[] = [
        'name' => 'owner_' . $owner . '_city',
        'title' => E::ts('Individual Owner %1 City', [1 => $owner]),
        'data_type' => 'String',
      ];
      $spec[] = [
        'name' => 'owner_' . $owner . '_province',
        'title' => E::ts('Individual Owner %1 Province', [1 => $owner]),
        'data_type' => 'String',
      ];
      $spec[] = [
        'name' => 'owner_' . $owner . '_postal_code',
        'title' => E::ts('Individual Owner %1 Postal Code', [1 => $owner]),
        'data_type' => 'String',
      ];
      $spec[] = [
        'name' => 'owner_' . $owner . '_country',
        'title' => E::ts('Individual Owner %1 Country', [1 => $owner]),
        'data_type' => 'String',
      ];
    }
    return (new Generic\BasicGetFieldsAction(__CLASS__, __FUNCTION__, function() {
      return $spec;
    }))->setCheckPermissions($checkPermissions);
  }

}
