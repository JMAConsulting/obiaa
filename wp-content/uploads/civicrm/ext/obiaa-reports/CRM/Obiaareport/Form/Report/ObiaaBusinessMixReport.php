<?php
use CRM_Obiaareport_ExtensionUtil as E;

class CRM_Obiaareport_Form_Report_ObiaaBusinessMixReport extends CRM_Report_Form {

  protected $_addressField = FALSE;

  protected $_emailField = FALSE;

  protected $_summary = NULL;

  protected $_customGroupExtends = ['Organization'];
  function __construct() {
    $this->_columns =  [
      'civicrm_unit_business' => [
        'dao' => 'CRM_Biaproperty_DAO_UnitBusiness',
        'fields' => [
          'ownership_type' => [
            'required' => TRUE,
            'title' => E::ts('Business Type'),
            'dbAlias' => 'ov.name',
            'no_display' => TRUE,
          ],
          'number_of_businesses' => [
            'required' => TRUE,
            'title' => E::ts('Number of businesses'),
            'dbAlias' => 'temp.count',
            'no_display' => TRUE,
          ],
          'percentage_of_businesses' => [
            'required' => TRUE,
            'title' => E::ts('Percentage of businesses'),
            'dbAlias' => "temp.count",
            'no_display' => TRUE,
          ],
        ],
      ],
    ];
    $result = CRM_Core_DAO::executeQuery("SELECT ov.value, ov.label, ov.name FROM civicrm_option_value ov INNER JOIN civicrm_option_group og ON og.id = ov.option_group_id AND og.id = 108 ")->fetchAll();    
    $this->_columns['civicrm_unit_business']['fields']['type'] = [
      'required' => TRUE,
      'title' => E::ts('Business Type'),
      'dbAlias' => '1',
    ];
    foreach ($result as $value) {
      $this->_columns['civicrm_unit_business']['fields'][$value['name']] = [
        'required' => TRUE,
        'title' => $value['label'],
        'dbAlias' => "'" . $value['value'] . "'",
      ];
    }
    parent::__construct();
    $this->_columns['civicrm_unit_business']['filters'] =  $this->_columns['civicrm_value_business_cate_4']['filters'];
    unset($this->_columns['civicrm_value_business_cate_4']);
    $this->setVar('_params', $this->_submitValues);
  }

  public function addCustomDataToColumns($addFields = TRUE, $permCustomGroupIds = []) {
    $permCustomGroupIds = [25];
    parent::addCustomDataToColumns(FALSE, $permCustomGroupIds);
  }

  function preProcess() {
    $this->assign('reportTitle', E::ts('OBIAA Business Mix Report'));
    parent::preProcess();
  }

  function from() {
    parent::where();
    $result = CRM_Core_DAO::executeQuery("SELECT ov.value, ov.label, ov.name FROM civicrm_option_value ov INNER JOIN civicrm_option_group og ON og.id = ov.option_group_id AND og.id = 108 ")->fetchAll();
    $sqlParts = [];
    foreach ($result as $option) {
      $sqlParts[] = "(SELECT COUNT(DISTINCT ub.business_id) as count, '{$option['value']}' as val
        FROM civicrm_unit_business ub
        LEFT JOIN civicrm_value_business_deta_5 cf ON ub.business_id = cf.entity_id
        LEFT JOIN civicrm_value_ownership_dem_25 value_ownership_dem_25_civireport ON ub.business_id = value_ownership_dem_25_civireport.entity_id
        {$this->_where} AND ownership_type_16 REGEXP '([[:cntrl:]]|^){$option['value']}([[:cntrl:]]|$)'
      )";
    }
    $this->_from = sprintf("FROM civicrm_option_value ov
    INNER JOIN ( %s ) temp ON temp.val = ov.value AND ov.option_group_id = 108", implode(' UNION ', $sqlParts));
  }

  function where() {
    parent::where();
    $this->_where = '';
  }

  public function customDataFrom($joinsForFiltersOnly = FALSE) {}

  public function beginPostProcess() {
    parent::beginPostProcess();
    $this->setParams($this->controller->exportValues($this->_name));
  }

  function alterDisplay(&$rows) {
   $totalCount = CRM_Core_DAO::singleValueQuery("SELECT SUM(temp.count) {$this->_from}") ?? 0;
    $newRows = [];
    foreach ([
      'civicrm_unit_business_number_of_businesses' => 'Number of Businesses',
      'civicrm_unit_business_percentage_of_businesses' => '% of Businesses',      
    ] as $key => $label) {
      $newRows[$key] = ['civicrm_unit_business_type' =>  $label];
      foreach ($rows as $row) {
        if ($key == 'civicrm_unit_business_percentage_of_businesses') {
          $row[$key] = $row[$key] ? round((($row[$key] / $totalCount) * 100), 2) . '%' : '0.00%';
        }
        $newRows[$key]['civicrm_unit_business_' . str_replace(' ', '_', $row['civicrm_unit_business_ownership_type'])] = $row[$key];
      }
    }
    $rows = $newRows;
  }

}
