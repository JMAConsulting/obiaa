<?php

class CRM_CiviMobileAPI_Utils_CiviCRM {

  /**
   * Gets enabled CiviCRM components
   *
   * @return array
   */
  public static function getEnabledComponents() {
    $enableComponents = [];
    try {
      $result = civicrm_api3('Setting', 'get', [
        'sequential' => 1,
        'return' => ["enable_components"],
      ]);
    } catch (CiviCRM_API3_Exception $e) {
      return [];
    }

    if (isset($result['values'][0]['enable_components'])) {
      foreach ($result['values'][0]['enable_components'] as $component) {
        $enableComponents[] = $component;
      }
    }

    return $enableComponents;
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
      } elseif ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_WORDPRESS ) {
        $url = str_replace("wp-admin/admin.php", "index.php", $url);
      }

      return $url;
    }

    return '';
  }

}
