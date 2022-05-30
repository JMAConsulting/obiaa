<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_Install_Entity_CustomGroup extends CRM_CiviMobileAPI_Install_Entity_EntityBase {

  /**
   * Custom Group name
   *
   * @var string
   */
  const QR_USES = 'civi_mobile_qr_uses';
  const QR_CODES = 'civi_mobile_qr_codes';
  const CONTACT_SETTINGS = 'contact_settings';
  const PUBLIC_INFO = 'civi_mobile_public_info';
  const AGENDA_PARTICIPANT = 'civi_mobile_agenda_participant';
  const SURVEY = 'civi_mobile_survey';
  const ALLOW_MOBILE_REGISTRATION = 'civi_mobile_allow_registration';

  /**
   * Entity name
   *
   * @var string
   */
  protected $entityName = 'CustomGroup';

  /**
   * Params for checking Entity existence
   *
   * @var array
   */
  protected $entitySearchParamNameList = ['name'];

  /**
   * Sets entity Param list
   */
  protected function setEntityParamList() {
    $this->entityParamList = [
      [
        'name' => self::QR_USES,
        'title' => E::ts('Qr options'),
        'extends' => 'Event',
        'is_public' => 0,
        'is_reserved' => 1
      ],
      [
        'name' => self::AGENDA_PARTICIPANT,
        'title' => E::ts('Agenda'),
        'extends' => 'Participant',
        'is_public' => 0,
        'is_reserved' => 1
      ],
      [
        'name' => self::QR_CODES,
        'title' => E::ts('Qr codes'),
        'extends' => 'Participant',
        'is_public' => 0,
        'is_reserved' => 1
      ],
      [
        'name' => self::PUBLIC_INFO,
        'title' => E::ts('Public Info'),
        'extends' => 'Participant',
        'is_public' => 0,
        'is_reserved' => 1
      ],
      [
        'name' => self::SURVEY,
        'title' => E::ts('Survey`s additional info'),
        'extends' => 'Activity',
        'is_public' => 0,
        'is_reserved' => 1
      ],
      [
        'name' => self::ALLOW_MOBILE_REGISTRATION,
        'title' => E::ts('Allow Online registration in CiviMobile'),
        'extends' => 'Event',
        'is_public' => 0,
        'style' => 'Inline',
        'collapse_display' => 1,
        'is_reserved' => 1
      ],
    ];
  }

  /**
   * Disables by id
   *
   * @param $entityId
   */
  protected function disable($entityId) {
    CRM_Core_BAO_CustomGroup::setIsActive((int) $entityId, 0);
  }

  /**
   * Enables by id
   *
   * @param $entityId
   */
  protected function enable($entityId) {
    CRM_Core_BAO_CustomGroup::setIsActive((int) $entityId, 1);
  }

}
