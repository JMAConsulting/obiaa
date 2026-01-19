<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_Form_CalendarSettings extends CRM_Core_Form {

  /**
   * Build the form object.
   *
   * @return void
   */
  public function buildQuickForm() {
    parent::buildQuickForm();
    $settings = $this->getFormSettings();
    $this->addSettingsElements($settings);
    $this->assign('elementNames', $this->getRenderableElementNames());

    if (!CRM_CiviMobileAPI_Utils_Calendar::isCiviCalendarCompatible()) {
      $this->assign('synchronizationNotice', E::ts('The CiviCRM has a CiviCalendar installed, but its version is not enough to work with CiviMobileAPI. We recommend updating your calendar to the 3.4.x version or latest.'));
    } elseif (CRM_CiviMobileAPI_Utils_Calendar::isCiviCalendarInstalled() && !CRM_CiviMobileAPI_Utils_Calendar::isActivateCiviCalendarSettings()) {
      $this->assign('synchronizationNotice', E::ts('CiviCalendar and CiviMobile calendar are not synchronized! This may cause different info is shown on the calendar in CiviMobile app. It is recommended to set “Synchronize with CiviCalendar” flag to keep both calendars synchronized.'));
    } elseif (CRM_CiviMobileAPI_Utils_Calendar::isCiviCalendarInstalled() && CRM_CiviMobileAPI_Utils_Calendar::isActivateCiviCalendarSettings()) {
      $this->assign('synchronizationNotice', E::ts("CiviCalendar and CiviMobile calendar are  synchronized! You can change settings in <a %1>CiviCalendar Settings</a>", [1 => 'href="' . CRM_Utils_System::url('civicrm/admin/calendar') . '"']));
    }

    $this->addButtons([
      [
        'type' => 'submit',
        'name' => E::ts('Submit'),
        'isDefault' => TRUE,
      ],
      [
        'type' => 'cancel',
        'name' => E::ts('Cancel'),
      ],
    ]);
  }

  private function addSettingsElements($settings) {
    foreach ($settings as $name => $setting) {
      if (!isset($setting['html_type'])) {
        continue;
      }

      switch ($setting['html_type']) {
        case 'Text':
          $this->addElement('text', $name, E::ts($setting['description']), $setting['html_attributes']);
          break;
        case 'Checkbox':
          $this->addElement('checkbox', $name, E::ts($setting['description']));
          break;
        case 'Select':
          $options = $this->getSelectOptions($name, $setting);
          $select = $this->addElement('select', $name, E::ts($setting['description']), $options, $setting['html_attributes']);
          if (!empty($setting['multiple'])) {
            $select->setMultiple(TRUE);
          }
          break;
      }
    }
  }

  private function getSelectOptions($name, $setting) {
    if (isset($setting['option_values'])) {
      return $setting['option_values'];
    }
    if (isset($setting['pseudoconstant'])) {
      $options = civicrm_api4('Setting', 'getFields', [
        'loadOptions' => TRUE,
        'where' => [
          [
            'name',
            '=',
            CRM_CiviMobileAPI_Settings_Calendar_CiviMobile::getPrefix() . $name,
          ],
        ],
        'checkPermissions' => FALSE,
      ])->first();

      return $options['options'] ?? [];
    }
    return [];
  }

  /**
   * @throws \CRM_Core_Exception
   */
  public function postProcess() {
    parent::postProcess();
    $params = $this->exportValues();
    $settings = $this->getFormSettings(TRUE);

    foreach ($settings as &$setting) {
      $setting = ($setting['html_type'] == 'Checkbox') ? FALSE : NULL;
    }

    $settingsToSave = array_merge($settings, array_intersect_key($params, $settings));
    $this->saveSetting($settingsToSave);
    CRM_Core_Session::singleton()
      ->setStatus(E::ts('Configuration Updated'), E::ts('CiviMobile Calendar Settings'), 'success');
    CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/civimobile/calendar/settings'));
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames() {
    $elementNames = [];

    foreach ($this->_elements as $element) {
      $label = $element->getLabel();

      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }

    return $elementNames;
  }

  /**
   * Get the settings we are going to allow to be set on this form.
   *
   * @param bool $metadata
   *
   * @return array
   * @throws \CRM_Core_Exception
   */
  function getFormSettings($metadata = TRUE) {
    $settings = civicrm_api3('setting', 'getfields', ['filters' => CRM_CiviMobileAPI_Settings_Calendar_CiviMobile::getFilter()]);

    $nonPrefixedSettings = [];

    if (!empty($settings['values'])) {
      foreach ($settings['values'] as $name => $values) {
        if ($metadata) {
          $nonPrefixedSettings[CRM_CiviMobileAPI_Settings_Calendar_CiviMobile::getName($name, FALSE)] = $values;
        } else {
          $nonPrefixedSettings[CRM_CiviMobileAPI_Settings_Calendar_CiviMobile::getName($name, FALSE)] = NULL;
        }
      }
    }
    if (!CRM_CiviMobileAPI_Utils_Calendar::isCiviCalendarInstalled() || !CRM_CiviMobileAPI_Utils_Calendar::isCiviCalendarCompatible()) {
      unset($nonPrefixedSettings['synchronize_with_civicalendar']);
    }

    $components = civicrm_api3('Setting', 'getvalue', ['name' => "enable_components"]);

    if (!in_array('CiviCase', $components)) {
      unset($nonPrefixedSettings['case_types']);
    }
    if (!in_array('CiviEvent', $components)) {
      unset($nonPrefixedSettings['event_types']);
    }

    return $nonPrefixedSettings;
  }

  function setDefaultValues() {
    return CRM_CiviMobileAPI_Settings_Calendar_CiviMobile::get(array_keys($this->getFormSettings(FALSE))) ?: [];
  }

  /**
   * Save settings
   *
   * @param $settings
   *
   * @throws \CRM_Core_Exception
   */
  private function saveSetting($settings) {
    $prefixedSettings = [];

    foreach ($settings as $name => $value) {
      $prefixedSettings[CRM_CiviMobileAPI_Settings_Calendar_CiviMobile::getName($name, TRUE)] = !empty($value) ? $value : NULL;
    }

    if ($prefixedSettings) {
      civicrm_api3('setting', 'create', $prefixedSettings);
    }
  }

}
