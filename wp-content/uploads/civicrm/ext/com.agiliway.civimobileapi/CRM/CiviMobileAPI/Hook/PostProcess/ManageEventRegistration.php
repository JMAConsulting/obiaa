<?php

class CRM_CiviMobileAPI_Hook_PostProcess_ManageEventRegistration {

  public static function run($formName, &$form) {
    if ($formName == 'CRM_Event_Form_ManageEvent_Registration' && ($form->getAction() == CRM_Core_Action::UPDATE || $form->getAction() == CRM_Core_Action::ADD)) {
      $values = $form->exportValues();

      $newValue = isset($values['civi_mobile_is_event_mobile_registration']) ? 1 : 0;
      $eventId = $form->_id;
      CRM_CiviMobileAPI_Utils_IsAllowMobileEventRegistrationField::setValue($eventId, $newValue);
    }
  }
}
