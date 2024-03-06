<?php

use CRM_Biaproperty_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Contact_Form_Edit_Property {

  protected $_id;

  protected $_oid;

  protected $_property;

  public function getDefaultEntity() {
    return 'Property';
  }

  public function getDefaultEntityTable() {
    return 'civicrm_property';
  }

  public function getEntityId() {
    return $this->_id;
  }

  public static function buildQuickForm(&$form, $addressBlockCount = NULL, $sharing = TRUE, $inlineEdit = FALSE) {
    $form->addEntityRef('property_id',  E::ts('Property'), [
      'create' => TRUE,
          'entity' => 'Property',
          'api' => [
            'params' => [
              'options' => ['limit' => 100],
            ]
          ]
    ], TRUE);
    $form->addYesNo('is_voter', E::ts('Vote?'), TRUE);
    $form->setDefaults(['is_voter' => 0]);
    $elements = [
      'property_id' => 'Property',
      'is_voter' => 'Vote?',
    ];

    $url = CRM_Utils_System::url('civicrm/property/form', ['reset' => 1, 'action' => 'add', 'context' => 'create'], FALSE, NULL, FALSE, FALSE, TRUE);
     CRM_Core_Resources::singleton()->addScript(
      "CRM.$(function($) {
        CRM.config.entityRef.links.Property = [
         {label: 'Add Property', url: '{$url}'}
        ];
     });
    ");
    $form->addFormRule([__CLASS__, 'formRule'], $form);
    $form->assign('elements', $elements);
  }

    public static function formRule($fields, $files = [], $self = NULL) {
      $errors = [];
      if (!empty($fields['property_id']) && $fields['is_voter'] == 0) {
        $count = \Civi\Api4\PropertyOwner::get(FALSE)
        ->addWhere('property_id', '=', $fields['property_id'])
        ->execute()
        ->count();
        if ($count == 0) {
          $errors['is_voter'] = ts('Since you have created a new property, please select "Yes". You can change the voting member later when you add other property owners');
        }
      }

      return $errors;
    }

}
