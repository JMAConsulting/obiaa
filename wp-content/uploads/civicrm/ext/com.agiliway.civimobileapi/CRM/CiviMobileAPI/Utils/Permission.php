<?php

/**
 * Class provide permission helper methods
 */
class CRM_CiviMobileAPI_Utils_Permission {

  /**
   * CiviCRM Permission. It mean User can only update Participant status to 'register' or 'attended'
   *
   * @var string
   */
  const CAN_CHECK_IN_ON_EVENT = 'can check in on event';

  /**
   * Check if user can manage Participant
   *
   * @param $eventCreatorId
   *
   * @return bool
   */
  public static function isUserCanManageParticipant($eventCreatorId) {
    $loginContactId = CRM_CiviMobileAPI_Utils_Contact::getCurrentContactId();
    if (!empty($eventCreatorId) && $eventCreatorId == $loginContactId) {
      return true;
    }

    if (CRM_Core_Permission::check('administer CiviCRM')) {
      return true;
    }

    return false;
  }

  /**
   * Validates if enough permission for change Participant status
   * from 'Register' to 'Attended' or vice versa
   *
   * @return bool
   */
  public static function isEnoughPermissionForChangingParticipantStatuses() {
    if (CRM_Core_Permission::check('administer CiviCRM')) {
      return true;
    }

    if (CRM_Core_Permission::check(CRM_CiviMobileAPI_Utils_Permission::CAN_CHECK_IN_ON_EVENT) || CRM_Core_Permission::check('edit all events')) {
      return true;
    }

    return false;
  }

  /**
   * Validates if enough permission for view my tickets
   *
   * @return bool
   */
  public static function isEnoughPermissionForViewMyTickets() {
    if (CRM_Core_Permission::check('administer CiviCRM')) {
      return true;
    }

    if (CRM_Core_Permission::check('access CiviCRM')
      && CRM_Core_Permission::check('access CiviEvent')
      && CRM_Core_Permission::check('view event info')
    ) {
      return true;
    }

    return false;
  }

  /**
   * Is enough permission for create participant with payment
   *
   * @return bool
   */
  public static function isEnoughPermissionForCreateParticipantWithPayment() {
    //TODO
    return true;
  }

  /**
   * Is enough permission for delete ContactGroup entity
   *
   * @return bool
   */
  public static function isEnoughPermissionForDeleteContactGroup() {
    if (CRM_Core_Permission::check('administer CiviCRM')) {
      return true;
    }

    if (CRM_Core_Permission::check('access CiviCRM')
      && CRM_Core_Permission::check('edit all contacts')
      && CRM_Core_Permission::check('view my contact')
    ) {
      return true;
    }

    return false;
  }

  /**
   * Is enough permission for get available ContactGroups in create select
   *
   */
  public static function isEnoughPermissionForGetAvailableContactGroup() {
    if (CRM_Core_Permission::check('administer CiviCRM')) {
      return true;
    }

    if (CRM_Core_Permission::check('access CiviCRM')
        && (CRM_Core_Permission::check('edit my contact')
          || CRM_Core_Permission::check('view all contacts')
          || CRM_Core_Permission::check('view my contact')
          || CRM_Core_Permission::check('edit all contacts')
        )
    ) {
      return true;
    }

    return false;
  }

  /**
   * Is enough permission tag structure
   */
  public static function isEnoughPermissionForGetTagStructure() {
    if (CRM_Core_Permission::check('administer CiviCRM')) {
      return true;
    }

    if (CRM_Core_Permission::check('access CiviCRM')
        && (CRM_Core_Permission::check('edit my contact')
          || CRM_Core_Permission::check('view all contacts')
          || CRM_Core_Permission::check('view my contact')
          || CRM_Core_Permission::check('edit all contacts')
        )
    ) {
      return true;
    }

    return false;
  }

  /**
   * Gets anonymous permissions
   */
  public static function getAnonymous() {
    try {
      $viewAllEvent = CRM_Core_Permission::check('view event info');
      $viewEventParticipants = CRM_Core_Permission::check('view event participants');
      $registerForEvents = CRM_Core_Permission::check('register for events');
      $editEventParticipants = CRM_Core_Permission::check('edit event participants');
      $profileCreate = CRM_Core_Permission::check('profile create');
      $accessUploadedFiles = CRM_Core_Permission::check('access uploaded files');
      $viewAgenda = CRM_Core_Permission::check('view Agenda');
      $signPetition = CRM_Core_Permission::check('sign CiviCRM Petition');
      $profileView = CRM_Core_Permission::check('profile view');
      $accessAllCustomData = CRM_Core_Permission::check('access all custom data');
      $accessCiviContribute = CRM_Core_Permission::check('access CiviContribute');
      $makeOnlineContributions = CRM_Core_Permission::check('make online contributions');
      $accessCiviCrm = CRM_Core_Permission::check('access CiviCRM');
    } catch (Exception $e) {
      return [];
    }

    return [
      'view_public_event' => $viewAllEvent ? 1 : 0,
      'register_for_public_event' => $registerForEvents && $viewAllEvent && $profileCreate ? 1 : 0,
      'view_public_participant' => $viewAllEvent && $viewEventParticipants ? 1 : 0,
      'edit_public_participant' => $viewEventParticipants && $viewAllEvent && $editEventParticipants ? 1 : 0,
      'access_uploaded_files' => $accessUploadedFiles ? 1 : 0,
      'view_agenda' => $viewAgenda ? 1 : 0,
      'view_petition' => $signPetition && $profileView && $accessAllCustomData ? 1 : 0,
      'sign_petition' => $signPetition && $profileCreate && $accessAllCustomData ? 1 : 0,
      'view_public_donation' => $accessCiviContribute && $makeOnlineContributions ? 1 : 0,
      'access_civicrm' => $accessCiviCrm ? 1 : 0,
    ];
  }

  /**
   * Is enough permissions for getting EventSession
   */
  public static function isEnoughPermissionForGetEventSession() {
    if (CRM_Core_Permission::check('view Agenda')) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Is enough permissions for creating EventSession
   */
  public static function isEnoughPermissionForCreateEventSession() {
    if (CRM_Core_Permission::check('access CiviCRM')
      && CRM_Core_Permission::check('view my contact')
      && CRM_Core_Permission::check('access CiviEvent')
      && CRM_Core_Permission::check('view Agenda')
    ) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Is enough permissions for deleting EventSession
   */
  public static function isEnoughPermissionForDeleteEventSession() {
    if (CRM_Core_Permission::check('access CiviCRM')
      && CRM_Core_Permission::check('view my contact')
      && CRM_Core_Permission::check('access CiviEvent')
      && CRM_Core_Permission::check('view Agenda')
    ) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Is enough permissions for getting EventVenues
   */
  public static function isEnoughPermissionForGetEventVenues() {
    if (CRM_Core_Permission::check('view Agenda')) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Is enough permissions for creating EventVenues
   */
  public static function isEnoughPermissionForCreateEventVenues() {
    if (CRM_Core_Permission::check('access CiviCRM')
      && CRM_Core_Permission::check('view my contact')
      && CRM_Core_Permission::check('access CiviEvent')
      && CRM_Core_Permission::check('view Agenda')
    ) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Is enough permissions for deleting EventVenues
   */
  public static function isEnoughPermissionForDeleteEventVenues() {
    if (CRM_Core_Permission::check('access CiviCRM')
      && CRM_Core_Permission::check('view my contact')
      && CRM_Core_Permission::check('access CiviEvent')
      && CRM_Core_Permission::check('view Agenda')
    ) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Is enough permissions for getting AgendaConfig
   */
  public static function isEnoughPermissionForGetAgendaConfig() {
    if (CRM_Core_Permission::check('view Agenda')) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Is enough permissions for creating AgendaConfig
   */
  public static function isEnoughPermissionForCreateAgendaConfig() {
    if (CRM_Core_Permission::check('access CiviCRM')
      && CRM_Core_Permission::check('view my contact')
      && CRM_Core_Permission::check('access CiviEvent')
      && CRM_Core_Permission::check('view Agenda')
    ) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Is enough permissions for getting Speakers in Agenda
   */
  public static function isEnoughPermissionForGetSpeaker() {
    if (CRM_Core_Permission::check('view Agenda')) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Is enough permissions to set favourite EventSession
   */
  public static function isEnoughPermissionToSetFavouriteEventSession() {
    if (CRM_Core_Permission::check('view Agenda')) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Is enough permissions for editing Speaker
   */
  public static function isEnoughPermissionToEditSpeaker() {
    if (CRM_Core_Permission::check('access CiviCRM')
      && CRM_Core_Permission::check('access CiviEvent')
      && CRM_Core_Permission::check('view Agenda')
      && CRM_Core_Permission::check('access all custom data')
      && CRM_Core_Permission::check('edit event participants')
      && CRM_Core_Permission::check('edit all contacts')
    ) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Is enough permission to get surveys list
   */
  public static function isEnoughPermissionToGetSurveysList() {
    if (CRM_Core_Permission::check('administer CiviCRM')) {
      return true;
    }

    if (CRM_Core_Permission::check('administer CiviCampaign')
      || CRM_Core_Permission::check('manage campaign')
      || CRM_Core_Permission::check('reserve campaign contacts')
      || CRM_Core_Permission::check('release campaign contacts')
      || CRM_Core_Permission::check('interview campaign contacts')
      || CRM_Core_Permission::check('gotv campaign contacts')
      || CRM_Core_Permission::check('sign CiviCRM Petition')
    ) {
      return true;
    }

    return false;
  }

  /**
   * Is enough permission to change Interviewer
   */
  public static function isEnoughPermissionToChangeInterviewer() {
    if (CRM_Core_Permission::check('administer CiviCRM')) {
      return true;
    }

    if (CRM_Core_Permission::check('administer CiviCampaign')) {
      return true;
    }

    return false;
  }

  /**
   * Is enough permission to get respondents
   */
  public static function isEnoughPermissionToGetRespondents() {
    if (CRM_Core_Permission::check('administer CiviCRM')
      || CRM_Core_Permission::check('manage campaign')) {
      return true;
    }

    if (CRM_Core_Permission::check('interview campaign contacts')
      || CRM_Core_Permission::check('gotv campaign contacts')
      || CRM_Core_Permission::check('release campaign contacts')
    ) {
      return true;
    }

    return false;
  }

  /**
   * Is enough permission to reserve respondents
   */
  public static function isEnoughPermissionToReserveRespondents() {
    if (CRM_Core_Permission::check('administer CiviCRM')
      || CRM_Core_Permission::check('manage campaign')) {
      return true;
    }

    if (CRM_Core_Permission::check('reserve campaign contacts')
      && CRM_Core_Permission::check('interview campaign contacts')
    ) {
      return true;
    }

    return false;
  }

  /**
   * Is enough permission to view respondent details
   */
  public static function isEnoughPermissionToViewRespondentDetails() {
    if (CRM_Core_Permission::check('administer CiviCRM')
      || CRM_Core_Permission::check('administer CiviCampaign')) {
      return true;
    }

    if (CRM_Core_Permission::check('profile view')
      && CRM_Core_Permission::check('interview campaign contacts')
    ) {
      return true;
    }

    return false;
  }

  /**
   * Is enough permission to edit respondent details
   */
  public static function isEnoughPermissionToEditRespondentDetails() {
    if (CRM_Core_Permission::check('administer CiviCRM')
      || CRM_Core_Permission::check('administer CiviCampaign')) {
      return true;
    }

    if (CRM_Core_Permission::check('profile create')
      && CRM_Core_Permission::check('interview campaign contacts')
    ) {
      return true;
    }

    return false;
  }

  /**
   * Is enough permission to release respondents
   */
  public static function isEnoughPermissionToReleaseRespondents() {
    if (CRM_Core_Permission::check('administer CiviCRM')
      || CRM_Core_Permission::check('administer CiviCampaign')) {
      return true;
    }

    if (CRM_Core_Permission::check('release campaign contacts')
      && CRM_Core_Permission::check('interview campaign contacts')
    ) {
      return true;
    }

    return false;
  }

  /**
   * Is enough permission to GOTV respondents
   */
  public static function isEnoughPermissionToGotvRespondents() {
    if (CRM_Core_Permission::check('administer CiviCRM')
      || CRM_Core_Permission::check('administer CiviCampaign')) {
      return true;
    }

    if (CRM_Core_Permission::check('gotv campaign contacts')
      && CRM_Core_Permission::check('interview campaign contacts')
    ) {
      return true;
    }

    return false;
  }

  /**
   * Is enough permission to view petition answers
   */
  public static function isEnoughPermissionToViewPetitionAnswers() {
    if (CRM_Core_Permission::check('administer CiviCRM')) {
      return true;
    }

    if (CRM_Core_Permission::check('profile view')) {
      return true;
    }

    return false;
  }

  /**
   * Is enough permission to sign petition
   */
  public static function isEnoughPermissionToSignPetition() {
    if (CRM_Core_Permission::check('administer CiviCRM')) {
      return true;
    }

    if (CRM_Core_Permission::check('profile create')
      && CRM_Core_Permission::check('access all custom data')
      && CRM_Core_Permission::check('sign CiviCRM Petition')
    ) {
      return true;
    }

    return false;
  }

  /**
   * Is enough permission to view petition
   */
  public static function isEnoughPermissionToViewPetition() {
    if (CRM_Core_Permission::check('administer CiviCRM')) {
      return true;
    }

    if (CRM_Core_Permission::check('profile view')
      && CRM_Core_Permission::check('access all custom data')
      && CRM_Core_Permission::check('sign CiviCRM Petition')
    ) {
      return true;
    }

    return false;
  }
}
