<?php

class CRM_CiviMobileAPI_Hook_AlterAPIPermissions_MobileRequest {

  public static function run($entity, $action, &$params) {
    if (CRM_CiviMobileAPI_Hook_Utils::is_mobile_request()) {
      CRM_CiviMobileAPI_Hook_Utils::civimobileapi_secret_validation();
      if (($entity == 'calendar' and $action == 'get') ||
        ($entity == 'civi_mobile_participant' and $action == 'create') ||
        ($entity == 'civi_mobile_participant_payment' and $action == 'create') ||
        ($entity == 'participant_status_type' and $action == 'get') ||
        ($entity == 'civi_mobile_get_price_set_by_event' and $action == 'get') ||
        ($entity == 'my_event' and $action == 'get') ||
        ($entity == 'civi_mobile_system' and $action == 'get') ||
        ($entity == 'setting' and $action == 'get') ||
        ($entity == 'civi_mobile_calendar' and $action == 'get') ||
        ($entity == 'civi_mobile_my_ticket' and $action == 'get') ||
        ($entity == 'relationship' and $action == 'update') ||
        ($entity == 'civi_mobile_case_role') ||
        ($entity == 'civi_mobile_allowed_relationship_types') ||
        ($entity == 'civi_mobile_allowed_extended_relationship_types') ||
        ($entity == 'push_notification' and $action == 'create') ||
        ($entity == 'contact_type' and $action == 'get') ||
        ($entity == 'location_type' and $action == 'get') ||
        ($entity == 'civi_mobile_permission' and $action == 'get') ||
        ($entity == 'option_value' and $action == 'get') ||
        ($entity == 'phone' and $action == 'create') ||
        ($entity == 'email' and $action == 'create') ||
        ($entity == 'contact' and $action == 'delete') ||
        ($entity == 'civi_mobile_contact' and $action == 'create') ||
        ($entity == 'phone' and $action == 'create') ||
        ($entity == 'address' and $action == 'create') ||
        ($entity == 'website' and $action == 'create') ||
        ($entity == 'civi_mobile_active_relationship' and $action == 'get') ||
        ($entity == 'civi_mobile_allowed_activity_types' and $action == 'get') ||
        ($entity == 'civi_mobile_contribution_statistic') ||
        ($entity == 'state_province' and $action == 'get') ||
        ($entity == 'civi_mobile_available_contact_group' and $action == 'get') ||
        ($entity == 'civi_mobile_tag_structure' and $action == 'get') ||
        ($entity == 'civi_mobile_custom_fields' and $action == 'get') ||

        ($entity == 'civi_mobile_survey_respondent' and $action == 'reserve') ||
        ($entity == 'civi_mobile_survey_respondent' and $action == 'get') ||
        ($entity == 'civi_mobile_survey_respondent' and $action == 'release') ||
        ($entity == 'civi_mobile_survey_respondent' and $action == 'gotv') ||
        ($entity == 'civi_mobile_survey_respondent' and $action == 'get_to_reserve') ||
        ($entity == 'civi_mobile_survey' and $action == 'get_contact_surveys') ||
        ($entity == 'civi_mobile_survey' and $action == 'get_structure') ||
        ($entity == 'civi_mobile_survey' and $action == 'sign') ||
        ($entity == 'civi_mobile_survey' and $action == 'get_signed_values') ||
        ($entity == 'civi_mobile_survey_interviewer' and $action == 'get') ||
        ($entity == 'contribution_page' and $action == 'get') ||
        ($entity == 'civi_mobile_contact_group' and $action == 'delete') ||
        ($entity == 'financial_type' and $action == 'get')
      ) {
        $params['check_permissions'] = FALSE;
      }
    }
  }
}
