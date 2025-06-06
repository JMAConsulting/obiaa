<?php

require_once 'CRM/Core/Form.php';

/**
 * Form controller class
 *
 * @see http://wiki.civicrm.org/confluence/display/CRMDOC43/QuickForm+Reference
 */
class CRM_CivirulesConditions_Form_Campaign_Type extends CRM_CivirulesConditions_Form_Form {

  /**
   * Overridden parent method to build the form
   *
   * @access public
   */
  public function buildQuickForm() {
    $this->add('hidden', 'rule_condition_id');
    $campaignList = ['- select -'] + CRM_Civirules_Utils::getCampaignTypeList();
    asort($campaignList);
    $this->add('select', 'campaign_type_id', ts('Campaign Type(s)'), $campaignList, TRUE,
      ['id' => 'campaign_type_ids', 'multiple' => 'multiple','class' => 'crm-select2']);
    $this->add('select', 'operator', ts('Operator'), ['is one of', 'is NOT one of'], TRUE);
    $this->addButtons([
      ['type' => 'next', 'name' => ts('Save'), 'isDefault' => TRUE,],
      ['type' => 'cancel', 'name' => ts('Cancel')],
      ]);
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
    $data = $this->ruleCondition->unserializeParams();
    if (!empty($data['campaign_type_id'])) {
      $defaultValues['campaign_type_id'] = $data['campaign_type_id'];
    }
    if (!empty($data['operator'])) {
      $defaultValues['operator'] = $data['operator'];
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
    $data['campaign_type_id'] = $this->_submitValues['campaign_type_id'];
    $this->ruleCondition->condition_params = serialize($data);
    $this->ruleCondition->save();
    parent::postProcess();
  }

}

