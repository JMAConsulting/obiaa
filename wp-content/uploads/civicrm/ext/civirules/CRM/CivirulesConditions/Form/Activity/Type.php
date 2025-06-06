<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_CivirulesConditions_Form_Activity_Type extends CRM_CivirulesConditions_Form_Form {

  /**
   * Overridden parent method to build the form
   *
   * @access public
   */
  public function buildQuickForm() {
    $this->add('hidden', 'rule_condition_id');

    $activityTypeList = array('- select -') + CRM_Civirules_Utils::getActivityTypeList();
    asort($activityTypeList);
    $this->add('select', 'activity_type_id', ts('Activity Type(s)'), $activityTypeList, true,
      array('id' => 'activity_type_ids', 'multiple' => 'multiple','class' => 'crm-select2'));
    $this->add('select', 'operator', ts('Operator'), array('is one of', 'is NOT one of'), true);

    $this->addButtons(array(
      array('type' => 'next', 'name' => ts('Save'), 'isDefault' => TRUE,),
      array('type' => 'cancel', 'name' => ts('Cancel'))));

    parent::buildQuickForm();
  }

  /**
   * Overridden parent method to set default values
   *
   * @return array $defaultValues
   * @access public
   */
  public function setDefaultValues() {
    $defaultValues = parent::setDefaultValues();
    if (isset($this->ruleCondition->condition_params)) {
      $data = $this->ruleCondition->unserializeParams();
      if (!empty($data['activity_type_id'])) {
        $defaultValues['activity_type_id'] = $data['activity_type_id'];
      }
      if (!empty($data['operator'])) {
        $defaultValues['operator'] = $data['operator'];
      }
    }
    return $defaultValues;
  }

  /**
   * Overridden parent method to perform data processing once form is submitted
   *
   * @access public
   */
  public function postProcess() {
    $data['operator'] = $this->_submitValues['operator'];
    $data['activity_type_id'] = $this->_submitValues['activity_type_id'];
    $this->ruleCondition->condition_params = serialize($data);
    $this->ruleCondition->save();

    parent::postProcess();
  }

}