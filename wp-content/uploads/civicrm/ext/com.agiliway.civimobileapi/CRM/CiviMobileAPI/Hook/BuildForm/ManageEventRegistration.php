<?php

class CRM_CiviMobileAPI_Hook_BuildForm_ManageEventRegistration {

  public static function run($formName, &$form) {
    if ($formName == 'CRM_Event_Form_ManageEvent_Registration' && $form->getAction() == CRM_Core_Action::UPDATE) {
      $form->addElement('checkbox',
        'civi_mobile_is_event_mobile_registration',
        ts('Is allow mobile registration?')
      );

      if ($form->getAction() == CRM_Core_Action::UPDATE) {
        $eventId = $form->_id;
        $elementValue = CRM_CiviMobileAPI_Utils_IsAllowMobileEventRegistrationField::getValue($eventId);
        $form->setDefaults([
          'civi_mobile_is_event_mobile_registration' => $elementValue,
        ]);
      }

      CRM_Core_Region::instance('page-header')->add([
        'template' => 'CRM/CiviMobileAPI/Block/IsAllowMobileRegistration.tpl'
      ]);
    }
  }
}
