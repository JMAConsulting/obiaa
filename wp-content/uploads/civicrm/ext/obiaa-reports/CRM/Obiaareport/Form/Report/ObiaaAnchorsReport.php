<?php
use CRM_Obiaareport_ExtensionUtil as E;

class CRM_Obiaareport_Form_Report_ObiaaAnchorsReport extends CRM_Report_Form {

  protected $_addressField = FALSE;

  protected $_emailField = FALSE;

  protected $_summary = NULL;

  protected $_customGroupGroupBy = FALSE;

  function __construct() {
    $cfFilter = \Civi\Api4\CustomField::get(FALSE)->addWhere('name', '=', 'What_region_is_this_BIA_in_')->execute()->first();
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
        'filters' => [],
      ],
    ];
    CRM_Obiaareport_Utils::addMembershipFilter($this->_columns['civicrm_unit_business']['filters']);
    parent::__construct();
  }

  function preProcess() {
    $this->assign('reportTitle', E::ts('Number of Anchors Report'));
    parent::preProcess();
  }

  function from() {
    parent::where();
    $join = empty($this->_whereClauses) ? 'LEFT' : 'INNER';
    $filter = CRM_Obiaareport_Utils::addMembershipTableJoin('Business', 'a');

    $this->_from = "
      FROM civicrm_unit_business a
      LEFT JOIN civicrm_contact b ON a.business_id = b.id
      LEFT JOIN civicrm_value_business_deta_5 c ON b.id = c.entity_id
      {$filter}
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
