<?php

/**
 * Collection of upgrade steps.
 */
class CRM_CiviMobileAPI_Upgrader extends CRM_CiviMobileAPI_Upgrader_Base {

  public function upgrade_0001() {
    $this->ctx->log->info('Applying update 0001');
    CRM_CiviMobileAPI_PushNotification_Helper::deleteCustomGroup("contact_push_notification");

    return TRUE;
  }

  public function upgrade_0002() {
    try {
      $this->executeSqlFile('sql/auto_install.sql');
      return TRUE;
    } catch (Exception $e) {
      return FALSE;
    }
  }

  public function upgrade_0003() {
    CRM_CiviMobileAPI_Install_Install::run();

    return TRUE;
  }

  public function upgrade_0004() {
    try {
      $this->executeSqlFile('sql/notification_messages_install.sql');
      return TRUE;
    } catch (Exception $e) {
      return FALSE;
    }
  }

  public function upgrade_0005() {
    try {
      $this->executeSql('ALTER TABLE civicrm_contact_push_notification_messages ADD invoke_contact_id INT(10) UNSIGNED NULL');
    } catch (Exception $e) {}

    return TRUE;
  }

  public function upgrade_0006() {
    try {
      $this->executeSql('ALTER TABLE civicrm_contact_push_notification_messages ADD message_title varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL');
    } catch (Exception $e) {}

    return TRUE;
  }

  public function upgrade_0011() {
    $this->ctx->log->info('Applying update 0011');
    $this->deleteOldMenu();

    return TRUE;
  }

  public function upgrade_0012() {
    CRM_CiviMobileAPI_Install_Install::run();

    return TRUE;
  }

  public function upgrade_0013() {
    CRM_CiviMobileAPI_Install_Install::run();

    return TRUE;
  }

  public function upgrade_0014() {
    (new CRM_CiviMobileAPI_Install_Entity_ApplicationQrCode())->install();

    return TRUE;
  }

  public function upgrade_0015() {
    try {
      $this->executeSqlFile('sql/civimobile_event_payment_info_install.sql');
      $this->executeSql('ALTER TABLE civicrm_contact_push_notification_messages ADD data varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL');
      CRM_CiviMobileAPI_Utils_CustomGroup::delete(CRM_CiviMobileAPI_Install_Entity_CustomGroup::CONTACT_SETTINGS);
      CRM_Core_Invoke::rebuildMenuAndCaches(TRUE);

      return TRUE;
    } catch (Exception $e) {
      return FALSE;
    }
  }

  public function upgrade_0016() {
    try {
      $this->executeSqlFile('sql/create_event_agenda_config.sql');
      $this->executeSqlFile('sql/create_location_venue.sql');
      $this->executeSqlFile('sql/create_event_session.sql');
      $this->executeSqlFile('sql/create_favourite_event_session.sql');
      $this->executeSqlFile('sql/create_event_session_speaker.sql');
      (new CRM_CiviMobileAPI_Install_Entity_CustomGroup())->install();
      (new CRM_CiviMobileAPI_Install_Entity_CustomField())->install();
      $this->executeSql("ALTER TABLE `civicrm_contact_push_notification_messages` CHANGE COLUMN `data` `data` TEXT NULL DEFAULT NULL;");
      CRM_Core_Invoke::rebuildMenuAndCaches(TRUE);

      return TRUE;
    } catch (Exception $e) {
      return FALSE;
    }
  }

  public function upgrade_0017() {
    try {
      $fields = CRM_Core_DAO::executeQuery("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'civicrm_civimobile_location_venue' AND COLUMN_NAME IN ('attached_file_type', 'attached_file_url')")->fetchAll();

      if (count($fields) == 2) {
        $query = CRM_Utils_SQL_Select::from(CRM_CiviMobileAPI_DAO_LocationVenue::getTableName());
        $query->select('id, attached_file_url, attached_file_type');
        $venues = CRM_Core_DAO::executeQuery($query->toSQL())->fetchAll();

        foreach ($venues as $venue) {
          parse_str(parse_url($venue['attached_file_url'], PHP_URL_QUERY), $params);

          $fileDAO = new CRM_Core_DAO_File();
          $fileDAO->uri = $params['filename'];
          $fileDAO->mime_type = $venue['attached_file_type'];
          $fileDAO->upload_date = date('YmdHis');
          $fileDAO->save();

          $entityFileDAO = new CRM_Core_DAO_EntityFile();
          $entityFileDAO->entity_table = 'civicrm_civimobile_location_venue';
          $entityFileDAO->entity_id = $venue['id'];
          $entityFileDAO->file_id = $fileDAO->id;
          $entityFileDAO->save();
        }

        $this->executeSql("ALTER TABLE `civicrm_civimobile_location_venue` DROP COLUMN `attached_file_type`, DROP COLUMN `attached_file_url`;");
      }

      return TRUE;
    } catch (Exception $e) {
      return FALSE;
    }
  }

  public function upgrade_0018() {
    try {
      (new CRM_CiviMobileAPI_Install_Entity_CustomGroup())->install();
      (new CRM_CiviMobileAPI_Install_Entity_CustomField())->install();
    } catch (Exception $e) {
      return FALSE;
    }

    return TRUE;
  }

  public function upgrade_0019() {
    try {
      (new CRM_CiviMobileAPI_Install_Entity_OptionGroup())->install();
      (new CRM_CiviMobileAPI_Install_Entity_OptionValue())->install();
    } catch (Exception $e) {
      return FALSE;
    }

    return TRUE;
  }

  public function upgrade_0020() {
    try {
      (new CRM_CiviMobileAPI_Install_Entity_CustomGroup())->install();
      (new CRM_CiviMobileAPI_Install_Entity_CustomField())->install();
      (new CRM_CiviMobileAPI_Install_Entity_CustomGroup())->enableAll();

      self::setDefaultMobileEventRegistration();
    } catch (Exception $e) {
      return FALSE;
    }

    return TRUE;
  }

  public function upgrade_0021() {
    try {
      $customGroupId = (int) civicrm_api3('CustomGroup', 'getvalue', [
        'name' => CRM_CiviMobileAPI_Install_Entity_CustomGroup::ALLOW_MOBILE_REGISTRATION,
        'return' => 'id'
      ]);

      civicrm_api3('CustomGroup', 'create', [
        'id' => $customGroupId,
        'is_public' => 0
      ]);
    } catch (Exception $e) {
      return FALSE;
    }

    return TRUE;
  }

  public function upgrade_0022() {
    try {
      (new CRM_CiviMobileAPI_Install_Entity_OptionGroup())->install();
      (new CRM_CiviMobileAPI_Install_Entity_OptionValue())->install();
    } catch (Exception $e) {
      return FALSE;
    }

    return TRUE;
  }

  /**
   * Installs scheduled job
   *
   * @throws \Exception
   */
  public function install() {
    CRM_CiviMobileAPI_Install_Install::run();

    $this->executeSqlFile('sql/create_location_venue.sql');
    $this->executeSqlFile('sql/create_event_session.sql');
    $this->executeSqlFile('sql/create_event_agenda_config.sql');
    $this->executeSqlFile('sql/create_favourite_event_session.sql');
    $this->executeSqlFile('sql/create_event_session_speaker.sql');

    CRM_CiviMobileAPI_Settings_Calendar::setCalendarIsAllowToUseCiviCalendarSettings(
      CRM_CiviMobileAPI_Utils_Calendar::isCiviCalendarEnable()
      && CRM_CiviMobileAPI_Utils_Calendar::isCiviCalendarCompatible()
    );

    self::setDefaultMobileEventRegistration();

    Civi::settings()->set('civimobile_auto_update', 1);
  }

  /**
   * Uninstalls scheduled job
   *
   * @throws \CiviCRM_API3_Exception
   */
  public function uninstall() {
    CRM_CiviMobileAPI_Install_Install::uninstall();

    $this->executeSqlFile('sql/drop_event_session_speaker.sql');
    $this->executeSqlFile('sql/drop_favourite_event_session.sql');
    $this->executeSqlFile('sql/drop_event_agenda_config.sql');
    $this->executeSqlFile('sql/drop_event_session.sql');
    $this->executeSqlFile('sql/drop_location_venue.sql');

    $this->uninstallPushNotificationCustomGroup();
    CRM_CiviMobileAPI_Utils_CustomGroup::delete(CRM_CiviMobileAPI_Install_Entity_CustomGroup::CONTACT_SETTINGS);
  }

  /**
   * Run a simple query when a module is enabled.
   *
   * @throws \CiviCRM_API3_Exception
   */
  public function enable() {
    CRM_CiviMobileAPI_Install_Install::enable();
  }

  /**
   * Run a simple query when a module is disabled.
   *
   * @throws \CiviCRM_API3_Exception
   */
  public function disable() {
    CRM_CiviMobileAPI_Install_Install::disable();
  }

  /**
   * Deletes 'push notification' custom group
   */
  private function uninstallPushNotificationCustomGroup() {
    $pushNotificationCustomGroupId = civicrm_api3('CustomGroup', 'get', [
      'return' => "id",
      'name' => "contact_push_notification",
    ]);

    if (isset($pushNotificationCustomGroupId['values']) && !empty($pushNotificationCustomGroupId['values'])) {
      civicrm_api3('CustomGroup', 'delete', [
        'id' => $pushNotificationCustomGroupId,
      ]);
    }
  }

  /**
   * Deletes old menu
   */
  private function deleteOldMenu() {
    $value = ['name' => 'civimobile-settings'];
    CRM_Core_BAO_Navigation::retrieve($value, $navInfo);
    if (!empty($navInfo['id'])) {
      CRM_Core_BAO_Navigation::processDelete($navInfo['id']);
      CRM_Core_BAO_Navigation::resetNavigation();
    }
  }

  private static function setDefaultMobileEventRegistration() {
    $isAllowMobileRegistrationField = "custom_" . CRM_CiviMobileAPI_Utils_CustomField::getId(CRM_CiviMobileAPI_Install_Entity_CustomGroup::ALLOW_MOBILE_REGISTRATION, CRM_CiviMobileAPI_Install_Entity_CustomField::IS_MOBILE_EVENT_REGISTRATION);
    $events = civicrm_api3('Event', 'get', [
      'sequential' => 1,
      'options' => ['limit' => 0]
    ]);

    foreach ($events['values'] as $event) {
      civicrm_api3('CustomValue', 'create', [
        'entity_id' => $event['id'],
        $isAllowMobileRegistrationField => 1
      ]);
    }
  }

}
