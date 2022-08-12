<?php

use CRM_Biaproperty_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_BiaProperty_Form_Property extends CRM_Core_Form {

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

    $this->_id = CRM_Utils_Request::retrieve('id', 'Positive', $this, FALSE);
    $this->_oid = CRM_Utils_Request::retrieve('oid', 'Positive', $this, FALSE);
    CRM_Utils_System::setTitle('Add Property');
    if ($this->_id) {
      CRM_Utils_System::setTitle('Edit Property');
      $entities = civicrm_api4('Property', 'get', ['where' => [['id', '=', $this->_id]], 'limit' => 1]);
      $this->_property = reset($entities);

      $this->assign('property', $this->_property);

      $session = CRM_Core_Session::singleton();
      $session->replaceUserContext(CRM_Utils_System::url('civicrm/property/form', ['id' => $this->getEntityId(), 'action' => 'update']));
    }
  }


  public function buildQuickForm() {
    $this->assign('id', $this->getEntityId());
    $this->add('hidden', 'id');
    if ($this->_action != CRM_Core_Action::DELETE) {
      $elements = [
        'address_name' => 'Property Name',
        'roll_no' => 'Roll #',
        'street_address' => 'Address',
        'supplemental_address_1' => 'Supplemental Address',
        'city' => 'City',
        'postal_code' => 'Postal Code',
        'country_id' => 'Country',
        'state_province_id' => 'State/Province',
        'is_voter' => 'Vote?',
      ];
      if (empty($this->_oid)) {
        unset($elements['is_voter']);
      }
      foreach ($elements as $element => $label) {
        if ($element == 'country_id') {
          $this->add('select', $element, E::ts($label), CRM_Core_PseudoConstant::country());
        }
        elseif ($element == 'is_voter') {
          $this->addYesNo($element, E::ts($label), TRUE);
        }
        elseif ($element == 'state_province_id') {
          $this->addChainSelect($element, ['label' => E::ts($label)]);
        }
        else {
          $this->add('text', $element, E::ts($label));
        }
      }

      $this->assign('elements', $elements);

      $this->addButtons([
        [
          'type' => 'upload',
          'name' => E::ts('Submit'),
          'isDefault' => TRUE,
        ],
      ]);
    } else {
      $this->addButtons([
        ['type' => 'submit', 'name' => E::ts('Delete'), 'isDefault' => TRUE],
        ['type' => 'cancel', 'name' => E::ts('Cancel')]
      ]);
    }
    parent::buildQuickForm();
  }

  /**
   * This virtual function is used to set the default values of various form
   * elements.
   *
   * @return array|NULL
   *   reference to the array of default values
   */
  public function setDefaultValues() {
    if ($this->_property) {
      $address = \Civi\Api4\Address::get(FALSE)
        ->addWhere('id', '=', $this->_property['address_id'])
        ->execute()->first();
      $defaults = $address;
      if ($this->_oid) {
      $defaults['is_voter'] = \Civi\Api4\PropertyOwner::get(FALSE)
        ->addSelect('is_voter')
        ->addWhere('property_id', '=', $this->_id)
        ->addWhere('owner_id', '=', $this->_oid)
        ->execute()->first()['is_voter'];
      }
    }
    else {
     $defaults['country_id'] = CRM_Core_Config::singleton()->defaultContactCountry;
     $defaults['state_province_id'] = CRM_Core_Config::singleton()->defaultContactStateProvince;
    }
    return $defaults;
  }

  public function postProcess() {
    if ($this->_action == CRM_Core_Action::DELETE) {
      civicrm_api4('Property', 'delete', ['where' => [['id', '=', $this->_id]]]);
      CRM_Core_Session::setStatus(E::ts('Removed Property'), E::ts('Property'), 'success');
    } else {
      $values = $this->controller->exportValues();
      $params = [];
      if ($this->getEntityId()) {
        // Update the address.
        $addressId = \Civi\Api4\Property::get()
          ->addSelect('address_id')
          ->addWhere('id', '=', $this->getEntityId())
          ->execute()->first()[0]['address_id'];
        $values['id'] = $addressId;
        $address = civicrm_api4('Address', 'update', [
          'values' => $values,
        ])->first()['id'];
        $params['id'] = $this->getEntityId();
        $params['address_id'] = $address;
        $action = 'update';
      }
      else {
        unset($values['id']);
        $action = 'create';
        $address = civicrm_api4('Address', 'create', [
          'values' => $values,
        ])->first()['id'];
        $params['address_id'] = $address;
        $params['roll_no'] = $values['roll_no'];
        $params['created_id'] = CRM_Core_Session::singleton()->getLoggedInContactID();
      }
      $propertyID = civicrm_api4('Property', $action, [
        'values' => $params,
      ])->first()['id'];
      if (!empty($this->_oid)) {
      \Civi\Api4\PropertyOwner::create(FALSE)
        ->addValue('property_id', $propertyID)
        ->addValue('owner_id', $this->_oid)
        ->addValue('is_voter', $values['is_voter'])
        ->execute();
      }
    }
    parent::postProcess();
  }

}
