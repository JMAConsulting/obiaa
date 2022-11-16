<?php
use CRM_Obiaareport_ExtensionUtil as E;

class CRM_Obiaareport_Form_Report_EmploymentReport extends CRM_Report_Form {
  protected $_addressField = FALSE;

  protected $_emailField = FALSE;

  protected $_summary = NULL;

  protected $_customGroupGroupBy = FALSE;

  protected $_customFieldColumnName;

  public function __construct() {
    $currentQuater = ceil(date("m", time())/3);
    $season = [
      1 => 'Winter_Seasonal_Workers',
      2 => 'Spring_Seasonal_Workers',
      3 => 'Summer_Seasonal_Workers',
      4 => 'Fall_Seasonal_Workers',
    ];

    $cfFilter = \Civi\Api4\CustomField::get()->addWhere('name', '=', $season[$currentQuater])->execute()->first();
    $this->_customFieldColumnName = $cfFilter['column_name'];
    $this->_columns = array(
      'civicrm_unit' => array(
        'dao' => 'CRM_Biaproperty_DAO_Unit',
        'fields' => array(
          'emp_range' => array(
            'required' => TRUE,
            'title' => E::ts('Employment Range'),
            'dbAlias' => 'ov.name',
          ),
          'emp_count' => array(
            'required' => TRUE,
            'no_display' => TRUE,
            'title' => E::ts('Employment Count'),
            'dbAlias' => 'COUNT(DISTINCT cg.entity_id)',
          ),
          'sole_proprietor_58' => array(
            'required' => TRUE,
            'no_display' => TRUE,
            'title' => E::ts('Sole Proprietor'),
            'dbAlias' => 'SUM(sole_proprietor_58)',
          ),
          'full_time_employees' => array(
            'required' => TRUE,
            'no_display' => TRUE,
            'title' => E::ts('Full time employees'),
            'dbAlias' => 'full_time_employees_at_this_loca_77',
          ),
        ),
        'filters' => [],
      ),
    );
    $result = CRM_Core_DAO::executeQuery("SELECT ov.value, ov.label, ov.name FROM civicrm_option_value ov INNER JOIN civicrm_option_group og ON og.id = ov.option_group_id AND og.name = 'business_category_employees_at_' ORDER BY weight ")->fetchAll();
    foreach ($result as $value) {
      $this->_columns['civicrm_unit']['fields'][$value['name']] = [
        'required' => TRUE,
        'title' => $value['label'],
        'dbAlias' => "'{$value['name']}'",
      ];
    }
    $this->_columns['civicrm_unit']['fields']['total'] = [
      'required' => TRUE,
      'title' => E::ts('Total'),
      'dbAlias' => "0",
    ];
    parent::__construct();
  }

  public function preProcess() {
    $this->assign('reportTitle', E::ts('Employment Report'));
    parent::preProcess();
  }

  public function from() {
    $this->_from = "
  FROM civicrm_value_business_deta_5 cg
  LEFT JOIN civicrm_option_value ov ON ov.value = cg.{$this->_customFieldColumnName}
    LEFT  JOIN civicrm_option_group AS og
                        ON og.id = ov.option_group_id AND og.name = 'business_category_employees_at_'
    ";
  }

  public function where() {
   $this->_where = '';
  }

  public function groupBy() {
    $this->_groupBy = 'GROUP BY ov.label';
  }

  public function alterDisplay(&$rows) {
    $options = CRM_Core_OptionGroup::values('business_category_employees_at_', TRUE, FALSE, FALSE, NULL, 'name');
    // custom code to alter rows
    $total = 0;
    $newRows = [];
    foreach ([
      'civicrm_unit_number' => 'Number of Member Businesses',
      'civicrm_unit_estimate' => 'Estimated Employment',
    ] as $key => $label) {
      $total = 0;
      $newRows[$key] = ['civicrm_unit_emp_range' => '<b>' . $label . '</b>'];
      foreach ($rows as $row) {
        if (!empty($row['civicrm_unit_emp_range']) && isset($row['civicrm_unit_' . $row['civicrm_unit_emp_range']])) {
          if ($key == 'civicrm_unit_number'){
            $newRows[$key]['civicrm_unit_' . $row['civicrm_unit_emp_range']] =  (int) $row['civicrm_unit_emp_count'];
            $total += (int) $row['civicrm_unit_emp_count'];
          }
          else {
            $newRows[$key]['civicrm_unit_' . $row['civicrm_unit_emp_range']] = (($options[$row['civicrm_unit_emp_range']] + $row['full_time_employees']) * $row['civicrm_unit_emp_count']) + ($row['civicrm_unit_sole_proprietor_58'] ?? 0);
            $total += $newRows[$key]['civicrm_unit_' . $row['civicrm_unit_emp_range']];
          }
        }
      }
      $newRows[$key]['civicrm_unit_total'] = $total;
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
