<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_Form_CiviAiSettings extends CRM_Core_Form {

  public function preProcess() {
    parent::preProcess();

    $civiAiMessage = E::ts('To use ChatGPT you must register at <a href="https://platform.openai.com/account/api-keys"  target="_blank">openai.com</a> and create your own Secret Key');

    $this->assign('civiAiMessage', $civiAiMessage);

    CRM_Core_Resources::singleton()->addStyleFile('com.agiliway.civimobileapi', 'css/civimobileapiSettings.css', 200, 'html-header');
  }

  public function addRules() {
    $params = $this->exportValues();

    if (!empty($params['_qf_CiviAiSettings_submit'])) {
      $this->addFormRule([CRM_CiviMobileAPI_Form_CiviAiSettings::class, 'validateToken']);
    }
  }


  public static function validateToken($values) {
    $errors = [];
    $tokenFieldName = 'civimobile_openai_secret_key';

    if (empty($values[$tokenFieldName]) || empty(trim($values[$tokenFieldName]))) {
      $errors[$tokenFieldName] = E::ts('Fields cannot be empty.');
      return empty($errors) ? TRUE : $errors;
    }

    return empty($errors) ? TRUE : $errors;
  }


  public function buildQuickForm() {
    parent::buildQuickForm();

    $this->addElement('password', 'civimobile_openai_secret_key', E::ts('Secret key'));

    $buttons = [
      [
        'type' => 'submit',
        'name' => E::ts('Save settings'),
        'isDefault' => TRUE,
      ],
      [
        'type' => 'cancel',
        'name' => E::ts('Cancel'),
      ]
    ];

    $this->addButtons($buttons);
  }


  public function postProcess() {
    $params = $this->exportValues();

    if (!empty($params['_qf_CiviAiSettings_submit'])) {
      Civi::settings()->set('civimobile_openai_secret_key', $params['civimobile_openai_secret_key']);
      CRM_Core_Session::singleton()->setStatus(E::ts('Secret key updated'), E::ts('CiviMobile Settings'), 'success');
    }
  }


  public function setDefaultValues() {
    $defaults = [];

    $defaults['civimobile_openai_secret_key'] = Civi::settings()->get('civimobile_openai_secret_key');

    return $defaults;
  }
}