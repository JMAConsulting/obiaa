<?php

class CRM_CiviMobileAPI_Hook_ApiPost_SaveActivity {

  public static function run($dao) {
    if (isset($_POST['hasVoted']) && !is_null($dao->status_id)) {
      $hasVoted = CRM_Utils_String::strtoboolstr(CRM_Utils_Type::escape($_POST['hasVoted'], 'String'));
      $gotvCustomFieldName = 'custom_' . CRM_CiviMobileAPI_Utils_CustomField::getId(CRM_CiviMobileAPI_Install_Entity_CustomGroup::SURVEY, CRM_CiviMobileAPI_Install_Entity_CustomField::SURVEY_GOTV_STATUS);

      civicrm_api3('Activity', 'create', [
        $gotvCustomFieldName => $hasVoted,
        'id' => $dao->id
      ]);
    }
  }
}
