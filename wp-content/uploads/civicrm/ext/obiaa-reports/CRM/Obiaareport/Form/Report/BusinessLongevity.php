<?php
use CRM_Obiaareport_ExtensionUtil as E;

class CRM_Obiaareport_Form_Report_BusinessLongevity extends CRM_Report_Form {

  protected $_addressField = FALSE;

  protected $_emailField = FALSE;

  protected $_summary = NULL;

  protected $_customGroupGroupBy = FALSE;

  public function __construct() {
    $this->_columns = array(
      'civicrm_unit' => array(
        'dao' => 'CRM_Biaproperty_DAO_Unit',
        'fields' => array(
         'region' => array(
            'required' => TRUE,
            'title' => E::ts('Region'),
          ),
          'year' => array(
            'required' => TRUE,
            'title' => E::ts('Average Business Longevity (years)'),
            'dbAlias' => "ROUND((SUM(TIMESTAMPDIFF(YEAR, activity_civireport.activity_date_time, activity_civireport1.activity_date_time)) / COUNT(ac_opened.contact_id)), 2)",
          ),
        ),
        'filters' => [],
      ),
    );

    CRM_Obiaareport_Utils::addMembershipFilter($this->_columns['civicrm_unit']['filters']);
    $this->_columns['civicrm_unit']['fields']['region']['dbAlias'] = $this->_columns['civicrm_unit']['filters']['Region']['dbAlias'];
    parent::__construct();
  }

  public function preProcess() {
    $this->assign('reportTitle', E::ts('Average Business Longevity Report'));
    parent::preProcess();
  }

  public function from() {
    $filter = CRM_Obiaareport_Utils::addMembershipTableJoin('ActivityContact', 'ac_opened');
    $openActivity = CRM_Core_PseudoConstant::getKey('CRM_Activity_BAO_Activity', 'activity_type_id', 'Business opened');
    $closedActivity = CRM_Core_PseudoConstant::getKey('CRM_Activity_BAO_Activity', 'activity_type_id', 'Business closed');

    $this->_from = "
    FROM  civicrm_activity activity_civireport
    INNER JOIN civicrm_activity_contact ac_opened ON ac_opened.activity_id = activity_civireport.id AND ac_opened.record_type_id = 3 AND activity_civireport.activity_type_id = {$openActivity}
    INNER JOIN civicrm_activity_contact ac_closed ON ac_closed.contact_id = ac_opened.contact_id AND ac_closed.record_type_id = 3 AND activity_civireport.id != ac_closed.activity_id
    INNER JOIN civicrm_activity activity_civireport1 ON activity_civireport1.id = ac_closed.activity_id AND activity_civireport1.activity_type_id = {$closedActivity}
    {$filter}
     ";
  }

  public function orderBy () {
    $this->_orderBy = 'ORDER BY activity_civireport.id ASC, activity_civireport1.id DESC';
  }

  public function groupBy() {
    $this->_groupBy = 'GROUP BY ac_opened.contact_id, ' . $this->_columns['civicrm_unit']['filters']['Region']['dbAlias'];
  }

  public function alterDisplay(&$rows) {
    foreach ($this->_noDisplay as $noDisplayField) {
      unset($this->_columnHeaders[$noDisplayField]);
    }
    return;
  }

}
