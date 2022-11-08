<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_Install_Entity_OptionValue extends CRM_CiviMobileAPI_Install_Entity_EntityBase {

  /**
   * Entity name
   *
   * @var string
   */
  protected $entityName = 'OptionValue';

  /**
   * OptionValue name
   *
   * @var string
   */
  const TAB_CALENDAR = 'civi_mobile_tab_calendar';
  const TAB_EVENTS = 'civi_mobile_tab_events';
  const TAB_ACTIVITIES = 'civi_mobile_tab_activities';
  const TAB_CONTACTS = 'civi_mobile_tab_contacts';
  const TAB_CASES = 'civi_mobile_tab_cases';
  const TAB_RELATIONSHIPS = 'civi_mobile_tab_relationships';
  const TAB_MEMBERSHIPS = 'civi_mobile_tab_memberships';
  const TAB_CONTRIBUTIONS = 'civi_mobile_tab_contributions';
  const TAB_NOTES = 'civi_mobile_tab_notes';
  const TAB_GROUPS = 'civi_mobile_tab_groups';
  const TAB_TAGS = 'civi_mobile_tab_tags';
  const TAB_SURVEYS = 'civi_mobile_tab_surveys';

  /**
   * Params for checking Entity existence
   *
   * @var array
   */
  protected $entitySearchParamNameList = ['name', 'option_group_id'];

  /**
   * Sets entity Param list
   */
  protected function setEntityParamList() {
    $this->entityParamList = [
      [
        'option_group_id' => CRM_CiviMobileAPI_Install_Entity_OptionGroup::TABS,
        'label' => E::ts('Calendar'),
        'name' => self::TAB_CALENDAR,
        'value' => self::TAB_CALENDAR,
        'weight' => 1,
      ],
      [
        'option_group_id' => CRM_CiviMobileAPI_Install_Entity_OptionGroup::TABS,
        'label' => E::ts('Events'),
        'name' => self::TAB_EVENTS,
        'value' => self::TAB_EVENTS,
        'weight' => 2,
      ],
      [
        'option_group_id' => CRM_CiviMobileAPI_Install_Entity_OptionGroup::TABS,
        'label' => E::ts('Cases'),
        'name' => self::TAB_CASES,
        'value' => self::TAB_CASES,
        'weight' => 3,
      ],
      [
        'option_group_id' => CRM_CiviMobileAPI_Install_Entity_OptionGroup::TABS,
        'label' => E::ts('Activities'),
        'name' => self::TAB_ACTIVITIES,
        'value' => self::TAB_ACTIVITIES,
        'weight' => 4,
      ],
      [
        'option_group_id' => CRM_CiviMobileAPI_Install_Entity_OptionGroup::TABS,
        'label' => E::ts('Contacts'),
        'name' => self::TAB_CONTACTS,
        'value' => self::TAB_CONTACTS,
        'weight' => 5,
      ],
      [
        'option_group_id' => CRM_CiviMobileAPI_Install_Entity_OptionGroup::TABS,
        'label' => E::ts('Relationships'),
        'name' => self::TAB_RELATIONSHIPS,
        'value' => self::TAB_RELATIONSHIPS,
        'weight' => 6,
      ],
      [
        'option_group_id' => CRM_CiviMobileAPI_Install_Entity_OptionGroup::TABS,
        'label' => E::ts('Memberships'),
        'name' => self::TAB_MEMBERSHIPS,
        'value' => self::TAB_MEMBERSHIPS,
        'weight' => 7,
      ],
      [
        'option_group_id' => CRM_CiviMobileAPI_Install_Entity_OptionGroup::TABS,
        'label' => E::ts('Contributions'),
        'name' => self::TAB_CONTRIBUTIONS,
        'value' => self::TAB_CONTRIBUTIONS,
        'weight' => 8,
      ],
      [
        'option_group_id' => CRM_CiviMobileAPI_Install_Entity_OptionGroup::TABS,
        'label' => E::ts('Notes'),
        'name' => self::TAB_NOTES,
        'value' => self::TAB_NOTES,
        'weight' => 9,
      ],
      [
        'option_group_id' => CRM_CiviMobileAPI_Install_Entity_OptionGroup::TABS,
        'label' => E::ts('Groups'),
        'name' => self::TAB_GROUPS,
        'value' => self::TAB_GROUPS,
        'weight' => 10,
      ],
      [
        'option_group_id' => CRM_CiviMobileAPI_Install_Entity_OptionGroup::TABS,
        'label' => E::ts('Tags'),
        'name' => self::TAB_TAGS,
        'value' => self::TAB_TAGS,
        'weight' => 11,
      ],
      [
        'option_group_id' => CRM_CiviMobileAPI_Install_Entity_OptionGroup::TABS,
        'label' => E::ts('Surveys'),
        'name' => self::TAB_SURVEYS,
        'value' => self::TAB_SURVEYS,
        'weight' => 12,
      ],
    ];
  }
}
