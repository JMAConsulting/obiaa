<?php

use CRM_Biaproperty_ExtensionUtil as E;
use Civi\Api4\Address;
use Civi\Api4\Property;
use Civi\Api4\UnitBusiness;
use Civi\Api4\Unit;
use Civi\Api4\Activity;
use Civi\Api4\Contact;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Biaproperty_Form_Unit extends CRM_Core_Form {

  protected $_id;

  protected $_pid;

  protected $_bid;

  protected $_unit;

  public function getDefaultEntity() {
    return 'Unit';
  }

  public function getDefaultEntityTable() {
    return 'civicrm_unit';
  }

  public function getEntityId() {
    return $this->_id;
  }

  public function getDefaultContext() {
    return 'create';
  }

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
    $session = CRM_Core_Session::singleton();
    $this->_id = CRM_Utils_Request::retrieve('id', 'Positive', $this, FALSE);
    $this->_bid = CRM_Utils_Request::retrieve('bid', 'Positive', $this, FALSE);
    $context = CRM_Utils_Request::retrieve('context', 'Alphanumeric', $this, FALSE);
    if (empty($this->_id)) {
      $this->_pid = CRM_Utils_Request::retrieve('pid', 'Positive', $this, TRUE);
      if (!empty($this->_pid)) {
        $property = Property::get()->addWhere('id', '=', $this->_pid)->execute()->first();
        $session->replaceUserContext(CRM_Utils_System::url('civicrm/biaunits') . '#?pid=' . $this->_pid . '&title=' . (!empty($property['name']) ? $property['name'] . ' - ' : '') . $property['property_address']);
      }
      else {
        $session->replaceUserContext(CRM_Utils_System::url('civicrm/contact/view', ['cid' => $this->_bid, 'reset' => 1, 'selectedChild' => 'afsearchUnit1']));
      }
    }
    CRM_Utils_System::setTitle('Add Unit');
    if ($this->_id) {
      $this->_unit = civicrm_api4('Unit', 'get', [
        'select' => ['*', 'file.uri', 'file.mime_type', 'address.street_unit', 'address.street_address', 'address.city', 'address.state_province_id:label', 'address.postal_code'],
        'where' => [['id', '=', $this->_id]],
        'join' => [['File AS file', 'LEFT'], ['Address AS adddress', 'INNER']],
        'limit' => 1])->first();
      $this->_pid = $this->_unit['property_id'];
      CRM_Utils_System::setTitle(E::ts('Edit Unit %1'), [1 => [(!empty($this->_unit['address.street_unit']) ? '#' . $this->_unit['address.street_unit'] . ' - ' : '') . $this->_unit['address.street_address']]]);
      $this->assign('unit', $this->_unit);
      if ($context === 'propertyView') {
        $property = Property::get()->addWhere('id', '=', $this->_pid)->execute()->first();
        $session->replaceUserContext(CRM_Utils_System::url('civicrm/biaunits') . '#?pid=' . $this->_pid . '&title=' . (!empty($property['name']) ? $property['name'] . ' - ' : '') . $property['property_address']);
      }
      elseif ($context === 'contactUnit') {
        $session->replaceUserContext(CRM_Utils_System::url('civicrm/contact/view', ['cid' => $this->_bid, 'reset' => 1, 'selectedChild' => 'afsearchUnit1']));
      }
      else {
        $session->replaceUserContext(CRM_Utils_System::url('civicrm/unit/form', ['id' => $this->getEntityId(), 'action' => 'update']));
      }
    }

    if (!empty($this->_bid)) {
      $subTypes = Contact::get(FALSE)->addSelect('contact_sub_type:label')->addWhere('id', '=', $this->_bid)->execute()->first()['contact_sub_type:label'];
      if (in_array('BIA Staff', $subTypes) || in_array('Government Staff/Member', $subTypes)) {
        CRM_Core_Error::statusBounce(E::ts('You cannot choose business contact of type Staff or Government Staff/Member'));
      }
    }
  }


  public function buildQuickForm() {
    if ($this->_pid && $this->_action == CRM_Core_Action::DELETE) {
      if (Unit::get(FALSE)->addWhere('property_id', '=', $this->_pid)->execute()->count() == 1) {
        CRM_Core_Error::statusBounce(E::ts('You cannot delete this sole property unit.'));
      }
    }
    $this->assign('id', $this->getEntityId());
    $this->add('hidden', 'id');
    $this->assign('propertyID', $this->_pid);
    $this->assign('addressAutocomplete', TRUE);
    $addressElements = [];
    if ($this->_pid >= 0 && $this->_action != CRM_Core_Action::DELETE) {
      $element = $this->addEntityRef('property_id',  E::ts('Property'), [
        'create' => TRUE,
        'entity' => 'Property',
        'api' => [
          'params' => [
            'options' => ['limit' => 100],
          ]
        ]
      ], TRUE);
      if ($this->_pid != 0) {$element->freeze();}
      $this->assign('noproperty', ($this->_pid === 0));
    }
    if ($this->_pid != 0) {
      if (empty($this->_id)) {
        $this->assign('addressAutocomplete', FALSE);
        $addressElements = [
          'street_address' => E::ts('Business Mailing Address'),
          'street_unit' => E::ts('Business Mailing Unit/Suite'),
          'city' => E::ts('City'),
          'postal_code' => E::ts('Postal Code'),
          'country_id' => E::ts('Country'),
          'state_province_id' => E::ts('Province'),
        ];
        foreach ($addressElements as $element => $label) {
          if ($element == 'country_id') {
            $this->add('select', $element, $label, CRM_Core_PseudoConstant::country(), TRUE);
          }
          elseif ($element == 'state_province_id') {
            $this->addChainSelect($element, ['label' => $label, 'required' => TRUE]);
          }
          else {
            $isRequired = $element === 'street_unit' ? FALSE : TRUE;
            $this->add('text', $element, $label, NULL, $isRequired);
          }
        }
      }
    }
    if ($this->_action != CRM_Core_Action::DELETE ) {
      $elements = [
        'address_id' => E::ts('Unit Address'),
        'unit_size' => E::ts('Size (Sq Ft)'),
        'unit_price' => E::ts('Price per Sq Ft'),
        'unit_status' => E::ts('Status'),
        'mls_listing_link' => E::ts('MLS Listing Link'),
        'unit_location' => E::ts('Location (Ground Fl, Floor #)'),
        'unit_photo' => E::ts('Unit Photo'),
        'business_id' => E::ts('Business'),
      ];
      if ((!empty($this->_pid) && empty($this->_id))) {
        unset($elements['address_id']);
      }
      foreach ($elements as $element => $label) {
        if ($element == 'unit_photo') {
          $this->add('file', $element, $label);
          $this->addUploadElement($element);
          continue;
        }
        elseif ($element == 'business_id') {
          $element = $this->addEntityRef($element, $label, ['placeholder' => '- Select Business -', 'create' => TRUE, 'multiple' => TRUE]);
          if ($this->_bid > 0) {$element->freeze();}
          continue;
        }
        elseif ($element === 'address_id') {
          $this->addEntityRef($element, $label, ['placeholder' => '- Select Unit Address -', 'create' => TRUE, 'entity' => 'Address', 'api' => [
            'search_field' => 'street_address',
          ]], TRUE);
          continue;
        }
        $attr = ($element == 'unit_price') ? ['placeholder' => CRM_Core_Config::singleton()->defaultCurrencySymbol . ' 0.00'] : [];
        $required = (in_array($element, ['unit_status']));
        $ele = $this->addField($element, array_merge(['label' => $label], $attr), $required, FALSE);
        // if we are only creating new unit, then set status to 'Vacant (available for rent)'
        if ($element == 'unit_status' && ($this->_bid === 0 || empty($this->_id))) {
          $this->setDefaults(['unit_status' => 2]);
          $ele->freeze();
        }
      }

      if (!empty($this->_unit['file.uri'])) {
        $this->assign('imageURL', CRM_Utils_File::getFileURL($this->_unit['file.uri'], $this->_unit['file.mime_type']));
      }
      $elements = array_merge($addressElements, $elements);
      if ($this->_pid >= 0) {
        $elements = array_merge(['property_id' => 'Property'], $elements);
      }

      $this->assign('elements', $elements);
      $this->addButtons([
        [
          'type' => 'upload',
          'name' => $this->_id ? E::ts('Update') : E::ts('Submit'),
          'isDefault' => TRUE,
        ],
        ['type' => 'cancel', 'name' => E::ts('Cancel')]
      ]);
    }
    else {
      $this->addButtons([
        ['type' => 'submit', 'name' => E::ts('Delete'), 'isDefault' => TRUE],
        ['type' => 'cancel', 'name' => E::ts('Cancel')]
      ]);
    }

    $this->addFormRule(['CRM_Biaproperty_Form_Unit', 'formRule'], $this);

    parent::buildQuickForm();
  }

  public static function formRule($fields, $files, $self) {
    $errors = [];
    if (!empty($fields['unit_status']) && $fields['unit_status'] == 1 && empty($fields['business_id'])) {
      $errors['business_id'] = ts('Please select a business.');
    }
    if (!empty($fields['address_id']) && !empty($self->_id)) {
      $pid = $self->_pid ?? $fields['property_id'];
      // check if there is/are any unit(s) which share the same unit address
      $count = Unit::get(FALSE)->addWhere('address_id', '=', $fields['address_id'])
        ->addWhere('property_id', '=', $pid)
        ->addWhere('id', '!=', $self->_id)
        ->execute()->count();

      if ($count > 0) {
        $errors['address_id'] = ts('There is a existing property unit at this mailing address. Please choose a different unit address.');
      }
    }

    if (!empty($fields['street_address']) && !empty($fields['street_unit'])) {
      $addressCheck = Address::get(FALSE)
        ->addWhere('street_unit', '=', $fields['street_unit'])
        ->addWhere('street_address', '=', $fields['street_address'])
        ->execute()
        ->count();
      if ($addressCheck > 0) {
        $errors['street_address'] = E::ts('Unit Address already exists in the database');
      }
    }

    if (!empty($fields['business_id'])) {
       $subTypes = Contact::get(FALSE)->addSelect('contact_sub_type:label')->addWhere('id', '=', $fields['business_id'])->execute()->first()['contact_sub_type:label'];
       if (in_array('BIA Staff', $subTypes) || in_array('Government Staff/Member', $subTypes)) {
         $errors['business_id'] = E::ts('You cannot choose business contact of type Staff or Government Staff/Member');
       }
    }
    return $errors;
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
    if ($this->_unit) {
      $defaults = $this->_unit;
      $defaults['business_id'] = UnitBusiness::get(FALSE)
        ->addWhere('unit_id', '=', $this->_unit['id'])
        ->execute()->column('business_id');
    }
    elseif (!empty($this->_pid)) {
      $property = Property::get(FALSE)->addWhere('id', '=', $this->_pid)->execute()->first();
      $defaults['country_id'] = Civi::settings()->get('defaultContactCountry');
      $defaults['state_province_id'] = Civi::settings()->get('defaultContactStateProvince');
      $defaults['city'] = $property['city'];
      $defaults['postal_code'] = $property['postal_code'];
      if ($this->_pid !== 0) {$defaults['property_id'] = $this->_pid;}
    }
    if ($this->_bid && $this->_bid !== 0) {
      $defaults['business_id'] = $this->_bid;
      $defaults['unit_status'] = 1;
    }
    return $defaults;
  }

  public function postProcess() {
    if ($this->_action == CRM_Core_Action::DELETE) {
      civicrm_api4('Address', 'delete', ['where' => [['id', '=', $this->_unit['address_id']]], 'checkPermissions' => FALSE]);
      civicrm_api4('Unit', 'delete', ['where' => [['id', '=', $this->_id]]]);
      $count = civicrm_api4('Unit', 'get', ['where' => [['address_id', '=', $this->_unit['address_id']], ['id', '!=', $this->_id]]])->count();
      CRM_Core_Session::setStatus(E::ts('Removed Unit'), E::ts('Unit'), 'success');
    }
    else {
      $action = empty($this->_id) ? 'create' : 'update';
      $values = $this->controller->exportValues();
      $this->_pid = ($this->_pid == 0) ? $values['property_id'] : $this->_pid;
      $values['property_id'] = $this->_pid;

      if (empty($this->_id)) {
        unset($values['id']);
      }
      // on changing of unit address, if the old unit address is no longer associated with any unit then delete it
      elseif ($this->_unit['address_id'] != $values['address_id']) {
        $count = \Civi\Api4\Unit::get(FALSE)->addWhere('address_id', '=', $this->_unit['address_id'])->execute()->count();
        if ($count == 0) {
          \Civi\Api4\Address::delete(FALSE)->addWhere('id', '=', $this->_unit['address_id'])->execute();
        }
      }
      if ($action === 'create') {
        if (empty($values['address_id'])) {
          $addressElements = ['street_address', 'city', 'postal_code', 'state_province_id', 'country_id'];
          $addressValues = [];
          foreach ($addressElements as $addressElement) {
            $addressValues[$addressElement] = $values[$addressElement];
          }
          if (!empty($values['street_unit'])) {
            $addressValues['street_unit'] = $values['street_unit'];
          }
          $address = Address::create(FALSE)->setValues($addressValues)->execute();
          $values['address_id'] = $address[0]['id'];
        }
        else {
          // If we are in create action but have an address id lets see if it is already linked to a unit and if so change action to be update.
          $unit = Unit::get(FALSE)->addWhere('address_id', '=', $values['address_id'])->execute();
          if (count($unit) > 0) {
            $this->_id = $unit[0]['id'];
            $action = 'update';
            $values['id'] = $this->_id;
          }
        }
      }
      $fileInfo = $values['unit_photo'];
      $fileID = NULL;
      if (!empty($fileInfo)) {
        $fileDAO = new CRM_Core_DAO_File();
        $filename = pathinfo($fileInfo['name'], PATHINFO_BASENAME);
        $fileDAO->uri = $filename;
        $fileDAO->mime_type = $fileInfo['type'];
        $fileDAO->upload_date = date('YmdHis');
        $fileDAO->save();
        $fileID = $fileDAO->id;
      }

      $values['unit_photo'] = $fileID;
      $unitID = civicrm_api4('Unit', $action, ['values' => $values])->first()['id'];
      if ($fileID) {
        $ef = new CRM_Core_DAO_EntityFile();
        $ef->entity_table = 'civicrm_unit';
        $ef->entity_id = $unitID;
        $ef->file_id = $fileID;
        $ef->save();
      }
      $submittedBusinessIds = (array) explode(',', $values['business_id']);
      $submittedBusinessIds = array_filter($submittedBusinessIds);
      $values['business_id'] = $submittedBusinessIds;
      if ($values['unit_status'] == 1 && !empty($submittedBusinessIds) && empty($this->_id)) {
        foreach ($values['business_id'] as $business_id) {
          UnitBusiness::create(FALSE)
            ->addValue('unit_id', $unitID)
            ->addValue('business_id', $business_id)
            ->execute();
        }
      }
      elseif ($values['unit_status'] != 1 && !empty($submittedBusinessIds) && !empty($this->_id)) {
        foreach ($values['business_id'] as $business_id) {
          UnitBusiness::delete(FALSE)
            ->addWhere('unit_id', '=', $unitID)
            ->addWhere('business_id', '=', $business_id)
            ->execute();
          Activity::create(FALSE)
            ->addValue('activity_type_id:name', 'Business closed')
            ->addValue('target_contact_id', $business_id)
            ->addValue('assignee_contact_id', $business_id)
            ->addValue('source_contact_id', CRM_Core_Session::getLoggedInContactID())
            ->addValue('status_id:name', 'Completed')
            ->addValue('details', 'Business closed at Unit - ' . (!empty($this->_unit['address.street_unit']) ? '#' . $this->_unit['address.street_unit'] . ', ' : '') . $this->_unit['address.street_address'] . ')')
            ->addValue('subject', 'Business closed')
            ->execute();
        }
        if (count($values['business_id']) > 0) {
          CRM_Core_Session::setStatus(ts('Business closed successfully'), ts('Business closed'), 'success');
        }
      }
      elseif ($values['unit_status'] == 1 && !empty($submittedBusinessIds) && !empty($this->_id)) {
        $blankUnitBusinessRecords = [];
        $unitBusinessRecords = UnitBusiness::get(FALSE)
          ->addWhere('unit_id', '=', $unitID)
          ->addWhere('unit_id.property_id', '=', $this->_pid)
          ->execute();
        // Loop through all the current Unit Business Records.
        if (count($unitBusinessRecords) > 1) {
          foreach ($unitBusinessRecords as $unitBusinessRecord) {
            // if there is no business linked yet we might use that lower down
            if (empty($unitBusinessRecord['business_id'])) {
              $blankUnitBusinessRecords[] = $unitBusinessRecord['id'];
            }
            else {
              // If the business that is linked is not in the submitted ones lets just delete the unit business record
              if (!in_array($unitBusinessRecord['business_id'], $submittedBusinessIds)) {
                UnitBusiness::delete(FALSE)
                  ->addWhere('id', '=', $unitBusinessRecord['id'])
                  ->execute();
              }
              else {
                // ok the business is already in the database do nothing but remove it out of the submitted business ids array
                $key = array_search($unitBusinessRecord['business_id'], $submittedBusinessIds);
                unset($submittedBusinessIds[$key]);
              }
            }
          }
        }
        foreach ($submittedBusinessIds as $newBusinessId) {
          // ok now we loop through the rest of the submitted businesses
          // lets see if we have any database records for this unit that hasn't been linked to a business if so link
          if (count($blankUnitBusinessRecords) > 0) {
            $key = array_key_first($blankUnitBusinessRecords);
            UnitBusiness::update(FALSE)
              ->addValue('business_id', $newBusinessId)
              ->addValue('id', $blankUnitBusinessRecords[$key])
              ->execute();
            unset($blankUnitBusinessRecords[$key]);
          }
          else {
            // ok we must have run out of existing rows to update so lets create a new row.
            UnitBusiness::create(FALSE)
              ->addValue('business_id', $newBusinessId)
              ->addValue('unit_id', $unitID)
              ->execute();
          }
        }
      }
      elseif ($values['unit_status'] != 1 && !empty($values['business_id'])) {
        $unitBusinesses = UnitBusiness::get(FALSE)
          ->addWhere('unit_id', '=', $unitID)
          ->addWhere('business_id', '=', $values['business_id'])
          ->execute();
        foreach ($unitBusinesses as $unitBusiness) {
          UnitBusiness::delete(FALSE)
            ->addWhere('id', '=', $unitBusiness['id'])
            ->execute();
        }
      }
      parent::postProcess();
      $property = Property::get(FALSE)->addWhere('id', '=', $this->_pid)->execute()->first();
      $title = (!empty($property['name'])) ? $property['name'] . ' - ' . $property['property_address'] : $property['property_address'];
      if (empty($_REQUEST['snippet']) || ($_REQUEST['snippet'] && !in_array($_REQUEST['snippet'], [
        CRM_Core_Smarty::PRINT_JSON,
        6,
      ]))) {
        CRM_Utils_System::redirect(CRM_Utils_System::url('/civicrm/biaunits#?pid=' . $this->_pid . '&title=' . $title));
      }
    }
  }

}
