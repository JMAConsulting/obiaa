<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_Form_CiviAiSettings extends CRM_Core_Form {

  public function preProcess() {
    parent::preProcess();

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
    $modelFieldName = 'civimobile_openai_model';

    if (empty($values[$tokenFieldName]) || empty(trim($values[$tokenFieldName]))) {
      $errors[$tokenFieldName] = E::ts('Fields cannot be empty.');
    }

    if (empty($values[$modelFieldName]) || empty(trim($values[$modelFieldName]))) {
      $errors[$modelFieldName] = E::ts('Fields cannot be empty.');
    }

    return empty($errors) ? TRUE : $errors;
  }

  public function buildQuickForm() {
    parent::buildQuickForm();

    $this->addElement('password', 'civimobile_openai_secret_key', E::ts('Secret key'));
    $this->addElement('text', 'civimobile_openai_model', E::ts('Model'));

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
      Civi::settings()->set('civimobile_openai_model', $params['civimobile_openai_model']);
      CRM_Core_Session::singleton()->setStatus(E::ts('AI settings updated'), E::ts('CiviAI Settings'), 'success');
    }
  }

  public function setDefaultValues() {
    $defaults = [];

    $defaults['civimobile_openai_secret_key'] = Civi::settings()->get('civimobile_openai_secret_key');
    $defaults['civimobile_openai_model'] = Civi::settings()->get('civimobile_openai_model');

    return $defaults;
  }
}