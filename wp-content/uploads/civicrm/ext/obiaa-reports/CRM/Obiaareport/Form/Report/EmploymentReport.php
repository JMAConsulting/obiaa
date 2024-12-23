<?php
use CRM_Obiaareport_ExtensionUtil as E;

class CRM_Obiaareport_Form_Report_EmploymentReport extends CRM_Report_Form {
  protected $_addressField = FALSE;

  protected $_emailField = FALSE;

  protected $_summary = NULL;

  protected $_customGroupGroupBy = FALSE;

  protected $_customFieldColumnName;

  public $optimisedForOnlyFullGroupBy = FALSE;

  public function __construct() {
    $currentMonth = (int) date('m');
    $season = [
      1 => 'Winter_Seasonal_Workers',
      2 => 'Winter_Seasonal_Workers',
      3 => 'Spring_Seasonal_Workers',
      4 => 'Spring_Seasonal_Workers',
      5 => 'Spring_Seasonal_Workers',
      6 => 'Summer_Seasonal_Workers',
      7 => 'Summer_Seasonal_Workers',
      8 => 'Summer_Seasonal_Workers',
      9 => 'Fall_Seasonal_Workers',
      10 => 'Fall_Seasonal_Workers',
      11 => 'Fall_Seasonal_Workers',
      12 => 'Winter_Seasonal_Workers',
    ];

    $cfFilter = \Civi\Api4\CustomField::get()->addWhere('name', '=', $season[$currentMonth])->execute()->first();
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
            'dbAlias' => 'cg.entity_id',
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
    CRM_Obiaareport_Utils::addMembershipFilter($this->_columns['civicrm_unit']['filters']);
    parent::__construct();
  }

  public function preProcess() {
    $this->assign('reportTitle', E::ts('Employment Report'));
    parent::preProcess();
  }

  public function from() {
    $this->_from = "
  FROM civicrm_value_business_deta_5 cg
  LEFT JOIN civicrm_value_membership_st_12 member ON member.entity_id = cg.entity_id
  LEFT JOIN civicrm_option_value ov ON ov.value = cg.{$this->_customFieldColumnName}
    LEFT  JOIN civicrm_option_group AS og
                        ON og.id = ov.option_group_id AND og.name = 'business_category_employees_at_'
    ";
  }

  public function groupBy() {
    $this->_groupBy = 'GROUP BY ov.label, cg.entity_id';
  }

  public function alterDisplay(&$rows) {
    foreach ($this->_noDisplay as $noDisplayField) {
      unset($this->_columnHeaders[$noDisplayField]);
    }
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
        //this is for full time employee where emp range might be blank
        if (empty($row['civicrm_unit_emp_range']) && !empty($row['civicrm_unit_full_time_employees'])) {
          $row['civicrm_unit_emp_range'] = array_search($row['civicrm_unit_full_time_employees'], $options);
        }
        if (!empty($row['civicrm_unit_emp_range']) && isset($row['civicrm_unit_' . $row['civicrm_unit_emp_range']])) {
          if ($key == 'civicrm_unit_number'){
            $newRows[$key]['civicrm_unit_' . $row['civicrm_unit_emp_range']] += (int) $row['civicrm_unit_emp_count'] ?? 0;
            $total += (int) $row['civicrm_unit_emp_count'] ?? 0;
          }
          else {
            $newRows[$key]['civicrm_unit_' . $row['civicrm_unit_emp_range']] += (($options[$row['civicrm_unit_emp_range']] + $row['civicrm_unit_full_time_employees']) * $row['civicrm_unit_emp_count']) + ($row['civicrm_unit_sole_proprietor_58'] ?? 0);
            $total += (($options[$row['civicrm_unit_emp_range']] + $row['civicrm_unit_full_time_employees']) * $row['civicrm_unit_emp_count']) + ($row['civicrm_unit_sole_proprietor_58'] ?? 0);
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
