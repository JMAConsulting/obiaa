<?php

require_once 'civimobileapi.civix.php';
require_once 'lib/PHPQRCode.php';
\PHPQRCode\Autoloader::register();

use Civi\CiviMobileAPI\PushNotification\Entity\ActivityPushNotification;
use Civi\CiviMobileAPI\PushNotification\Entity\CasePushNotification;
use Civi\CiviMobileAPI\PushNotification\Entity\ParticipantPushNotification;
use Civi\CiviMobileAPI\PushNotification\Entity\RelationshipPushNotification;
use CRM_CiviMobileAPI_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function civimobileapi_civicrm_config(&$config) {
  _civimobileapi_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function civimobileapi_civicrm_xmlMenu(&$files) {
  _civimobileapi_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function civimobileapi_civicrm_install() {
  _civimobileapi_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function civimobileapi_civicrm_postInstall() {
  _civimobileapi_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function civimobileapi_civicrm_uninstall() {
  _civimobileapi_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function civimobileapi_civicrm_enable() {
  _civimobileapi_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function civimobileapi_civicrm_disable() {
  _civimobileapi_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CR
 *   MDOC/hook_civicrm_upgrade
 */
function civimobileapi_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _civimobileapi_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function civimobileapi_civicrm_managed(&$entities) {
  _civimobileapi_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function civimobileapi_civicrm_caseTypes(&$caseTypes) {
  _civimobileapi_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function civimobileapi_civicrm_angularModules(&$angularModules) {
  _civimobileapi_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function civimobileapi_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _civimobileapi_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_apiWrappers().
 */
function civimobileapi_civicrm_apiWrappers(&$wrappers, $apiRequest) {
  if ($apiRequest['entity'] == 'Contact' && ($apiRequest['action'] == 'getsingle' || $apiRequest['action'] == 'get')) {
    $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Contact();
  }
  elseif ($apiRequest['entity'] == 'Address' && $apiRequest['action'] == 'get') {
    $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Address();
  }
  elseif ($apiRequest['entity'] == 'Activity') {
    if ($apiRequest['action'] == 'getsingle') {
      $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Activity_GetSingle();
    }

    if ($apiRequest['action'] == 'get') {
      $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Activity_Get();
    }
  }
  elseif ($apiRequest['entity'] == 'Case' && ($apiRequest['action'] == 'getsingle' || $apiRequest['action'] == 'get')) {
    $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Case();
  }
  elseif ($apiRequest['entity'] == 'Event' && ($apiRequest['action'] == 'getsingle' || $apiRequest['action'] == 'get')) {
    $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Event();
  }
  elseif ($apiRequest['entity'] == 'Job' && $apiRequest['action'] == 'version_check') {
    $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Job_VersionCheck();
  }
  elseif ($apiRequest['entity'] == 'Note' && $apiRequest['action'] == 'get') {
    $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Note();
  }
  elseif ($apiRequest['entity'] == 'Contribution' && $apiRequest['action'] == 'get') {
    $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Contribution();
  }
  elseif ($apiRequest['entity'] == 'Membership') {
    if ($apiRequest['action'] == 'create') {
      $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Membership_Create();
    }

    if (is_mobile_request()) {
      if ($apiRequest['action'] == 'getsingle' || $apiRequest['action'] == 'get') {
        $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Membership_Get();
      }
    }
  }
  elseif ($apiRequest['entity'] == 'Relationship' && $apiRequest['action'] == 'get') {
    $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Relationship_Get();
  }
  elseif ($apiRequest['entity'] == 'Participant') {
    if ($apiRequest['action'] == 'create') {
      $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Participant_Create();
    }
    elseif ($apiRequest['action'] == 'get') {
      $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Participant_Get();
    }
  }
  elseif ($apiRequest['entity'] == 'GroupContact') {
    if ($apiRequest['action'] == 'get') {
      $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_GroupContact_Get();
    }
    elseif ($apiRequest['action'] == 'create') {
      $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_GroupContact_Create();
    }
  }
  elseif ($apiRequest['entity'] == 'EntityTag') {
    if ($apiRequest['action'] == 'get') {
      $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_EntityTag_Get();
    }
  } elseif ($apiRequest['entity'] == 'Survey') {
    if ($apiRequest['action'] == 'getsingle') {
      $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_Survey_Getsingle();
    }
  }
  elseif ($apiRequest['entity'] == 'ContributionPage') {
    if ($apiRequest['action'] == 'get') {
      $wrappers[] = new CRM_CiviMobileAPI_ApiWrapper_ContributionPage();
    }
  }
}

/**
 * API hook to disable permission validation
 */
function civimobileapi_civicrm_alterAPIPermissions($entity, $action, &$params, &$permissions) {
  if (is_mobile_request()) {
    civimobileapi_secret_validation();
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

  $permissions['civi_mobile_favourite_event_session']['create'] = ['view Agenda'];
  $permissions['civi_mobile_agenda_config']['create'] = ['access CiviCRM', 'view my contact', 'access CiviEvent', 'view Agenda'];
  $permissions['civi_mobile_agenda_config']['get'] = ['view Agenda'];
  $permissions['civi_mobile_speaker']['get'] = ['view Agenda'];
  $permissions['civi_mobile_participant']['get'] = ['access CiviEvent', 'view event info', 'view event participants'];
  $permissions['civi_mobile_venue']['get'] = ['view Agenda'];
  $permissions['civi_mobile_event_session']['get'] = ['view Agenda'];
  $permissions['civi_mobile_venue_attach_file']['delete'] = ['access CiviCRM', 'view my contact', 'access CiviEvent', 'view Agenda'];
  $permissions['civi_mobile_participant_payment_link']['get'] = ['view event info', 'register for events'];
}

/**
 * Integrates Pop-up window to notify that mobile application is available for
 * this website
 */
function civimobileapi_civicrm_pageRun(&$page) {
  civimobile_add_qr_popup();
  $pageName = $page->getVar('_name');
  if ($pageName == 'CRM_Event_Page_EventInfo') {
    if (CRM_CiviMobileAPI_Utils_Agenda_AgendaConfig::isAgendaActiveForEvent(CRM_Utils_Request::retrieve('id', 'Positive'))) {
      $sessionsValues = CRM_CiviMobileAPI_Utils_Agenda_SessionSchedule::getEventSessionsValues(CRM_Utils_Request::retrieve('id', 'Positive'));
      if (!empty($sessionsValues)) {
        $smarty = CRM_Core_Smarty::singleton();
        $smarty->assign('session_schedule_data', json_encode(CRM_CiviMobileAPI_Utils_Agenda_SessionSchedule::getSessionScheduleData(CRM_Utils_Request::retrieve('id', 'Positive'))));
        CRM_Core_Region::instance('page-body')->add([
          'template' => CRM_CiviMobileAPI_ExtensionUtil::path() . '/templates/CRM/CiviMobileAPI/Form/SessionSchedule.tpl',
        ]);
      }
    }
  }

  if($pageName == 'CRM_Event_Page_ManageEvent'){
    $smarty = CRM_Core_Smarty::singleton();
    foreach ($smarty->_tpl_vars["rows"] as $key => &$row) {
      if ($key == 'tab') {
        continue;
      }
      $row['is_agenda'] = CRM_CiviMobileAPI_Utils_Agenda_AgendaConfig::isAgendaActiveForEvent($row['id']);
    }
  }
}

/**
 * Adds qr popup to page-body
 */
function civimobile_add_qr_popup() {
  if (empty($_GET['snippet'])) {
    if (Civi::settings()->get('civimobile_is_allow_public_website_url_qrcode') == 1 || CRM_Core_Permission::check('administer CiviCRM')) {

      $params = [
        'apple_link' => 'https://itunes.apple.com/us/app/civimobile/id1404824793?mt=8',
        'google_link' => 'https://play.google.com/store/apps/details?id=com.agiliway.civimobile',
        'civimobile_logo' => CRM_CiviMobileAPI_ExtensionUtil::url('/img/civimobile_logo.svg'),
        'app_store_img' => CRM_CiviMobileAPI_ExtensionUtil::url('/img/app-store.png'),
        'google_play_img' => CRM_CiviMobileAPI_ExtensionUtil::url('/img/google-play.png'),
        'civimobile_phone_img' => CRM_CiviMobileAPI_ExtensionUtil::url('/img/civimobile-phone.png'),
        'font_directory' => CRM_CiviMobileAPI_ExtensionUtil::url('/font'),
        'qr_code_link' => CRM_CiviMobileAPI_Install_Entity_ApplicationQrCode::getPath(),
        'small_popup_background_color' => '#e8ecf0',
        'advanced_popup_background_color' => '#e8ecf0',
        'button_background_color' => '#5589b7',
        'button_text_color' => 'white',
        'description_text' => 'Congratulations, your CiviCRM supports <b>CiviMobile</b> application now. You can download the mobile application at AppStore or Google PlayMarket.',
        'description_text_color' => '#3b3b3b',
        'is_showed_popup' => empty($_COOKIE["civimobile_popup_close"]),
      ];

      CRM_CiviMobileAPI_Utils_HookInvoker::qrCodeBlockParams($params);

      $params['is_showed_popup'] = $params['is_showed_popup'] ? 1 : 0;

      CRM_Core_Smarty::singleton()->assign($params);
      CRM_Core_Region::instance('page-body')->add([
        'template' => CRM_CiviMobileAPI_ExtensionUtil::path() . '/templates/CRM/CiviMobileAPI/popup.tpl',
      ]);
    }
  }
}

/**
 * Adds hook civimobile_secret_validation, which you can use to add own secret
 * validation
 */
function civimobileapi_secret_validation() {
  $nullObject = CRM_Utils_Hook::$_nullObject;
  $validated = TRUE;
  CRM_Utils_Hook::singleton()
    ->commonInvoke(1, $validated, $nullObject, $nullObject, $nullObject, $nullObject, $nullObject, 'civimobile_secret_validation', '');
  if (!$validated) {
    http_response_code(404);
    exit;
  }
}

/**
 * Checks if this is request from mobile application
 */
function is_mobile_request() {
  $null = NULL;

  return CRM_Utils_Request::retrieve('civimobile', 'Int', $null, FALSE, FALSE, 'GET');
}

function civimobileapi_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  if ($objectName == 'Participant') {
    if ($op == 'create') {
      CRM_CiviMobileAPI_Utils_QRcode::generateQRcode($objectId);
    }

    if ($op == 'delete') {
      CRM_CiviMobileAPI_BAO_EventSessionSpeaker::deleteAllSpeakersByParticipantId($objectId);
    }
  }

  if ($objectName == 'Individual' && $op == 'edit') {
    try {
      $contact = CRM_Contact_BAO_Contact::findById($objectId);
      $apiKey = $contact->api_key;
    } catch (\CiviCRM_API3_Exception $e) {
      $apiKey = NULL;
    }
    if (!empty($apiKey) && CRM_CiviMobileAPI_Utils_Contact::isBlockedApp($objectId) == 1) {
      CRM_CiviMobileAPI_Utils_Contact::logoutFromMobile($objectId);
    }
  }

  if ($objectName == 'Event' && $op == 'create') {
    $qrcodeCheckinEvent = CRM_Utils_Request::retrieve('default_qrcode_checkin_event', 'String');
    $eventId = $objectId;

    CRM_CiviMobileAPI_Utils_IsAllowMobileEventRegistrationField::setValue($eventId, 1);

    if ($qrcodeCheckinEvent) {
      CRM_CiviMobileAPI_Utils_EventQrCode::setQrCodeToEvent($eventId);
    }
  }

  /**
   * Send push notification if contact or relation contact haves token.
   */
  (new CasePushNotification($op, $objectName, $objectId, $objectRef))->handlePostHook();
  (new ActivityPushNotification($op, $objectName, $objectId, $objectRef))->handlePostHook();
  (new RelationshipPushNotification($op, $objectName, $objectId, $objectRef))->handlePostHook();
  (new ParticipantPushNotification($op, $objectName, $objectId, $objectRef))->handlePostHook();

  /**
   * Rebuild venue after changing event location data.
   */
  if ($objectName == 'Address') {
    $locBlocks = civicrm_api3('LocBlock', 'get', [
      'address_id' => $objectId,
      'options' => ['limit' => 0],
    ])['values'];

    foreach ($locBlocks as $locBlock) {
      CRM_CiviMobileAPI_Utils_Agenda_Venue::rebuildVenueGeoDate($locBlock['id']);
    }
  }

  CRM_CiviMobileAPI_Hook_Post_Register::run($op, $objectName, $objectId, $objectRef);
}

function civimobileapi_civicrm_postProcess($formName, &$form) {
  if ($formName == 'CRM_Event_Form_ManageEvent_Registration' && ($form->getAction() == CRM_Core_Action::UPDATE || $form->getAction() == CRM_Core_Action::ADD)) {
    $values = $form->exportValues();

    $newValue = isset($values['civi_mobile_is_event_mobile_registration']) ? 1 : 0;
    $eventId = $form->_id;
    CRM_CiviMobileAPI_Utils_IsAllowMobileEventRegistrationField::setValue($eventId, $newValue);
  }

  //TODO: think about how to remove all venues on hook_pre
  /**
   * Removes all venues in EventSession if loc_block_id was changed.
   */
  if ($formName == 'CRM_Event_Form_ManageEvent_Location') {
    try {
      $event = CRM_Event_BAO_Event::findById($form->_id);
      if(!empty($event->loc_block_id) && $event->loc_block_id != $form->getVar('_oldLocBlockId')){
        CRM_CiviMobileAPI_BAO_EventSession::deleteAllVenues($event->id);
      }
    } catch (Exception $e) {}
  }

  /**
   * This hook run only when delete Activity from WEB
   */
  $action = $form->getAction();
  if ($action == CRM_Core_Action::DELETE) {
    $action = "delete";
  }

  $objectId = null;
  if ($formName == 'CRM_Case_Form_Activity' && $action == 'delete') {
    $objectId = (isset($form->_caseId[0])) ? $form->_caseId[0] : null;
  }

  if ($formName == 'CRM_Event_Form_Participant' && $action == 'create') {
    setcookie("civimobile_speaker_id", $form->_id, 0, '/');
  }
}

function civimobileapi_civicrm_alterMailParams(&$params, $context) {
  CRM_CiviMobileAPI_Hook_AlterMailParams_EventOnlineReceipt::run($params, $context);
  CRM_CiviMobileAPI_Hook_AlterMailParams_EventOfflineReceipt::run($params, $context);
}

function civimobileapi_civicrm_pre($op, $objectName, $id, &$params) {
  /**
   * Send notification in delete process
   */
  (new CasePushNotification($op, $objectName, $id, $params))->handlePreHook();
  (new ActivityPushNotification($op, $objectName, $id, $params))->handlePreHook();
  (new ParticipantPushNotification($op, $objectName, $id, $params))->handlePreHook();
}

/**
 * @param $tabsetName
 * @param $tabs
 * @param $context
 */
function civimobileapi_civicrm_tabset($tabsetName, &$tabs, $context) {
  if ($tabsetName == 'civicrm/contact/view' && !empty($context['contact_id'])) {
    if (CRM_Contact_BAO_Contact::getContactType($context['contact_id']) == 'Individual' &&
       (CRM_Core_Permission::check('administer CiviCRM') || CRM_Core_Session::singleton()->getLoggedInContactID() == $context['contact_id'])
    ) {
      $tabs[] = [
        'id' => 'civimobile',
        'url' => CRM_Utils_System::url('civicrm/civimobile/dashboard', 'reset=1&cid=' . $context['contact_id']),
        'title' => E::ts('CiviMobile'),
        'weight' => 99,
      ];
    }
  }
  if ($tabsetName == 'civicrm/event/manage') {
    $isActiveAgenda = !empty($context['event_id']) ? CRM_CiviMobileAPI_Utils_Agenda_AgendaConfig::isAgendaActiveForEvent($context['event_id']) : false;
    $tabs['agenda'] = [
      'title' => E::ts('Agenda'),
      'url' => 'civicrm/civimobile/event/agenda',
      'link' => CRM_Utils_System::url('civicrm/civimobile/event/agenda', (isset($context['event_id']) ? 'id=' . $context['event_id'] : NULL)),
      'valid' => $isActiveAgenda,
      'active' => true,
      'current' => true,
      'class' => 'ajaxForm',
      'field' => 'is_agenda'
    ];
  }

}

/**
 * @param $entity
 * @param $clauses
 *
 * @throws \CRM_Core_Exception
 */
function civimobileapi_civicrm_selectWhereClause($entity, &$clauses) {
  if ($entity == 'Note') {
    if ($json = CRM_Utils_Request::retrieve('json', 'String')) {
      $params = json_decode($json, TRUE);

      if (!empty($params['entity_table']) && $params['entity_table'] == 'civicrm_note') {
        unset($clauses['id']);
      }
    }
  }
}

/**
 * Implements hook_civicrm_permission().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_permission/
 *
 * @param $permissionList
 */
function civimobileapi_civicrm_permission(&$permissionList) {
  $permissionsPrefix = 'CiviCRM : ';

  $permissionList[CRM_CiviMobileAPI_Utils_Permission::CAN_CHECK_IN_ON_EVENT] = [
    $permissionsPrefix . CRM_CiviMobileAPI_Utils_Permission::CAN_CHECK_IN_ON_EVENT,
    E::ts("It means User can only update Participant status to 'Registered' or 'Attended'. Uses by QR Code."),
  ];

  $permissionList['view Agenda'] = [
    $permissionsPrefix . 'view Agenda',
    E::ts("View Agenda."),
  ];

  $permissionList['see tags'] = [
    $permissionsPrefix . 'see tags',
    E::ts("It means the User can see the tags he belongs to."),
  ];

  $permissionList['see groups'] = [
    $permissionsPrefix . 'see groups',
    E::ts("It means the User can see the groups he belongs to"),
  ];

}

if (!function_exists('is_writable_r')) {

  /**
   * @param string $dir directory path.
   *
   * @return bool
   */
  function is_writable_r($dir) {
    if (is_dir($dir)) {
      if (is_writable($dir)) {
        $objects = scandir($dir);

        foreach ($objects as $object) {
          if ($object != "." && $object != "..") {
            if (!is_writable_r($dir."/".$object)) {
              return FALSE;
            }
            else {
              continue;
            }
          }
        }

        return TRUE;
      } else {
        return FALSE;
      }
    } else if (file_exists($dir)) {
      return is_writable($dir);
    }

    return false;
  }
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @param $menu
 */
function civimobileapi_civicrm_navigationMenu(&$menu) {
  $civiMobile = [
    'name' => E::ts('CiviMobile'),
    'permission' => 'administer CiviCRM',
    'operator' => NULL,
    'separator' => NULL,
  ];
  _civimobileapi_civix_insert_navigation_menu($menu, 'Administer/', $civiMobile);

  $civiMobileSettings = [
    'name' => E::ts('CiviMobile Settings'),
    'url' => 'civicrm/civimobile/settings',
    'permission' => 'administer CiviCRM',
    'operator' => NULL,
    'separator' => NULL,
  ];
  _civimobileapi_civix_insert_navigation_menu($menu, 'Administer/CiviMobile/', $civiMobileSettings);

  $civiMobileCalendarSettings = [
    'name' => E::ts('CiviMobile Calendar Settings'),
    'url' => 'civicrm/civimobile/calendar/settings',
    'permission' => 'administer CiviCRM',
    'operator' => NULL,
    'separator' => NULL,
  ];
  _civimobileapi_civix_insert_navigation_menu($menu, 'Administer/CiviMobile/', $civiMobileCalendarSettings);

  $civiMobileEventLocations = [
    'name' => E::ts('CiviMobile Event Locations'),
    'url' => 'civicrm/civimobile/event-locations',
    'permission' => 'administer CiviCRM',
    'operator' => NULL,
    'separator' => NULL,
  ];
  _civimobileapi_civix_insert_navigation_menu($menu, 'Administer/CiviEvent/', $civiMobileEventLocations);

  $civiMobileSettings = [
    'name' => E::ts('CiviMobile Checklist'),
    'url' => 'civicrm/civimobile/checklist',
    'permission' => 'administer CiviCRM',
    'operator' => NULL,
    'separator' => NULL,
  ];
  _civimobileapi_civix_insert_navigation_menu($menu, 'Administer/CiviMobile/', $civiMobileSettings);

  $civiMobileTabs = [
    'name' => E::ts('CiviMobile Tabs'),
    'url' => 'civicrm/admin/options/civi_mobile_tabs',
    'permission' => 'administer CiviCRM',
    'operator' => NULL,
    'separator' => NULL,
  ];
  _civimobileapi_civix_insert_navigation_menu($menu, 'Administer/CiviMobile/', $civiMobileTabs);
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_buildForm/
 */
function civimobileapi_civicrm_buildForm($formName, &$form) {
  if ($formName == 'CRM_Event_Form_ManageEvent_Registration' && $form->getAction() == CRM_Core_Action::UPDATE) {
    $form->addElement('checkbox',
        'civi_mobile_is_event_mobile_registration',
        ts('Is allow mobile registration?')
    );

    if ($form->getAction() == CRM_Core_Action::UPDATE) {
      $eventId = $form->_id;
      $elementValue = CRM_CiviMobileAPI_Utils_IsAllowMobileEventRegistrationField::getValue($eventId);
      $form->setDefaults([
        'civi_mobile_is_event_mobile_registration' => $elementValue,
      ]);
    }

    CRM_Core_Region::instance('page-header')->add([
        'template' => CRM_CiviMobileAPI_ExtensionUtil::path() . '/templates/CRM/CiviMobileAPI/Block/IsAllowMobileRegistration.tpl'
    ]);
  }

  if ($formName == 'CRM_Event_Form_ManageEvent_EventInfo') {
    if ($form->getAction() == CRM_Core_Action::ADD){
      $templatePath = realpath(dirname(__FILE__)."/templates");

      $form->add('checkbox', 'default_qrcode_checkin_event', E::ts('When generating QR Code tokens, use this Event'));
      CRM_Core_Region::instance('page-body')->add([
        'template' => "{$templatePath}/qrcode-checkin-event-options.tpl"
      ]);

      CRM_Core_Region::instance('page-body')->add([
        'style' => '.custom-group-' . CRM_CiviMobileAPI_Install_Entity_CustomGroup::QR_USES . ' { display:none;}'
      ]);
    }

    CRM_Core_Region::instance('page-body')->add([
      'style' => '.custom-group-' . CRM_CiviMobileAPI_Install_Entity_CustomGroup::ALLOW_MOBILE_REGISTRATION . ' { display:none;}'
    ]);
  }

  if ($formName == 'CRM_Event_Form_Participant' && $form->getAction() == CRM_Core_Action::ADD) {
    $elementName = 'send_receipt';
    if ($form->elementExists($elementName)) {
      $element = $form->getElement($elementName);
      $element->setValue(1);
    }
  }

  (new CRM_CiviMobileAPI_Hook_BuildForm_Register)->run($formName, $form);
  (new CRM_CiviMobileAPI_Hook_BuildForm_ContributionPayment)->run($formName, $form);
  civimobile_add_qr_popup();
}

/**
 * Implements hook_civicrm_alterBadge().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterBadge/
 */
function civimobileapi_civicrm_alterBadge( &$labelName, &$label, &$format, &$participant ) {
  $qrCodeCustomFieldName = "custom_" . CRM_CiviMobileAPI_Utils_CustomField::getId(CRM_CiviMobileAPI_Install_Entity_CustomGroup::QR_CODES, CRM_CiviMobileAPI_Install_Entity_CustomField::QR_IMAGE);
  if (isset($format['values'][$qrCodeCustomFieldName])) {
    $link = $format['values'][$qrCodeCustomFieldName];
    $label->printImage($link, '100', '0' , 30, 30);

    //hide label
    if (!empty($format['token'])) {
      foreach ($format['token'] as $key => $token) {
        if ($token['token'] == '{participant.' . $qrCodeCustomFieldName . '}') {
          $format['token'][$key]['value'] =  '';
        }
      }
    }
  }
}

function civimobileapi_civicrm_alterContent(&$content, $context, $tplName, &$object) {
  if($context == "form") {
    if($tplName == "CRM/Event/Form/ManageEvent/Location.tpl") {
      if(CRM_CiviMobileAPI_Utils_Agenda_AgendaConfig::isAgendaActiveForEvent($object->_id)) {
        $content = "<div class='status'>If you change the location for an event, all venues will be deleted from sessions.</div>" . $content;
      }
    }
    if($tplName == "CRM/Event/Form/ManageEvent/EventInfo.tpl") {
      if(CRM_CiviMobileAPI_Utils_Agenda_AgendaConfig::isAgendaActiveForEvent($object->_id)) {
        $content = "<div class='status'>If you change the date, some event sessions may stop displaying.</div>" . $content;
      }
    }
  }
}

function civimobileapi_civicrm_postSave_civicrm_activity($dao) {
  if (isset($_POST['hasVoted']) && !is_null($dao->status_id)) {
    $hasVoted = CRM_Utils_String::strtoboolstr(CRM_Utils_Type::escape($_POST['hasVoted'], 'String'));
    $gotvCustomFieldName = 'custom_' . CRM_CiviMobileAPI_Utils_CustomField::getId(CRM_CiviMobileAPI_Install_Entity_CustomGroup::SURVEY,CRM_CiviMobileAPI_Install_Entity_CustomField::SURVEY_GOTV_STATUS);

    civicrm_api3('Activity', 'create', [
      $gotvCustomFieldName => $hasVoted,
      'id' => $dao->id
    ]);
  }
}

function civimobileapi_civicrm_preProcess($formName, &$form) {
  if ($formName == 'CRM_Activity_Form_Activity' || $formName == 'CRM_Custom_Form_CustomDataByType') {
    $groupTree = $form->getVar('_groupTree');

    if (!empty($groupTree)) {
      foreach ($groupTree as $key => $customGroup) {
        if ($customGroup['name'] == CRM_CiviMobileAPI_Install_Entity_CustomGroup::SURVEY) {
          unset($groupTree[$key]);
        }
      }
      $form->setVar('_groupTree', $groupTree);
    }
  }
  if ($formName == 'CRM_Contribute_Form_Contribution_Main') {
    (new CRM_CiviMobileAPI_Hook_Pre_ContributionPayment)->run();
  }
}
