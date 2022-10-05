<?php

use CRM_Biaproperty_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Biaproperty_Form_ExistingProperty extends CRM_Core_Form {

  protected $_oid;

  /**
   * Preprocess form.
   *
   * This is called before buildForm. Any pre-processing that
   * needs to be done for buildForm should be done here.
   *
   * This is a virtual function and should be redefined if needed.
   */
  public function preProcess() {
    parent::preProcess();

    $this->_action = CRM_Utils_Request::retrieve('action', 'String', $this);
    $this->assign('action', $this->_action);

    $this->_oid = CRM_Utils_Request::retrieve('oid', 'Positive', $this, TRUE);
    CRM_Utils_System::setTitle('Add/Buy Property');
  }


  public function buildQuickForm() {
    $options = [];
    $properties = \Civi\Api4\Property::get(FALSE)
      ->addSelect('id', 'address_id.name', 'address_id.street_address')
      ->addJoin('PropertyOwner AS property_owner', 'LEFT', ['property_owner.property_id', '=', 'id'])
      ->addClause('OR', ['property_owner.owner_id', 'IS NULL'], ['property_owner.owner_id', '!=', $this->_oid])
      ->setLimit(100)
      ->execute();
    foreach ($properties as $property) {
      $options[$property['id']] = $property['address_id.name'] . ' - ' . $property['address_id.street_address'];
    }
    $this->addEntityRef('property_id',  E::ts('Property'), ['create' => TRUE, 'entity' => 'Property', 'api' => [
      'params' => ['options' => ['limit' => 100], 'owner_id' => ['!=' => $this->_oid]]]], TRUE);
    $this->addYesNo('is_voter', ts('Vote?'), TRUE);
    $this->setDefaults(['is_voter' => 0]);
    $this->assign('elementNames', ['property_id', 'is_voter']);

    $this->addButtons([
      [
        'type' => 'upload',
        'name' => E::ts('Submit'),
        'isDefault' => TRUE,
      ],
      [
        'type' => 'cancel',
        'name' => E::ts('Cancel')
      ]
    ]);
    $this->addFormRule([__CLASS__, 'formRule'], $this);
    parent::buildQuickForm();
  }

  public static function formRule($fields, $files, $self) {
    $errors = [];
    if (!empty($fields['property_id']) && !empty($self->_oid)) {
      $count = \Civi\Api4\PropertyOwner::get(FALSE)
      ->addWhere('property_id', '=', $fields['property_id'])
      ->addWhere('owner_id', '=', $self->_oid)
      ->execute()
      ->count();
      if ($count > 0) {
        $errors['property_id'] = ts('You are already an owner for the selected property.');
      }
    }

    return $errors;
  }

  public function postProcess() {
    $values = $this->controller->exportValues();
    $contact = \Civi\Api4\Contact::get(FALSE)
      ->addSelect('contact_type:label', 'first_name', 'middle_name', 'last_name')
      ->addWhere('id', '=', $this->_oid)->execute()->first();

    // change contact type of property owner if individual to organization
    if ($contact['contact_type:label'] == 'Individual') {
      \Civi\Api4\Contact::update(FALSE)
        ->addValue('contact_type', 'Organization')
        ->addValue('contact_sub_type', ['Members_Property_Owners_'])
        ->addValue('organization_name', implode(' ' , [$contact['first_name'], $contact['middle_name'], $contact['last_name']]))
        ->addWhere('id', '=', $this->_oid)
        ->execute();

      \Civi\Api4\Activity::create(FALSE)
        ->addValue('activity_type_id:name', 'Contact type changed')
        ->addValue('target_contact_id', $this->_oid)
        ->addValue('assignee_contact_id', $this->_oid)
        ->addValue('source_contact_id', CRM_Core_Session::getLoggedInContactID())
        ->addValue('status_id:name', 'Completed')
        ->addValue('subject', 'Propery owner contact type changed from Individual to Organization')
        ->execute();
    }
    else {
      $contact = \Civi\Api4\Contact::get(FALSE)
          ->addWhere('id', '=', $this->_oid)
          ->execute()->first();
      $subTypes = (array) $contact['contact_sub_type'];
      $contactType = $contact['contact_type'];
      $contactUpdate = \Civi\Api4\Contact::update()->addWhere('id', '=', $this->_oid);
      if (!in_array('Members_Property_Owners_', $subTypes)) {
        $subTypes[] = 'Members_Property_Owners_';
        $contactUpdate->addValue('contact_sub_type', $subTypes);
        $contactUpdate->execute();
      }
    }

    if (!empty($values['is_voter']) && $values['is_voter'] == 1) {
      // if the owner is going to be primary voter then clear all previous is_voter values to 0/No
      CRM_Core_DAO::executeQuery('UPDATE civicrm_property_owner SET is_voter = 0 WHERE is_voter = 1 AND property_id = ' . $values['property_id']);
    }
    else {
      $propetyOwnerCheck = \Civi\Api4\PropertyOwner::get()->addWhere('property_id', '=', $values['property_id'])->execute()->count();
      // If there is no other owners set ensure that this owner is set to be the voter
      $values['is_voter'] = $propertyOwnerCheck === 0 ? 1 : $values['is_voter'];
    }
    \Civi\Api4\PropertyOwner::create(FALSE)
      ->addValue('property_id', $values['property_id'])
      ->addValue('owner_id', $this->_oid)
      ->addValue('is_voter', $values['is_voter'])
      ->execute();

    parent::postProcess();
  }

}
