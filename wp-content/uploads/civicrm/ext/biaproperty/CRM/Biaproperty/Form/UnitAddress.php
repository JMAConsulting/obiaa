<?php

use CRM_Biaproperty_ExtensionUtil as E;
use Civi\Api4\Property;
use Civi\Api4\Unit;
use Civi\Api4\Address;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Biaproperty_Form_UnitAddress extends CRM_Core_Form {


  /**
   * Unit ID
   * @var int
   */
  protected $_uid;

  /**
   * Property ID
   * @var int
   */
  protected $_pid;

  /**
   * Address ID
   * @var int
   */
  protected $_id;

  public function getDefaultEntity() {
    return 'Address';
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
    $this->_uid = CRM_Utils_Request::retrieve('uid', 'Positive', $this, FALSE);
    $this->_pid = CRM_Utils_Request::retrieve('pid', 'Positive', $this, FALSE);
    $mode = $this->_id ? 'Edit' : 'Add';
    CRM_Utils_System::setTitle($mode . ' Unit Address');
  }

  public function buildQuickForm() {
    $this->assign('id', $this->getEntityId());
    $this->add('hidden', 'id');
    $elements = [];
    if ($this->_action != CRM_Core_Action::DELETE) {
      $elements = [
        'street_address' => E::ts('Business Mailing Address'),
        'street_unit' => E::ts('Business Mailing Unit/Suite'),
        'city' => E::ts('City'),
        'postal_code' => E::ts('Postal Code'),
        'country_id' => E::ts('Country'),
        'state_province_id' => E::ts('Province'),
      ];
      foreach ($elements as $element => $label) {
        if ($element == 'country_id') {
          $this->add('select', $element, $label, CRM_Core_PseudoConstant::country(), TRUE);
        }
        elseif ($element == 'state_province_id') {
          $this->addChainSelect($element, ['label' => $label, 'required' => TRUE]);
        }
        else {
          $isRequired = ($element === 'street_unit' ? FALSE : TRUE);
          $this->add('text', $element, $label, NULL, $isRequired);
        }
      }
      if (empty($this->_id)) {
        $propertyDetails = Property::get(FALSE)->addWhere('id', '=', $this->_pid)->execute()->first();
        $this->setDefaults([
          'country_id' => Civi::settings()->get('defaultContactCountry'),
          'state_province_id' => Civi::settings()->get('defaultContactStateProvince'),
          'city' => $propertyDetails['city'],
          'postal_code' => $propertyDetails['postal_code'],
        ]);
      }
      else {
        $address = Address::get(FALSE)->addWhere('id', '=', $this->_id)->execute();
        $this->setDefaults($address);
      }
      $this->addButtons([
        [
          'type' => 'upload',
          'name' => E::ts('Submit'),
          'isDefault' => TRUE,
        ],
        ['type' => 'cancel', 'name' => E::ts('Cancel')],
      ]);
    }
    else {
      $this->addButtons([
        ['type' => 'submit', 'name' => E::ts('Delete'), 'isDefault' => TRUE],
        ['type' => 'cancel', 'name' => E::ts('Cancel')],
      ]);
    }
    $this->assign('elements', $elements);
    $this->addFormRule([__CLASS__, 'formRule'], $this);
    parent::buildQuickForm();
  }

  public static function formRule($fields, $files, $self) {
    $errors = [];
    if (!empty($fields['street_unit']) && !empty($fields['street_address']) && empty($self->_id)) {
      $count = Address::get(FALSE)
        ->addWhere('street_unit', '=', $fields['street_unit'])
        ->addWhere('street_address', '=', $fields['street_address'])
        ->addWhere('city', '=', $fields['city'])
        ->execute()->count();
      if ($count > 0) {
        $errors['street_unit'] = $errors['street_address'] = ts('Duplicate street unit and address found.');
      }
    }
    if (empty($fields['street_unit']) && empty($self->_id)) {
      $count = Address::get(FALSE)
        ->addWhere('street_unit', 'IS EMPTY')
        ->addWhere('street_address', '=', $fields['street_address'])
        ->addWhere('city', '=', $fields['city'])
        ->execute()->count();
      if ($count > 0) {
        $errors['street_address'] = E::ts('Cannot have more than one unit covering the whole property');
      }
    }
    if (!empty($fields['street_unit']) && !empty($fields['street_address']) && !empty($self->_id)) {
      $count = Address::get(FALSE)
        ->addWhere('street_unit', '=', $fields['street_unit'])
        ->addWhere('id', '!=', $self->_id)
        ->addWhere('street_address', '=', $fields['street_address'])
        ->addWhere('city', '=', $fields['city'])
        ->execute()->count();
      if ($count > 0) {
        $errors['street_unit'] = $errors['street_address'] = ts('Duplicate street unit and address found.');
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
    if ($this->getEntityId()) {
      $defaults = Address::get(FALSE)
        ->addWhere('id', '=', $this->getEntityId())
        ->execute()->first();
    }
    return $defaults;
  }


  public function postProcess() {
    if ($this->_action == CRM_Core_Action::DELETE) {
      Address::delete(FALSE)->addWhere('id', '=', $this->_id)->execute();
      CRM_Core_Session::setStatus(E::ts('Removed Unit Address'), E::ts('Unit Address'), 'success');
    }
    else {
      $values = $this->controller->exportValues();
      $values['is_primary'] = 1;
      if (empty($values['street_unit'])) {
        $values['street_unit'] = NULL;
      }
      if (empty($this->getEntityId())) {
        unset($values['id']);
        $address = Address::create(FALSE)
          ->setValues($values)
          ->execute()
          ->first();
        if (!empty($this->_oid)) {
          Unit::update(FALSE)->addValue('address_id', $address['id'])->addWhere('id', '=', $this->_oid)->execute();
        }
        $this->_id = $address['id'];
        $this->ajaxResponse['label'] = (!empty($address['street_unit']) ? '#' . $address['street_unit'] . ' - ' : '') . $address['street_address'];
      }
      else {
        $address = civicrm_api4('Address', 'update', [
          'values' => $values,
        ])->first()['id'];
        $this->_id = $this->getEntityId();
        $action = 'update';
        $this->ajaxResponse['label'] = (!empty($values['street_unit']) ? '#' . $values['street_unit'] . ' - ' : '') . $values['street_address'];
      }
    }
    parent::postProcess();
  }

}
