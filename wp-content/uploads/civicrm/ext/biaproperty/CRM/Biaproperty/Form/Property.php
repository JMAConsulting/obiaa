<?php

use CRM_Biaproperty_ExtensionUtil as E;
use Civi\Api4\PropertyOwner;
use Civi\Api4\Unit;
use Civi\Api4\Organization;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Biaproperty_Form_Property extends CRM_Core_Form {

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
      $entities = civicrm_api4('Property', 'get', ['where' => [['id', '=', $this->_id]], 'limit' => 1])->first();
      $this->_property = $entities;

      $this->assign('property', $this->_property);

      $session = CRM_Core_Session::singleton();
      if ($this->_action != CRM_Core_Action::DELETE) {
        $session->replaceUserContext(CRM_Utils_System::url('civicrm/property/form', ['id' => $this->getEntityId(), 'action' => 'update']));
      }
      else {
        $session->replaceUserContext(CRM_Utils_System::url('civicrm/find/properties'));
      }
    }
  }


  public function buildQuickForm() {
    $this->assign('id', $this->getEntityId());
    $this->add('hidden', 'id');
    if ($this->_action == CRM_Core_Action::DELETE) {
     if (Unit::get(FALSE)->addWhere('property_id', '=', $this->_id)->addWhere('unit_status', '=', 1)->execute()->count() > 0) {
        CRM_Core_Error::statusBounce(E::ts('You cannot delete this property with occupied unit.'));
      }
    }
    if ($this->_action != CRM_Core_Action::DELETE) {
      $elements = [
        'name' => 'Property Name',
        'roll_no' => 'Roll #',
        'property_address' => 'Tax Roll Address'
      ];
      if (!$this->_id) {
        $elements = array_merge($elements, [
          'street_address' => 'Address',
          'supplemental_address_1' => 'Supplemental Address',
          'city' => 'City',
          'postal_code' => 'Postal Code',
          'country_id' => 'Country',
          'state_province_id' => 'State/Province',
        ]);
      }

      if (!empty($this->_oid)) {
        $elements['is_voter'] = 'Vote?';
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
          $required = in_array($element, ['street_address', 'roll_no', 'property_address']);
          $this->add('text', $element, E::ts($label), NULL, $required);
        }
      }
      $this->assign('elements', $elements);

      $this->addButtons([
        [
          'type' => 'upload',
          'name' => $this->_id ? E::ts('Update') : E::ts('Submit'),
          'isDefault' => TRUE,
        ],
        [
          'type' => 'cancel',
          'name' => E::ts('Cancel'),
        ],
      ]);
    }
    else {
      CRM_Utils_System::setTitle('Delete Property');
      $title = (!empty($this->_property['name'])) ? $this->_property['name'] . ' - ' . $this->_property['property_address'] : $this->_property['property_address'];
      $this->assign('title', $title);
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
    $defaults = [];
    if ($this->_property) {
      $defaults = $this->_property;
      if ($this->_oid) {
        $defaults['is_voter'] = PropertyOwner::get(FALSE)
          ->addSelect('is_voter')
          ->addWhere('property_id', '=', $this->_id)
          ->addWhere('owner_id', '=', $this->_oid)
          ->execute()->first()['is_voter'];
      }
    }
    else {
     $defaults['is_voter'] = 1;
     $defaults['country_id'] = CRM_Core_Config::singleton()->defaultContactCountry;
     $defaults['state_province_id'] = CRM_Core_Config::singleton()->defaultContactStateProvince;
    }
    return $defaults;
  }

  public function postProcess() {
    if ($this->_action == CRM_Core_Action::DELETE) {
      $units = Unit::get(FALSE)->addWhere('property_id', '=', $this->_id)->execute();
      foreach ($units as $unit) {
        // not able to execute due to foreign key constraint error, commented out for now
        $count = civicrm_api4('Unit', 'get', ['where' => [['address_id', '=', $unit['address_id']], ['id', '!=', $unit['id']]]])->count();
        if ($count === 0) {
          civicrm_api4('Address', 'delete', [
            'join' => [['LocBlock AS loc_block', 'LEFT', ['id', '=', 'loc_block.address_id']]],
            'where' => [['id', '=', $unit['address_id']], ['contact_id', 'IS NULL'], ['loc_block.address_id', 'IS NULL']],
            'checkPermissions' => FALSE,
          ]);
        }
        civicrm_api4('Unit', 'delete', ['where' => [['id', '=', $unit['id']]]]);
      }
      civicrm_api4('Property', 'delete', ['where' => [['id', '=', $this->_id]]]);
      $title = empty($this->_property['name']) ? $this->_property['property_address'] : $this->_property['name'] . ' ' . $this->_property['property_address'];
      \Civi\Api4\Activity::create(FALSE)
        ->addValue('activity_type_id:name', 'Property deleted')
        ->addValue('target_contact_id', CRM_Core_Session::getLoggedInContactID())
        ->addValue('assignee_contact_id', CRM_Core_Session::getLoggedInContactID())
        ->addValue('source_contact_id', CRM_Core_Session::getLoggedInContactID())
        ->addValue('status_id:name', 'Completed')
        ->addValue('subject', $title)
        ->execute();
      CRM_Core_Session::setStatus(E::ts('Removed Property'), E::ts('Property'), 'success');
    }
    else {
      $values = $this->controller->exportValues();
      $params = $unitParams = [];
      foreach (['name', 'property_address', 'roll_no'] as $element) {
        if (!empty($values[$element])) {
          $params[$element] = $values[$element];
        }
        else {
          $params[$element] = '';
        }
      }

      if (!$this->getEntityId()) {
        unset($values['id']);
        $action = 'create';
        $address = civicrm_api4('Address', 'create', [
          'values' => [
            'street_address' => $values['street_address'],
            'postal_code' => $values['postal_code'],
            'city' => $values['city'],
            'country_id' => $values['country_id'],
            'state_province_id' => $values['state_province_id'],
          ],
          'checkPermissions' => FALSE,
        ])->first()['id'];
        $unitParams['address_id'] = $address;
        $params['created_id'] = CRM_Core_Session::singleton()->getLoggedInContactID();
        $params['city'] = $values['city'];
        $params['postal_code'] = $values['postal_code'];
      }
      else {
        $action = 'update';
      }
      $apiParams = ['values' => $params];
      if ($action == 'update') {
        $apiParams['where'] = [];
        $apiParams['where'][] = ['id', '=', $this->_id];
      }

      $propertyID = civicrm_api4('Property', $action, $apiParams)->first()['id'];

      if (!$this->getEntityId()) {
        $unitParams['property_id'] = $propertyID;
        $unitParams['unit_status'] = 2;
        civicrm_api4('Unit', 'create', [
          'values' => $unitParams,
        ]);
      }

      if (!empty($this->_oid)) {
        $propetyOwnerCheck = PropertyOwner::get()->addWhere('property_id', '=', $propertyID)->execute();
        // If there is no other owners set ensure that this owner is set to be the voter
        $vote = count($propetyOwnerCheck) == 0 ? 1 : $values['is_voter'];
        PropertyOwner::create(FALSE)
          ->addValue('property_id', $propertyID)
          ->addValue('owner_id', $this->_oid)
          ->addValue('is_voter', $vote)
          ->execute();
      } else {
        // Create a dummy property owner
        $dummyOrg = Organization::get(FALSE)
        ->addSelect('id')
        ->addWhere('organization_name', '=', 'Empty Property Owner')
        ->execute()
        ->first();

        PropertyOwner::create(FALSE)
          ->addValue('property_id', $propertyID)
          ->addValue('owner_id', $dummyOrg['id'])
          ->addValue('is_voter', TRUE)
          ->execute();
      }

      $property = CRM_Core_DAO::executeQuery("SELECT name, property_address FROM civicrm_property p WHERE p.id = " . $propertyID)->fetchAll()[0];
      $this->ajaxResponse['label'] = $title = (!empty($property['name'])) ? $property['name'] . ' - ' . $property['property_address'] : $property['property_address'];
      $this->ajaxResponse['id'] = $propertyID;
//      CRM_Utils_System::redirect(CRM_Utils_System::url('/civicrm/biaunits#?pid=' . $this->_id . '&title=' . $title));
    }
    parent::postProcess();
  }

}
