<?php

class CRM_CiviMobileAPI_Hook_AlterBadge {

  public static function run(&$label, &$format) {
    $qrCodeCustomFieldName = "custom_" . CRM_CiviMobileAPI_Utils_CustomField::getId(CRM_CiviMobileAPI_Install_Entity_CustomGroup::QR_CODES, CRM_CiviMobileAPI_Install_Entity_CustomField::QR_IMAGE);
    if (isset($format['values'][$qrCodeCustomFieldName])) {
      $link = $format['values'][$qrCodeCustomFieldName];
      $label->printImage($link, '100', '0', 30, 30);

      //hide label
      if (!empty($format['token'])) {
        foreach ($format['token'] as $key => $token) {
          if ($token['token'] == '{participant.' . $qrCodeCustomFieldName . '}') {
            $format['token'][$key]['value'] = '';
          }
        }
      }
    }
  }
}
