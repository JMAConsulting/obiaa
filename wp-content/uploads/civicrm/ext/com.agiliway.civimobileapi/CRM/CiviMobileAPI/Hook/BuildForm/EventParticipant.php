<?php

class CRM_CiviMobileAPI_Hook_BuildForm_EventParticipant {

  public static function run($formName, &$form) {
    if ($formName == 'CRM_Event_Form_Participant' && $form->getAction() == CRM_Core_Action::ADD) {
      $elementName = 'send_receipt';
      if ($form->elementExists($elementName)) {
        $element = $form->getElement($elementName);
        $element->setValue(0);
      }
    }
  }
}
