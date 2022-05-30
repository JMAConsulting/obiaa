<?php

class CRM_CiviMobileAPI_Api_CiviMobileAgendaConfig_Create extends CRM_CiviMobileAPI_Api_CiviMobileBase {

  /**
   * Returns results to api
   *
   * @return array
   * @throws api_Exception
   */
  public function getResult() {
    $agendaConfig = new CRM_CiviMobileAPI_BAO_AgendaConfig();
    $agendaConfig->event_id = $this->validParams["event_id"];
    $agendaConfig->find(TRUE);
    $agendaConfig->is_active = $this->validParams["is_active"];
    $agendaConfig->save();

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
    if (!CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionForCreateAgendaConfig()) {
      throw new api_Exception('You don`t have enough permissions.', 'do_not_have_enough_permissions');
    }
    try {
      CRM_Event_BAO_Event::findById($params["event_id"]);
    } catch (Exception $e) {
      throw new api_Exception('This event does not exists.', 'event_does_not_exists');
    }

    return [
      'is_active' => $params['is_active'],
      'event_id' => $params['event_id'],
    ];
  }

}
