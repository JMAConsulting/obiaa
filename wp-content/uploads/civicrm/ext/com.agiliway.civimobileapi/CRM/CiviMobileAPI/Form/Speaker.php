<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

class CRM_CiviMobileAPI_Form_Speaker extends CRM_Core_Form {

  private $speaker;

  public function preProcess() {
    parent::preProcess();
    $participantId = CRM_Utils_Request::retrieve('pid', 'Positive');
    $eventId = CRM_Utils_Request::retrieve('eid', 'Positive');

    if ($this->getAction() == CRM_Core_Action::UPDATE && !CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionToEditSpeaker()) {
      CRM_Core_Error::statusBounce('You do not have all the permissions needed for this page.', '', E::ts('Permission Denied'));
    }

    if (!$participantId) {
      $participantId = $this->_submitValues["pid"];
    }
    if (!$eventId) {
      $eventId = $this->_submitValues["eid"];
    }

    try {
      $this->speaker = civicrm_api3('CiviMobileSpeaker', 'getsingle', [
        'sequential' => 1,
        'participant_id' => $participantId,
        'event_id' => $eventId,
      ]);
    } catch (Exception $e) {
      $url = CRM_Utils_System::url('civicrm/civimobile/event/agenda', http_build_query([
        'reset' => '1',
        'id' => $eventId
      ]));
      CRM_Core_Error::statusBounce('The speaker doesn`t exists.', $url, E::ts('Not Found'));
    }

    $this->assign('speaker', $this->speaker);
    $this->assign('can_edit_speaker', CRM_CiviMobileAPI_Utils_Permission::isEnoughPermissionToEditSpeaker());
  }

  /**
   * Build the form object
   */
  public function buildQuickForm() {
    parent::buildQuickForm();

    $cancelButtonLabel = 'Cancel';
    $cancelURL = CRM_Utils_System::url('civicrm/civimobile/event/agenda', http_build_query([
      'reset' => '1',
      'id' => $this->speaker['event_id']
    ]));

    if ($this->getAction() == CRM_Core_Action::UPDATE || $this->getAction() == CRM_Core_Action::VIEW) {
      $this->add('hidden', 'pid', $this->speaker['participant_id']);
      $this->add('hidden', 'eid', $this->speaker['event_id']);

      $this->add('text', 'first_name', E::ts('First name'), ['class' => 'huge']);
      $this->add('text', 'last_name', E::ts('Last name'), ['class' => 'huge']);
      $this->add('text', 'job_title', E::ts('Position'), ['class' => 'huge']);
      $this->add('textarea', 'participant_bio', E::ts('Bio'), ['class' => 'big']);
      $this->addField('image_URL', ['maxlength' => '255', 'label' => E::ts('Image')]);
      $this->addEntityRef('current_employer_id', 'Company', [
        'create' => TRUE,
        'multiple' => FALSE,
        'api' => ['params' => ['contact_type' => 'Organization']],
      ]);
    }

    if ($this->getAction() == CRM_Core_Action::UPDATE) {

      $buttons[] = [
        'type' => 'upload',
        'name' => E::ts('Save'),
        'isDefault' => TRUE,
      ];
    }

    if ($this->getAction() == CRM_Core_Action::VIEW) {
      $cancelButtonLabel = "Done";
    }

    $buttons[] = [
      'type' => 'cancel',
      'name' => E::ts($cancelButtonLabel),
      'class' => 'cancel',
      'js' => ['onclick' => "
         if( CRM.$('.ui-dialog').length ) {
           var active = 'a.crm-popup';
           CRM.$('#crm-main-content-wrapper').on('crmPopupFormSuccess.crmLivePage', active, CRM.refreshParent, CRM.$('.ui-dialog-titlebar-close').trigger('click'));
         } else {
           window.location.href='{$cancelURL}'; return false;
         }"
      ],
    ];

    $this->addButtons($buttons);
  }

  /**
   * Process the form submission.
   */
  public function postProcess() {
    $inputValues = $this->controller->exportValues($this->_name);
    $session = CRM_Core_Session::singleton();

    if (!empty($inputValues['image_URL'])) {
      CRM_Contact_BAO_Contact::processImageParams($inputValues);
    }

    if ($this->getAction() == CRM_Core_Action::UPDATE) {
      $contactParams = [
        'id' => $this->speaker['contact_id'],
        'first_name' => $inputValues['first_name'],
        'last_name' => $inputValues['last_name'],
        'job_title' => $inputValues['job_title'],
        'employer_id' => $inputValues['current_employer_id']
      ];

      if (!empty($inputValues['image_URL'])) {
        $contactParams['image_URL'] = $inputValues['image_URL'];
      }

      try {
        civicrm_api3('Contact', 'create', $contactParams);
      } catch (Exception $e) {
        CRM_Core_Session::setStatus(E::ts('Contact info wasn`t saved!'), E::ts('Error'), 'error');
      }

      $participantBioFieldName = $customFieldName = "custom_" . CRM_CiviMobileAPI_Utils_CustomField::getId(CRM_CiviMobileAPI_Install_Entity_CustomGroup::AGENDA_PARTICIPANT, CRM_CiviMobileAPI_Install_Entity_CustomField::AGENDA_PARTICIPANT_BIO);

      try {
        civicrm_api3('Participant', 'create', [
          'id' => $this->speaker['participant_id'],
          $participantBioFieldName => $inputValues['participant_bio']
        ]);
      } catch (Exception $e) {
        CRM_Core_Session::setStatus(E::ts('Participant bio wasn`t saved!'), E::ts('Error'), 'error');
      }
    }
    $session->replaceUserContext(CRM_Utils_System::url("civicrm/civimobile/event/agenda", 'reset=1&id=' . $this->speaker['event_id']));
  }

  /**
   * Set defaults for form.
   */
  public function setDefaultValues() {
    $defaults = [];

    if ($this->getAction() == CRM_Core_Action::UPDATE) {
      $defaults['first_name'] = $this->speaker['first_name'];
      $defaults['last_name'] = $this->speaker['last_name'];
      $defaults['job_title'] = $this->speaker['job_title'];
      $defaults['current_employer_id'] = $this->speaker['current_employer_id'];
      $defaults['participant_bio'] = $this->speaker['participant_bio'];
    }

    return $defaults;
  }

  /**
   * AddRules hook
   */
  public function addRules() {
    $this->addFormRule([self::class, 'validateForm']);
  }

  /**
   * Validates form
   *
   * @param $values
   *
   * @return array
   */
  public static function validateForm($values) {
    $errors = [];

    $contactFields = CRM_Contact_DAO_Contact::fields();

    if (strlen($values['first_name']) > $contactFields['first_name']['maxlength']) {
      $errors['first_name'] = "Title length cannot be more than " . $contactFields['first_name']['maxlength'] . " characters.";
    }
    if (strlen($values['last_name']) > $contactFields['last_name']['maxlength']) {
      $errors['last_name'] = "Title length cannot be more than " . $contactFields['last_name']['maxlength'] . " characters.";
    }
    if (strlen($values['job_title']) > $contactFields['job_title']['maxlength']) {
      $errors['job_title'] = "Title length cannot be more than " . $contactFields['job_title']['maxlength'] . " characters.";
    }

    return empty($errors) ? TRUE : $errors;
  }

  /**
   * Explicitly declare the form context.
   */
  public function getDefaultContext() {
    return 'edit';
  }

  /**
   * Explicitly declare the entity api name.
   */
  public function getDefaultEntity() {
    return 'Contact';
  }

}
