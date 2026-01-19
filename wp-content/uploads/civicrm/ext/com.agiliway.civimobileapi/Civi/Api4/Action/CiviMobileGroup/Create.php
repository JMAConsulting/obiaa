<?php

namespace Civi\Api4\Action\CiviMobileGroup;

use Civi\Api4\Generic\AbstractCreateAction;
use Civi\Api4\Generic\Result;
use CRM_Utils_String;

class Create extends AbstractCreateAction {

  public function _run(Result $result) {
    $values = $this->getValues();

    $apiParams = [
      'frontend_title' => $values['public_group_title'],
      'frontend_description' => $values['public_group_description'],
      'title' => $values['group_title'],
      'description' => $values['group_description'],
      'name' => CRM_Utils_String::titleToVar($values['group_title']),
      'is_active' => $values['is_active'],
      'visibility' => $values['visibility'],
      'group_type' => $values['group_type'],
      'parents' => $values['parents'],
      'is_reserved' => $values['is_reserved'],
    ];

    if (!empty($values['id'])) {
      $group = civicrm_api4('Group', 'update', [
        'values' => $apiParams,
        'where' => [
          ['id', '=', $values['id']],
        ],
        'checkPermissions' => FALSE,
      ])->getArrayCopy();
    }
    else {
      $group = civicrm_api4('Group', 'create', [
        'values' => $apiParams,
        'checkPermissions' => FALSE,
      ])->getArrayCopy();
    }

    $result->exchangeArray($group);
  }

}
