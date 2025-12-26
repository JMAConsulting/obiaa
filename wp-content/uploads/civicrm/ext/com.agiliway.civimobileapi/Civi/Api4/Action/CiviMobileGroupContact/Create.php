<?php

namespace Civi\Api4\Action\CiviMobileGroupContact;

use Civi\Api4\Generic\BasicCreateAction;
use Civi\Api4\Generic\Result;
use CRM_Contact_BAO_GroupContact;

class Create extends BasicCreateAction {

  /**
   * @param Result $result
   *
   * @return Result
   */
  public function _run(Result $result) {
    $values = $this->getValues();

    if ($values['status'] == 'Added') {
      CRM_Contact_BAO_GroupContact::addContactsToGroup([$values['contact_id']], $values['group_id']);
    }
    elseif ($values['status'] == 'Removed') {
      $contactsToRemove = [$values['contact_id']];

      CRM_Contact_BAO_GroupContact::removeContactsFromGroup($contactsToRemove, $values['group_id']);
    }

    return $result;
  }

}
