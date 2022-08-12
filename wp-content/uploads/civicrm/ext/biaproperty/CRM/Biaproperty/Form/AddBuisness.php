<?php

use CRM_Biaproperty_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Biaproperty_Form_AddBuisness extends CRM_Core_Form {

    protected $_pid;
        protected $_uid;
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

      $this->_action = CRM_Utils_Request::retrieve('action', 'String', $this);
      $this->_pid = CRM_Utils_Request::retrieve('pid', 'Positive', $this, FALSE);
      $this->_uid = CRM_Utils_Request::retrieve('uid', 'Positive', $this, FALSE);
      $cid = $this->_cid = CRM_Utils_Request::retrieve('bid', 'Positive', $this, FALSE);
      if (!$cid) {
      $cid = $this->_cid = \Civi\Api4\UnitBusiness::get(FALSE)
        ->addWhere('unit_id', '=', $this->_uid)
        ->addWhere('property_id', '=', $this->_pid)
        ->execute()->first()['business_id'];
      }
      if ($cid && $this->_pid) {
         CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/contact/view', 'reset=1&cid=' . $cid));
         // CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/business#?id=' . $cid));
      }

      $this->assign('action', $this->_action);


      CRM_Utils_System::setTitle('Add Business');
    }

    /**
     * This virtual function is used to set the default values of various form
     * elements.
     *
     * @return array|NULL
     *   reference to the array of default values
     */
    public function setDefaultValues() {
      $defaults = ['unit_id' => $this->_uid, 'business_id' => $this->_cid];
      return $defaults;
    }

    public function buildQuickForm() {
      $options = $units = [];
    if ($this->_pid) {
      $units = \Civi\Api4\PropertyUnit::get(FALSE)
        ->addSelect('unit_id.unit_no', 'unit_id.id')
        ->addJoin('Unit AS unit', 'LEFT')
        ->addWhere('property_id', '=', $this->_pid)
        ->execute();
      foreach ($units as $unit) {
        $options[$unit['unit_id.id']] = ' Unit ' . $unit['unit_id.unit_no'];
      }
    }
    else {
      $units = \Civi\Api4\Unit::get(FALSE)
        ->addSelect('unit_no', 'id')
        ->execute();
      foreach ($units as $unit) {
        $options[$unit['id']] = ' Unit ' . $unit['unit_no'];
      }
      }
      $element = $this->add('select', 'unit_id', E::ts('Unit #'), $options);
      if ($this->_uid) {$element->freeze();}
      $element = $this->addEntityRef('business_id', E::ts('Business'), ['placeholder' => '- Select Business -', 'create' => TRUE, 'api' => [
        'params' => ['contact_type' => 'Organization'],
      ]], $required);
      if ($this->_cid) {$element->freeze();}
      $this->assign('elementNames', ['unit_id', 'business_id']);

      $this->addButtons([
        [
          'type' => 'upload',
          'name' => E::ts('Update'),
          'isDefault' => TRUE,
        ],
      ]);
      parent::buildQuickForm();
    }

    public function postProcess() {
      $values = $this->controller->exportValues();
      $business = \Civi\Api4\UnitBusiness::get(FALSE)
        ->addWhere('unit_id', '=', $this->_uid)
        ->addWhere('property_id', '=', $this->_pid)
        ->execute()->first();
       // move contact  business_id to 'Member Business' contact subtype
       \Civi\Api4\UnitBusiness::update(FALSE)
        ->addValue('id', $values['business_id'])
        ->addValue('contact_sub_type', 'Members_Businesses_')
        ->execute();
      if ($business['business_id'] != $values['business_id']) {
        \Civi\Api4\UnitBusiness::update(FALSE)
          ->addValue('id', $business['id'])
          ->addValue('business_id', $values['business_id'])
          ->execute();
        \Civi\Api4\Activity::create()
          ->addValue('activity_type_id:name', 'Move Business within BIA')
          ->addValue('target_contact_id', $values['business_id'])
          ->addValue('assignee_contact_id', $values['business_id'])
          ->addValue('source_record_id', $business['id'])
          ->addValue('source_contact_id', $business['business_id'])
          ->addValue('subject', 'Move Business within BIA')
          ->execute();
          return;
      }

      \Civi\Api4\UnitBusiness::create(FALSE)
        ->addValue('unit_id', $values['unit_id'])
        ->addValue('business_id', $values['business_id'])
        ->addValue('property_id', $this->_pid)
        ->execute();
      parent::postProcess();
    }

}
