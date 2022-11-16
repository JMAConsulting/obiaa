<?php
use CRM_Obiaareport_ExtensionUtil as E;

class CRM_Obiaareport_Form_Report_ObiaaAnchorsReport extends CRM_Report_Form {

  protected $_addressField = FALSE;

  protected $_emailField = FALSE;

  protected $_summary = NULL;

  protected $_customGroupGroupBy = FALSE;

  function __construct() {
    $cfFilter = \Civi\Api4\CustomField::get()->addWhere('name', '=', 'What_region_is_this_BIA_in_')->execute()->first();
    $this->_columns = [
      'civicrm_unit_business' => [
        'doa' => 'CRM_Biaproperty_DAO_Unit_Business',
        'fields' => [
          'anchors' => [
            'required' => TRUE,
            'title' => E::ts('Number of Anchors'),
            'dbAlias' => 'COUNT(DISTINCT a.business_id)',
          ],          
        ],
        'filters' => [
          $cfFilter['column_name'] => [
            'title' => $cfFilter['label'],
            'type' => CRM_Utils_Type::T_STRING,
            'operatorType' => CRM_Report_Form::OP_MULTISELECT,
            'options' => CRM_Core_OptionGroup::values('Region_What_region_is_this_BIA_in_'),
            'dbAlias' => 'cf.' . $cfFilter['column_name'],
          ],
        ],
      ],
    ];
      
   
    parent::__construct();
  }
    
  function preProcess() {
    $this->assign('reportTitle', E::ts('Number of Anchors Report'));
    parent::preProcess();
  }

  function from() {
    parent::where();
    $join = empty($this->_whereClauses) ? 'LEFT' : 'INNER';

    $this->_from = "
      FROM civicrm_unit_business a
      LEFT JOIN civicrm_contact b ON a.business_id = b.id
      LEFT JOIN civicrm_value_business_deta_5 c ON b.id =  c.entity_id
      {$join} JOIN (SELECT business_id FROM civicrm_unit_business ub INNER JOIN civicrm_value_region_27 cf ON cf.entity_id = ub.business_id {$this->_where} GROUP BY business_id) temp ON temp.business_id = a.business_id
          ";    
  }

  function where() {
    $this->_where = "
      WHERE c.anchor_business_28 = 1
      AND b.contact_type = 'Organization'
      AND b.is_deleted = 0";
  }
  function alterDisplay(&$rows) {
    
    
  }

}



