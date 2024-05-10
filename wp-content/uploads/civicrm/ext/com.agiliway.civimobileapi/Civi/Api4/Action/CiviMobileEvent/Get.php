<?php

namespace Civi\Api4\Action\CiviMobileEvent;

use Civi\Api4\Generic\BasicGetAction;
use Civi\Api4\Generic\Result;
use CRM_Core_Permission;
use CRM_Core_Session;

class Get extends BasicGetAction {

  protected function getEvents() {
    $contactId = CRM_Core_Session::getLoggedInContactID();

    $hasPermissionsToViewAllEvents = CRM_Core_Permission::check('access CiviEvent') || CRM_Core_Permission::check('view event info');

    $params = $this->getParams();
    $params['checkPermissions'] = FALSE;

    if (!$hasPermissionsToViewAllEvents && !empty($contactId)) {
      $params['where'][] = ['gc.contact_id', '=', $contactId];
      $params['where'][] = ['p.contact_id', '!=', $contactId];
      $params['where'][] = ['acl.object_table', '=', 'civicrm_event'];

      $params['join'] = [
        ['ACL AS acl', 'LEFT', ['acl.object_id', '=', 'id']],
        ['ACLEntityRole AS acl_r', 'LEFT', ['acl_r.acl_role_id', '=', 'acl.entity_id']],
        ['GroupContact AS gc', 'LEFT', ['gc.group_id', '=', 'acl_r.entity_id']],
        ['Participant AS p', 'LEFT', ['p.event_id', '=', 'id']],
      ];
    }

    return  civicrm_api4('Event', 'get', $params);
  }

  /**
   * @param Result $result
   * @return void
   */
  public function _run(Result $result) {

    $result->exchangeArray($this->getEvents());
  }
}
