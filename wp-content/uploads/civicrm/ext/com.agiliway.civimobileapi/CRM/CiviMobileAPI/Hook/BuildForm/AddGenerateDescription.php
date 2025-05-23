<?php

class CRM_CiviMobileAPI_Hook_BuildForm_AddGenerateDescription {

  public static function run($formName, &$form) {
    if (($formName == 'CRM_Event_Form_ManageEvent_EventInfo' ||
        $formName == 'CRM_Campaign_Form_Survey_Main' ||
        $formName == 'CRM_Contact_Form_Task_Email' ||
        $formName == 'CRM_Admin_Form_MessageTemplates') &&
        $form->getAction() != CRM_Core_Action::VIEW
    ) {
      CRM_CiviMobileAPI_Hook_Utils::civimobile_add_generate_description_popup();
    }
    else if (($formName == 'CRM_Campaign_Form_Petition' || $formName == 'CRM_Campaign_Form_Campaign') && !empty($form->urlPath) && $form->urlPath[2] != 'view') {
      CRM_CiviMobileAPI_Hook_Utils::civimobile_add_generate_description_popup();
    }
  }
}
