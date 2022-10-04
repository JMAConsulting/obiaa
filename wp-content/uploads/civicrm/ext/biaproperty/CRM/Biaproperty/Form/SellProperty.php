<?php

use CRM_Biaproperty_ExtensionUtil as E;
use Civi\Api4\Activity;
use Civi\Api4\Contact;
use Civi\Api4\Property;
use Civi\Api4\PropertyOwner;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Biaproperty_Form_SellProperty extends CRM_Core_Form {

  /**
   * Property ID
   * @var int
   */
  protected $_id;

  /**
   * Contact ID of the owner that is 'selling' the property
   * @var int
   */
  protected $_cid;

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

    $this->_id = CRM_Utils_Request::retrieve('id', 'Positive', $this, TRUE);
    $this->_cid = CRM_Utils_Request::retrieve('cid', 'Positive', $this, TRUE);
    CRM_Utils_System::setTitle('Sell Property');
  }

  /**
   * This virtual function is used to set the default values of various form
   * elements.
   *
   * @return array|NULL
   *   reference to the array of default values
   */
  public function setDefaultValues() {
    $defaults = [];
    return $defaults;
  }

  public function buildQuickForm() {
    $required = PropertyOwner::get(FALSE)->addWhere('property_id', '=', $this->_id)->execute()->count() === 1 ? TRUE : FALSE;
    $element = $this->addEntityRef('owner_id', E::ts('New Property Owner'), ['placeholder' => '- Select Member (Property Owner) -', 'create' => TRUE, 'api' => [
      'params' => [],
    ]], $required);
    $this->assign('elementNames', ['owner_id']);

    $this->addButtons([
      [
        'type' => 'upload',
        'name' => E::ts('Sell Property'),
        'isDefault' => TRUE,
      ],
      [
        'type' => 'cancel',
        'name' => E::ts('Cancel'),
      ],
    ]);

    $this->addFormRule(['CRM_Biaproperty_Form_SellProperty', 'formRule'], $this);
    parent::buildQuickForm();
  }

  public static function formRule($fields, $files, $self) {
    $errors = [];
    if ($self->_cid == $fields['owner_id']) {
      $errors['owner_id'] = ts('You cannot choose existing property owner. Please select a different property owner.');
    }
    return $errors;
  }

  public function postProcess() {
    $values = $this->controller->exportValues();

    $property = Property::get(FALSE)->addWhere('id', '=', $this->_id)->execute()->first();
    $title = (!empty($property['name'])) ? $property['name'] . ' - ' . $property['property_address'] : $property['property_address'];

    $propertyOwners = PropertyOwner::get(FALSE)
      ->addWhere('property_id', '=', $this->_id)
      ->execute();
    $count = $propertyOwners->count();
    $targetContactID = $sourceRecordID = NULL;

    // if there is a single property owner
    if ($count == 1) {
      $propertyOwner = $propertyOwners->first();
      PropertyOwner::update(FALSE)
        ->addValue('owner_id', $values['owner_id'])
        ->addValue('is_voter', 1)
        ->addWhere('id', '=', $propertyOwner['id'])
        ->execute();
      $targetContactID = $values['owner_id'];
      $sourceRecordID = $propertyOwner['id'];

      // check if the contact is a owner of any property
      $c = PropertyOwner::get(FALSE)
        ->addWhere('owner_id', '=', $this->_cid)
        ->execute()
        ->count();
      // if not then remove the subtype 'Member (Property OWner)'
      if ($c == 0) {
        $contactSubTypes = Contact::get(FALSE)->addWhere('id', '=', $this->_cid)->execute()->first()['contact_sub_type'] ?? [];
        $key = array_search('Members_Property_Owners_', $contactSubTypes);
        unset($contactSubTypes[$key]);
        Contact::update(FALSE)
          ->addValue('contact_sub_type', $contactSubTypes)
          ->addWhere('id', '=', $this->_cid)
          ->execute();
      }
    }
    // if there is more then one property owner
    elseif ($count > 1) {
      // if no property owner chosen then simply delete the property owner record
      if (empty($values['owner_id'])) {
        foreach ($propertyOwners as $propertyOwner) {
          // If the property owner is already an owner we will set it to be the voter.
          if ($propertyOwner['owner_id'] == $this->_cid) {
            PropertyOwner::delete(FALSE)
              ->addWhere('id', '=', $propertyOwner['id'])
              ->execute();
            $contactSubTypes = Contact::get(FALSE)->addWhere('id', '=', $this->_cid)->execute()->first()['contact_sub_type'] ?? [];
            $key = array_search('Members_Property_Owners_', $contactSubTypes);
            unset($contactSubTypes[$key]);
            Contact::update(FALSE)
              ->addValue('contact_sub_type', $contactSubTypes)
              ->addWhere('id', '=', $this->_cid)
              ->execute();
            $targetContactID = $this->_cid;
            $sourceRecordID = $propertyOwner['id'];
          }
        }
      }
      // else simply update the existing property owner record
      else {
        foreach ($propertyOwners as $propertyOwner) {
          if ($propertyOwner['owner_id'] == $this->_cid) {
            PropertyOwner::update(FALSE)
              ->addValue('owner_id', $values['owner_id'])
              ->addValue('is_voter', $propertyOwner['voter_id'])
              ->addWhere('id', '=', $propertyOwner['id'])
              ->execute();
            $targetContactID = $values['owner_id'];
            $sourceRecordID = $propertyOwner['id'];
            break;
          }
        }
      }
    }

    Activity::create(FALSE)
      ->addValue('activity_type_id:name', 'Property sold')
      ->addValue('target_contact_id', $targetContactID)
      ->addValue('assignee_contact_id', $targetContactID)
      ->addValue('source_contact_id', $this->_cid)
      ->addValue('source_record_id', $sourceRecordID)
      ->addValue('status_id:name', 'Completed')
      ->addValue('subject', 'Property sold')
      ->addValue('details', sprintf('Sold property - <a href="%s">%s</a>', CRM_Utils_System::url('/civicrm/biaunits#?pid=' . $this->_id . '&title=' . $title), $title))
      ->addValue('New_Buyer.New_buyer', $values['owner_id'] ?? NULL)
      ->execute();

    $propertyOwners = PropertyOwner::get(FALSE)
      ->addWhere('property_id', '=', $this->_id)
      ->execute();
    CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/contact/view', 'reset=1&cid=' . $this->_cid));
    parent::postProcess();
  }

}
