<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_Install_Entity_OptionGroup extends CRM_CiviMobileAPI_Install_Entity_EntityBase {

  /**
   * Entity name
   *
   * @var string
   */
  protected $entityName = 'OptionGroup';

  /**
   * OptionGroup name
   *
   * @var string
   */
  const TABS = 'civi_mobile_tabs';

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
        'name' => self::TABS,
        'title' => E::ts('CiviMobile Tabs'),
        'data_type' => 'String',
        'is_reserved' => 1,
        'is_locked' => 1
      ]
    ];
  }

}
