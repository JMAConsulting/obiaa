<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_Hook_BuildForm_ManageEventEventInfo {

  public static function run($formName, &$form) {
    if ($formName == 'CRM_Event_Form_ManageEvent_EventInfo') {
      if ($form->getAction() == CRM_Core_Action::ADD) {
        $form->add('checkbox', 'default_qrcode_checkin_event', E::ts('When generating QR Code tokens, use this Event'));

        CRM_Core_Region::instance('page-body')->add([
          'template' => "qrcode-checkin-event-options.tpl"
        ]);

        CRM_Core_Region::instance('page-body')->add([
          'style' => '.custom-group-' . CRM_CiviMobileAPI_Install_Entity_CustomGroup::QR_USES . ' { display:none;}'
        ]);
      }

      CRM_Core_Region::instance('page-body')->add([
        'style' => '.custom-group-' . CRM_CiviMobileAPI_Install_Entity_CustomGroup::ALLOW_MOBILE_REGISTRATION . ' { display:none;}'
      ]);
    }
  }
}
