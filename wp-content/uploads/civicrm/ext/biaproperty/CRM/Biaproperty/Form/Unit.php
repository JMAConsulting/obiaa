<?php

use CRM_Biaproperty_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Biaproperty_Form_Unit extends CRM_Core_Form {

  protected $_id;

  protected $_pid;

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

    $this->_id = CRM_Utils_Request::retrieve('id', 'Positive', $this, FALSE);
    $this->_pid = CRM_Utils_Request::retrieve('pid', 'Positive', $this, TRUE);
    CRM_Utils_System::setTitle('Add Unit');
    if ($this->_id) {
      CRM_Utils_System::setTitle('Edit Unit');
      $this->_unit = civicrm_api4('Unit', 'get', ['where' => [['id', '=', $this->_id]], 'limit' => 1])->first();
      $this->assign('unit', $this->_unit);

      $session = CRM_Core_Session::singleton();
      $session->replaceUserContext(CRM_Utils_System::url('civicrm/unit/form', ['id' => $this->getEntityId(), 'action' => 'update']));
    }
  }


  public function buildQuickForm() {
    $this->assign('id', $this->getEntityId());
    $this->add('hidden', 'id');
    $unit = (!empty($this->_unit['unit_no'])) ? ', Unit ' . $this->_unit['unit_no'] : '';
    $property = CRM_Core_DAO::executeQuery("SELECT name, street_address FROM civicrm_property p INNER JOIN civicrm_address a ON a.id = p.address_id WHERE p.id = " . $this->_pid)->fetchAll()[0];
    $this->assign('propertyTitle', (!empty($property['name'])) ? $property['name'] . ' - ' . $property['street_address'] . $unit : $property['street_address']) . $unit;
    if ($this->_action != CRM_Core_Action::DELETE) {
      $elements = [
        'unit_no' => 'Unit #',
        'unit_size' => 'Unit Size',
        'unit_price' => 'Unit Price',
        'unit_status' => 'Status',
        'mls_listing_link' => 'Listing link',
        'unit_location' => 'Unit Location',
        'unit_photo' => 'Unit Photo',
        'business_id' => 'Business',
      ];
      foreach ($elements as $element => $label) {
        if ($element == 'unit_photo') {
          $this->add('file', $element, E::ts($label));
          $this->addUploadElement($element);
          continue;
        }
        elseif ($element == 'business_id') {
          $this->addEntityRef($element, E::ts($label), ['placeholder' => '- Select Business -', 'create' => TRUE, 'api' => [
            'params' => ['contact_type' => 'Organization'],
      	  ]]);
          continue;
        }
        $attr = ($element == 'unit_price') ? ['placeholder' => CRM_Core_Config::singleton()->defaultCurrencySymbol . ' 0.00'] : [];
        $this->addField($element, array_merge(['label' => E::ts($label)], $attr), FALSE, FALSE);
      }

      $this->assign('elements', $elements);

      $this->addButtons([
        [
          'type' => 'upload',
          'name' => $this->_id ? E::ts('Update') : E::ts('Submit'),
          'isDefault' => TRUE,
        ],
      ]);
    } else {
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
      $defaults['business_id'] = \Civi\Api4\UnitBusiness::get(FALSE)
        ->addWhere('unit_id', '=', $this->_unit['id'])
        ->execute()->first()['business_id'];
    }
    return $defaults;
  }

  public function postProcess() {
    if ($this->_action == CRM_Core_Action::DELETE) {
      civicrm_api4('Unit', 'delete', ['where' => [['id', '=', $this->_id]]]);
      CRM_Core_Session::setStatus(E::ts('Removed Unit'), E::ts('Unit'), 'success');
    } else {
      $action = empty($this->_id) ? 'create' : 'update';
      if (empty($this->_id)) {unset($values['id']);}
      $values = $this->controller->exportValues();
      if (empty($this->_id)) {unset($values['id']);}
     $fileInfo = $values['unit_photo'];
     $fileDAO = new CRM_Core_DAO_File();
    $filename = pathinfo($fileInfo['name'], PATHINFO_BASENAME);
    $fileDAO->uri = $filename;
    $fileDAO->mime_type = $fileInfo['type'];
    $fileDAO->upload_date = date('YmdHis');
    $fileDAO->save();
    $fileID = $fileDAO->id;

     $values['unit_photo'] = $fileID;
     $unitID = civicrm_api4('Unit', $action, ['values' => $values])->first()['id'];
     if (empty($this->_id)) {
       civicrm_api4('PropertyUnit', 'create', ['values' => [
        'property_id' => $this->_pid,
        'unit_id' => $unitID
       ]]);
    }
    $ef = new CRM_Core_DAO_EntityFile();
    $ef->entity_table = 'civicrm_unit';
    $ef->entity_id = $unitID;
    $ef->file_id = $fileID;
    $ef->save();
     if ($values['unit_status'] == 1 && !empty($values['business_id'])) {
       \Civi\Api4\UnitBusiness::create()
          ->addValue('unit_id', $unitID)
          ->addValue('business_id', $values['business_id'])
          ->addValue('property_id', $this->_pid)
          ->execute();
     }
    }
    parent::postProcess();
    $session = CRM_Core_Session::singleton();
    $context = $session->readUserContext() . '&pid=' . $this->_pid;
    CRM_Utils_System::redirect($context);
  }

}
