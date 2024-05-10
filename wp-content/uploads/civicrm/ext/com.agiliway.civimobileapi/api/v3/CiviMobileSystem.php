<?php

/**
 * Gets information about extension
 *
 * @param array $params
 *   Array per getfields documentation.
 *
 * @return array API result array
 * @throws \CRM_Extension_Exception_ParseException
 */
function civicrm_api3_civi_mobile_system_get($params) {
  $result = [];
  $result[] = [
    'minimal_required_civimobile_app_version' => CRM_CiviMobileAPI_Utils_Extension::MINIMAL_REQUIRED_CIVIMOBILE_APP_VERSION,
    'cms' => CRM_CiviMobileAPI_Utils_CmsUser::getInstance()->getSystem(),
    'crm_version' => CRM_Utils_System::version(),
    'ext_version' => CRM_CiviMobileAPI_Utils_VersionController::getInstance()->getCurrentFullVersion(),
    'site_name' => CRM_CiviMobileAPI_Utils_Extension::getSiteName(),
    'is_showed_news' => CRM_CiviMobileAPI_Utils_Extension::isShowedNews(),
    'news_rss_feed_url' => CRM_CiviMobileAPI_Utils_Extension::newsRssFeedUrl(),
    'renewal_membership_contribution_page_url' => CRM_CiviMobileAPI_Utils_CiviCRM::getContributionPageUrl(),
    'time_zone_utc_offset' => CRM_CiviMobileAPI_Utils_Cms::getTimeZoneUTCOffset(),
    'time_tracker_extension_is_enabled' => CRM_CiviMobileAPI_Utils_Extension::isTimeTrackerExtensionEnabled(),
    'max_num_attachments' => Civi::settings()->get('max_attachments'),
    'max_file_size' => Civi::settings()->get('maxFileSize'),
  ];

  return civicrm_api3_create_success($result, $params);
}
