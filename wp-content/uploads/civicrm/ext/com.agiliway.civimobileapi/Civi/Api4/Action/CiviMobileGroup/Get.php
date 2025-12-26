<?php

namespace Civi\Api4\Action\CiviMobileGroup;

use Civi\Api4\Generic\BasicGetAction;
use Civi\Api4\Generic\Result;
use CRM_Core_Session;

class Get extends BasicGetAction {

  public function _run(Result $result) {
    $selectParams = $this->getSelect();
    $whereParams = $this->getWhere();

    $group = civicrm_api4('Group', 'get', [
      'select' => $selectParams,
      'where' => $whereParams,
      'checkPermissions' => FALSE,
    ])->first();

    if (!empty($group['parents'])) {
      $groupParents = civicrm_api4('Group', 'get', [
        'select' => ['title'],
        'where' => [
          ['id', 'IN', $group['parents']],
        ],
        'checkPermissions' => FALSE,
      ])->getArrayCopy();

      $group['parents_title'] = array_column($groupParents, 'title');
    }

    $members = civicrm_api4('Contact', 'get', [
      'select' => [
        'id',
        'row_count',
      ],
      'where' => [
        ['groups', 'IN', [$group['id']]],
      ],
      'checkPermissions' => FALSE,
    ]);

    $group['members'] = array_map(function($contact) {
      return $contact['id'];
    }, $members->getArrayCopy());
    $group['members_count'] = $members->count();

    $groupContactStatus = civicrm_api4('GroupContact', 'get', [
      'select' => [
        'status',
      ],
      'where' => [
        ['group_id', '=', $group['id']],
        ['contact_id', '=', CRM_Core_Session::getLoggedInContactID()],
      ],
      'checkPermissions' => TRUE,
    ])->first();

    if (!empty($groupContactStatus)) {
      $group['contact_status'] = $groupContactStatus['status'] ?: 'Added';
    }
    else {
      $group['contact_status'] = NULL;
    }

    $result->exchangeArray([$group]);
  }

}
