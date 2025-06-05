<?php

/**
 * Class handles CiviMobileCustomFields api
 */
class CRM_CiviMobileAPI_Api_CiviMobileAvailableContactGroup_Get extends CRM_CiviMobileAPI_Api_CiviMobileBase {
  /**
   * Returns results to api
   *
   * @return array
   */
  public function getResult() {
    return civicrm_api4('Group', 'get', [
      'select' => [
        'id',
        'name',
        'title',
      ],
      'join' => [
        ['GroupContact AS group_contact', 'EXCLUDE', ['group_contact.contact_id', '=', $this->validParams['contact_id']], ['group_contact.group_id', '=', 'id']],
      ],
      'where' => [
        ['is_hidden', '=', $this->validParams['is_hidden']],
      ],
      'checkPermissions' => FALSE,
    ])->getArrayCopy();
  }

  /**
   * Returns validated params
   *
   * @param $params
   *
   * @return array
   * @throws \api_Exception
   */
  protected function getValidParams($params) {
    $contact = new CRM_Contact_BAO_Contact();
    $contact->id = $params['contact_id'];
    $contactExistence = $contact->find(TRUE);

    if (empty($contactExistence)) {
      throw new api_Exception('Contact(id=' . $params['contact_id'] . ') does not exist.', 'contact_does_not_exist');
    }

    if (!isset($params['is_hidden'])) {
      $params['is_hidden'] = FALSE;
    }

    return [
      'contact_id' => $params['contact_id'],
      'is_hidden' => $params['is_hidden']
    ];
  }
}
