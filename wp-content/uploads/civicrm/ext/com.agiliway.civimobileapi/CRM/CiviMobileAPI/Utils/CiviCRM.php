<?php

class CRM_CiviMobileAPI_Utils_CiviCRM {

  /**
   * Gets enabled CiviCRM components
   *
   * @return array
   */
  public static function getEnabledComponents() {
    return civicrm_api4('Setting', 'get', [
      'select' => [
        'enable_components',
      ],
      'checkPermissions' => FALSE,
    ])->first()["value"];
  }

  /**
   * @return string
   */
  public static function getContributionPageUrl($pageId = NULL) {
    if (empty($pageId)) {
      $pageId = Civi::settings()->get('default_renewal_contribution_page');
    }

    if (!empty($pageId)) {
      $currentCMS = CRM_CiviMobileAPI_Utils_CmsUser::getInstance()->getSystem();

      $url = CRM_Utils_System::url('civicrm/contribute/transact', ['id' => $pageId, 'civimobile' => 1, 'reset' => 1], TRUE, NULL, FALSE);

      if ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_JOOMLA) {
        $url = preg_replace('/administrator\//', 'index.php', $url);
      } elseif ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_WORDPRESS) {
        $url = str_replace("wp-admin/admin.php", "index.php", $url);
      }

      return $url;
    }

    return '';
  }

  /**
   * @return array
   * @throws Exception
   */
  public static function getCurrencies() {
    $currencies = CRM_Core_OptionGroup::values('currencies_enabled');
    if(!empty($currencies)) {
      return array_keys($currencies);
    }
    return [];
  }
}
