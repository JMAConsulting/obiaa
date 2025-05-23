<?php

require_once 'civimobileapi.civix.php';
require_once 'lib/PHPQRCode.php';
\PHPQRCode\Autoloader::register();

use Civi\CiviMobileAPI\PushNotification\Entity\ActivityPushNotification;
use Civi\CiviMobileAPI\PushNotification\Entity\CasePushNotification;
use Civi\CiviMobileAPI\PushNotification\Entity\ParticipantPushNotification;
use Civi\CiviMobileAPI\PushNotification\Entity\RelationshipPushNotification;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function civimobileapi_civicrm_config(&$config) {
  _civimobileapi_civix_civicrm_config($config);
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
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function civimobileapi_civicrm_enable() {
  _civimobileapi_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_apiWrappers().
 */
function civimobileapi_civicrm_apiWrappers(&$wrappers, $apiRequest) {
  CRM_CiviMobileAPI_Hook_ApiWrappers::run($wrappers, $apiRequest);
}

/**
 * API hook to disable permission validation
 */
function civimobileapi_civicrm_alterAPIPermissions($entity, $action, &$params, &$permissions) {
  CRM_CiviMobileAPI_Hook_AlterAPIPermissions_MobileRequest::run($entity, $action, $params);
  CRM_CiviMobileAPI_Hook_AlterAPIPermissions_CustomPermissions::run($permissions);
}

/**
 * Integrates Pop-up window to notify that mobile application is available for
 * this website
 */
function civimobileapi_civicrm_pageRun(&$page) {
  CRM_CiviMobileAPI_Hook_Utils::civimobile_add_qr_popup();

  $pageName = $page->getVar('_name');

  if($pageName == 'Civi\Angular\Page\Main') {
    CRM_CiviMobileAPI_Hook_Utils::civimobile_add_generate_description_popup();
  }
  else if ($pageName == 'CRM_Event_Page_EventInfo') {
    CRM_CiviMobileAPI_Hook_PageRun_EventInfo::run();
  }
  else if ($pageName == 'CRM_Event_Page_ManageEvent') {
    CRM_CiviMobileAPI_Hook_PageRun_ManageEvent::run();
  }
}

function civimobileapi_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  CRM_CiviMobileAPI_Hook_Post_Participant::run($op, $objectName, $objectId);
  CRM_CiviMobileAPI_Hook_Post_Individual::run($op, $objectName, $objectId);
  CRM_CiviMobileAPI_Hook_Post_Event::run($op, $objectName, $objectId);

  /**
   * Send push notification if contact or relation contact haves token.
   */
  (new CasePushNotification($op, $objectName, $objectId, $objectRef))->handlePostHook();
  (new ActivityPushNotification($op, $objectName, $objectId, $objectRef))->handlePostHook();
  (new RelationshipPushNotification($op, $objectName, $objectId, $objectRef))->handlePostHook();
  (new ParticipantPushNotification($op, $objectName, $objectId, $objectRef))->handlePostHook();

  CRM_CiviMobileAPI_Hook_Post_Address::run($op, $objectName, $objectId);
  CRM_CiviMobileAPI_Hook_Post_Register::run($op, $objectName, $objectId, $objectRef);
}

function civimobileapi_civicrm_postProcess($formName, &$form) {
  CRM_CiviMobileAPI_Hook_PostProcess_ManageEventRegistration::run($formName, $form);
  CRM_CiviMobileAPI_Hook_PostProcess_ManageEventLocation::run($formName, $form);

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
  CRM_CiviMobileAPI_Hook_Tabset_CiviMobile::run($tabsetName, $tabs, $context);
  CRM_CiviMobileAPI_Hook_Tabset_Agenda::run($tabsetName, $tabs, $context);
}

/**
 * @param $entity
 * @param $clauses
 *
 * @throws \CRM_Core_Exception
 */
function civimobileapi_civicrm_selectWhereClause($entity, &$clauses) {
  CRM_CiviMobileAPI_Hook_SelectWhereClause_Note::run($entity, $clauses);
}

/**
 * Implements hook_civicrm_permission().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_permission/
 *
 * @param $permissionList
 */
function civimobileapi_civicrm_permission(&$permissionList) {
  CRM_CiviMobileAPI_Hook_Permission::run($permissionList);
}

function civimobileapi_civicrm_permission_check($permission, &$granted) {
  if ($permission == 'access CiviCRM' && CRM_Core_Permission::check('CiviMobile backend access') && CRM_CiviMobileAPI_Hook_Utils::is_mobile_request()) {
    $granted = TRUE;
  }

  $session = CRM_Core_Session::singleton();
  $reqHash = CRM_Utils_Request::retrieve('cmbHash', 'String');
  $cmbHash = ($session->get('cmbHash')) ? $session->get('cmbHash') : $reqHash;

  if ($cmbHash) {
    $cmbHashData = CRM_CiviMobileAPI_BAO_CivimobileEventPaymentInfo::getByHash($cmbHash);
    if (!empty($cmbHashData['contact_id']) && ($permission == 'view event info' || $permission == 'register for events' || $permission == 'profile create')) {
      $granted = TRUE;
    }
  }
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
            if (!is_writable_r($dir . "/" . $object)) {
              return FALSE;
            } else {
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
  CRM_CiviMobileAPI_Hook_NavigationMenu::run($menu);
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_buildForm/
 */
function civimobileapi_civicrm_buildForm($formName, &$form) {
  CRM_CiviMobileAPI_Hook_BuildForm_ManageEventRegistration::run($formName, $form);
  CRM_CiviMobileAPI_Hook_BuildForm_ManageEventEventInfo::run($formName, $form);
  CRM_CiviMobileAPI_Hook_BuildForm_EventParticipant::run($formName, $form);
  CRM_CiviMobileAPI_Hook_BuildForm_AddGenerateDescription::run($formName, $form);

  (new CRM_CiviMobileAPI_Hook_BuildForm_Register)->run($formName, $form);
  (new CRM_CiviMobileAPI_Hook_BuildForm_ContributionPayment)->run($formName, $form);

  CRM_CiviMobileAPI_Hook_Utils::civimobile_add_qr_popup();
}

/**
 * Implements hook_civicrm_alterBadge().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterBadge/
 */
function civimobileapi_civicrm_alterBadge(&$labelName, &$label, &$format, &$participant) {
  CRM_CiviMobileAPI_Hook_AlterBadge::run($label, $format);
}

function civimobileapi_civicrm_alterAngular($angular) {
  CRM_CiviMobileAPI_Hook_AlterAngular_EditEmail::run($angular);
}

function civimobileapi_civicrm_alterContent(&$content, $context, $tplName, &$object) {
  CRM_CiviMobileAPI_Hook_AlterContent_AutoGenerateAI::run($content, $context, $tplName, $object);
}

function civimobileapi_civicrm_postSave_civicrm_activity($dao) {
  CRM_CiviMobileAPI_Hook_ApiPost_SaveActivity::run($dao);
}

function civimobileapi_civicrm_preProcess($formName, &$form) {
  CRM_CiviMobileAPI_Hook_Pre_GroupTree::run($formName, $form);
  CRM_CiviMobileAPI_Hook_Pre_ContributionPayment::run($formName, $form);
}
