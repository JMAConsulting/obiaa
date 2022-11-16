<?php
use CRM_Obiaareport_ExtensionUtil as E;

class CRM_Obiaareport_Form_Report_TurnoverReport extends CRM_Report_Form {

  protected $_addressField = FALSE;

  protected $_emailField = FALSE;

  protected $_summary = NULL;

  protected $_customGroupGroupBy = FALSE;

  public function __construct() {
    $this->_columns = array(
      'civicrm_unit' => array(
        'dao' => 'CRM_Biaproperty_DAO_Unit',
        'fields' => array(
          'year' => array(
            'required' => TRUE,
            'title' => E::ts('Year'),
            'dbAlias' => 'YEAR(activity_date_time)',
          ),
          'activity_type_id' => array(
            'required' => TRUE,
            'no_display' => TRUE,
            'title' => E::ts('Year'),
            'dbAlias' => 'activity_type_id',
          ),
          'count' => array(
            'required' => TRUE,
            'no_display' => TRUE,
            'title' => E::ts('New Business Openings'),
            'dbAlias' => 'COUNT(DISTINCT unit_civireport.id)',
          ),
        ),
        'filters' => [
          'activity_date_time' => [
            'type' => CRM_Utils_Type::T_DATE,
            'title' => E::ts('Period date'),
            'default' => 'this.year',
            'operatorType' => CRM_Report_Form::OP_DATE,
          ],
        ],
      ),
    );

    if ((!empty($_POST['activity_date_time_relative']) || !empty($_POST['activity_date_time_from']) || !empty($_POST['activity_date_time_to']))) {
      [$from, $to] = $this->getFromTo($_POST['activity_date_time_relative'], $_POST['activity_date_time_from'], $_POST['activity_date_time_to']);
      $from = substr($from, 0, 4);
      $to = substr($to, 0, 4);
      for ($i = $from; $i <= $to; $i++) {
        if ($this->_columns['civicrm_unit']['fields'][$i]) {continue;}
        $this->_columns['civicrm_unit']['fields'][$i] = [
          'required' => TRUE,
          'title' => $i,
          'dbAlias' => '0',
        ];
      }
    }
    else {
      foreach ([date("Y",strtotime("-1 year")), date("Y")] as $date) {
        $this->_columns['civicrm_unit']['fields'][$date] = [
          'required' => TRUE,
          'title' => $date,
          'dbAlias' => '0',
        ];
      }
   }
    parent::__construct();
  }

  public function preProcess() {
    $this->assign('reportTitle', E::ts('Turnover Report'));
    parent::preProcess();
  }

  public function from() {
    $this->_from = " FROM  civicrm_activity unit_civireport ";
  }

  public function where() {
   parent::where();
   $this->_where .= sprintf(' AND activity_type_id IN (%s)' , implode(', ', [
     CRM_Core_PseudoConstant::getKey('CRM_Activity_BAO_Activity', 'activity_type_id', 'Business opened'),
     CRM_Core_PseudoConstant::getKey('CRM_Activity_BAO_Activity', 'activity_type_id', 'Business closed'),
   ]));
  }

  public function groupBy() {
    $this->_groupBy = 'GROUP BY YEAR(activity_date_time), activity_type_id';
  }

  public function alterDisplay(&$rows) {
    foreach ($this->_noDisplay as $noDisplayField) {
       unset($this->_columnHeaders[$noDisplayField]);
    }
    $years = [];
    // custom code to alter rows
    $newRows = [];
    foreach ([
      'civicrm_unit_open' => 'New Business Openings',
      'civicrm_unit_close' => 'New Business Closings',
      'civicrm_unit_turnover' => 'Turnover',
    ] as $key => $label) {
      $newRows[$key] = ['civicrm_unit_year' => '<b>' . $label . '</b>'];
      foreach ($rows as $row) {
        if (!array_search($row['civicrm_unit_year'], $years)) {$years[$row['civicrm_unit_year']] = $row['civicrm_unit_year'];}
        if ($key == 'civicrm_unit_turnover') {
          $newRows[$key]['civicrm_unit_' . $row['civicrm_unit_year']] = ($newRows['civicrm_unit_close']['civicrm_unit_' . $row['civicrm_unit_year']] ?: 0) - ($newRows['civicrm_unit_open']['civicrm_unit_' . $row['civicrm_unit_year']] ?: 0);
        }
        elseif ($key == 'civicrm_unit_open' && $row['civicrm_unit_activity_type_id'] == CRM_Core_PseudoConstant::getKey('CRM_Activity_BAO_Activity', 'activity_type_id', 'Business opened')) {
          $newRows[$key]['civicrm_unit_' . $row['civicrm_unit_year']] = $row['civicrm_unit_count'] ?? 0;
        }
        elseif ($key == 'civicrm_unit_close' && $row['civicrm_unit_activity_type_id'] == CRM_Core_PseudoConstant::getKey('CRM_Activity_BAO_Activity', 'activity_type_id', 'Business closed')) {
          $newRows[$key]['civicrm_unit_' . $row['civicrm_unit_year']] = $row['civicrm_unit_count'] ?? 0;
        }
      }
    }
    $rows = $newRows;
    foreach (array_keys($this->_columnHeaders) as $header) {
      foreach ($rows as $key => $row) {
        if (!isset($row[$header])) {
          $rows[$key][$header] = '0';
        }
      }
    }
  }

}
