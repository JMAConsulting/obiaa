<?php

use CRM_Obiaareport_ExtensionUtil as E;

class CRM_Obiaareport_Form_Report_ObiaaCommercialSpaceReport extends CRM_Report_Form {
  protected $_addressField = FALSE;

  protected $_emailField = FALSE;

  protected $_summary = NULL;

  protected $_customGroupGroupBy = FALSE;

  public function __construct() {
    $totalCount = CRM_Core_DAO::singleValueQuery("SELECT COUNT(DISTINCT id) FROM civicrm_unit");
    $sum = CRM_Core_DAO::singleValueQuery("SELECT SUM(unit_size) FROM civicrm_unit");
    $cfFilter = \Civi\Api4\CustomField::get()->addWhere('name', '=', 'What_region_is_this_BIA_in_')->execute()->first();
    $this->_columns = [
      'civicrm_unit' => [
        'dao' => 'CRM_Biaproperty_DAO_Unit',
        'fields' => [
          'unit_status' => [
            'required' => TRUE,
            'title' => E::ts('Unit Status'),
            'dbAlias' => 'ov.name',
            'no_display' => TRUE,
          ],
          'count' => [
            'required' => TRUE,
            'title' => E::ts('Number of Units'),
            'dbAlias' => 'COUNT(DISTINCT u.id)',
            'no_display' => TRUE,
          ],
          'percentage' => [
            'required' => TRUE,
            'title' => E::ts('Percentage of Units'),
            'dbAlias' => "(COUNT(u.id) / $totalCount) * 100",
            'no_display' => TRUE,
          ],
          'unit_size' => [
            'required' => TRUE,
            'title' => E::ts('Number of Units'),
            'dbAlias' => 'SUM(u.unit_size)',
            'no_display' => TRUE,
          ],
          'percentage_of_size' => [

            'required' => TRUE,
            'title' => E::ts('Percentage of Space'),
            'dbAlias' => "(SUM(u.unit_size) / $sum) * 100",
            'no_display' => TRUE,
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
    $result = CRM_Core_DAO::executeQuery("SELECT ov.value, ov.label, ov.name FROM civicrm_option_value ov INNER JOIN civicrm_option_group og ON og.id = ov.option_group_id AND og.name = 'unit_status' ")->fetchAll();
    $this->_columns['civicrm_unit']['fields']['status'] = [
      'required' => TRUE,
      'title' => E::ts('Unit Status'),
      'dbAlias' => '1',
    ];
    foreach ($result as $value) {
      $this->_columns['civicrm_unit']['fields'][$value['name']] = [
        'required' => TRUE,
        'title' => $value['label'],
        'dbAlias' => $value['value'],
      ];
    }
    $this->_columns['civicrm_unit']['fields']["total"] = [
      'title' => "Total Vacant",
      'dbAlias' => '1',

    ];
    parent::__construct();
  }

  public function preProcess() {
    $this->assign('reportTitle', E::ts('OBIAA Commercial Space Report'));
    parent::preProcess();
  }

  public function from() {
    parent::where();
    $join = empty($this->_whereClauses) ? 'LEFT' : 'INNER';
    $part = CRM_Obiaareport_Utils::addMembershipTableJoin('Property');
    $this->_from = "
  FROM  civicrm_unit u
             INNER JOIN civicrm_option_value AS ov
                        ON ov.value = u.unit_status

             INNER  JOIN civicrm_option_group AS og
                        ON og.id = ov.option_group_id AND og.name = 'unit_status'
            {$part}
    ";
  }

  public function where() {
   $this->_where = '';
  }

  public function groupBy() {
    $this->_groupBy = 'GROUP BY ov.label';
  }

  public function alterDisplay(&$rows) {
    // custom code to alter rows
     foreach ($this->_noDisplay as $noDisplayField) {
       unset($this->_columnHeaders[$noDisplayField]);
    }
    $newRows = [];
    foreach ([
      'civicrm_unit_count' => 'Number of Units',
      'civicrm_unit_percentage' => 'Percentage of Units',
      'civicrm_unit_unit_size' => 'Sum of Size (Sq Ft)',
      'civicrm_unit_percentage_of_size' => 'Percentage of Space',

    ] as $key => $label) {
      $newRows[$key] = ['civicrm_unit_status' =>  $label];
      $total = array_sum(array_column($rows, $key));

      foreach ($rows as $row) {
        $newRows[$key]['civicrm_unit_' . $row['civicrm_unit_unit_status']] = round($row[$key],2);

        if($row["civicrm_unit_unit_status"] == "Occupied"){
          $total -= $row[$key];
        }
        $newRows[$key]['civicrm_unit_total'] = round($total,2) ;
      }
    }
$rows = $newRows;
    //CRM_Core_Error::debug('colHeaders', $rows);
    //CRM_Core_Error::debug('total', array_sum($totalUnitCount));

return;


  }

}
