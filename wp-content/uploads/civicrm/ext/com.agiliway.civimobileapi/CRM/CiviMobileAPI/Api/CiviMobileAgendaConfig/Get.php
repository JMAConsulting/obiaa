<?php

class CRM_CiviMobileAPI_Api_CiviMobileAgendaConfig_Get extends CRM_CiviMobileAPI_Api_CiviMobileBase {

  /**
   * Returns results to api
   *
   * @return array
   * @throws api_Exception
   */
  public function getResult() {
    $agendaConfig = new CRM_CiviMobileAPI_BAO_AgendaConfig();
    $agendaConfig->event_id = $this->validParams["event_id"];
    if (!$agendaConfig->find(TRUE)) {
      return [];
    }

    return [
      [
        'id' => $agendaConfig->id,
        'is_active' => $agendaConfig->is_active,
        'event_id' => $agendaConfig->event_id
      ]
    ];
  }

  /**
   * Returns validated params
   *
   * @param $params
   *
   * @return array
   * @throws api_Exception
   */
  protected function getValidParams($params) {
    if (!CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionForGetAgendaConfig()) {
      throw new api_Exception('You don`t have enough permissions.', 'do_not_have_enough_permissions');
    }
    return [
      'event_id' => $params['event_id'],
    ];
  }

}
