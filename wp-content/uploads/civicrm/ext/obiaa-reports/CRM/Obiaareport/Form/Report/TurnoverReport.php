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
          'count' => array(
            'required' => TRUE,
            'title' => E::ts('Year'),
            'dbAlias' => 'COUNT(DISTINCT entity_id)',
          ),
          'open_year' => array(
            'required' => TRUE,
            'no_display' => TRUE,
            'title' => E::ts('Open Year'),
            'dbAlias' => 'YEAR(open_date_14)',
          ),
          'close_year' => array(
            'required' => TRUE,
            'no_display' => TRUE,
            'title' => E::ts('Close Year'),
            'dbAlias' => 'YEAR(close_date_15)',
          ),
        ),
        'filters' => [],
      ),
    );

    $tableName = \Civi\Api4\CustomGroup::get(FALSE)
      ->addWhere('name', '=', 'Business_Details')
      ->execute()->first()['table_name'];
    $result = CRM_Core_DAO::executeQuery("SELECT DISTINCT YEAR(open_date_14) as open_year, YEAR(close_date_15) as close_year FROM $tableName WHERE open_date_14 IS NOT NULL OR close_date_15 IS NOT NULL")->fetchAll();
    $dates = [];
    foreach ($result as $value) {
      if (!empty($value['open_year'])) {
        $dates[$value['open_year']] = $value['open_year'];
      }
      if (!empty($value['close_year'])) {
        $dates[$value['close_year']] = $value['close_year'];
      }
    }
    ksort($dates);
    foreach ($dates as $date) {
      $this->_columns['civicrm_unit']['fields'][$date] = [
        'title' => $date,
        'dbAlias' => '0',
      ];
    }

    parent::__construct();
  }

  public function preProcess() {
    $this->assign('reportTitle', E::ts('Turnover Report'));
    parent::preProcess();
  }

  public function from() {
    $tableName = \Civi\Api4\CustomGroup::get(FALSE)
      ->addWhere('name', '=', 'Business_Details')
      ->execute()->first()['table_name'];
    $this->_from = " FROM $tableName cg";
  }

  public function groupBy() {
    $this->_groupBy = 'GROUP BY YEAR(open_date_14), YEAR(close_date_15)';
  }

  public function where() {
    parent::where();
    unset($this->_params['fields']['count']);
    unset($this->_params['fields']['year']);
    if (!empty($this->_params['fields'])) {
      $years = array_keys($this->_params['fields']);
      if (!empty($years)) {
        $this->_where .= sprintf(' AND (YEAR(open_date_14) IN (%s) OR YEAR(close_date_15) IN (%s))', implode(', ', $years), implode(', ', $years));
      }
    }
  }

  public function alterDisplay(&$rows) {
    foreach ($this->_noDisplay as $noDisplayField) {
      unset($this->_columnHeaders[$noDisplayField]);
    }
    $years = $newRows = [];
    foreach ([
      'civicrm_unit_open' => 'New Business Openings',
      'civicrm_unit_close' => 'New Business Closings',
    ] as $key => $label) {
      $newRows[$key] = ['civicrm_unit_count' => '<b>' . $label . '</b>'];
      foreach ($rows as $row) {
        if (!array_search($row['civicrm_unit_open_year'], $years)) {$years[$row['civicrm_unit_open_year']] = $row['civicrm_unit_open_year'];}
        if (!array_search($row['civicrm_unit_close_year'], $years)) {$years[$row['civicrm_unit_close_year']] = $row['civicrm_unit_close_year'];}
        if ($key == 'civicrm_unit_open' && !empty($row['civicrm_unit_open_year'])) {
          $newRows[$key]['civicrm_unit_' . $row['civicrm_unit_open_year']] = $row['civicrm_unit_count'] ?? 0;
        }
        elseif ($key == 'civicrm_unit_close' && !empty($row['civicrm_unit_close_year'])) {
          $newRows[$key]['civicrm_unit_' . $row['civicrm_unit_close_year']] = $row['civicrm_unit_count'] ?? 0;
        }
      }
    }
    $newRows['civicrm_unit_turnover'] = [];
    foreach ($newRows['civicrm_unit_open'] as $key => $value) {
      if ($key != 'civicrm_unit_count') {
        $year = str_replace('civicrm_unit_', '', $key);
        $newRows['civicrm_unit_turnover'][$key] = $value - ($newRows['civicrm_unit_close'][$key] ?? 0);
      }
      else {
        $newRows['civicrm_unit_turnover'][$key] = '<b>Turnover</b>';
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
