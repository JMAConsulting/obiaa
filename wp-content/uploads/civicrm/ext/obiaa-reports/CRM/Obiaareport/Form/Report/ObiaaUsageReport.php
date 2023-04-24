<?php
use CRM_Obiaareport_ExtensionUtil as E;

class CRM_Obiaareport_Form_Report_ObiaaUsageReport extends CRM_Report_Form {

  protected $_addressField = FALSE;

  protected $_emailField = FALSE;

  protected $_summary = NULL;

  protected $_customGroupGroupBy = TRUE;
  public function __construct() {
    $thid->_customGroupExtends = ['Contact', 'Individual', 'Organization'];
    $this->_columns = array(
      'civicrm_contact' => array(
        'dao' => 'CRM_Contact_DAO_Contact',
        'fields' => array(
          'contact_sub_type' => array(
            'title' => E::ts('Number of Property owners'),
            'required' => TRUE,
            'default' => TRUE,
            'no_repeat' => TRUE,
            'dbAlias' => 'SUM(IF(contact_civireport.contact_sub_type LIKE \'%Members_Property_Owners_%\', 1, 0))',
          ),
          'contact_sub_type_business' => array(
            'title' => E::ts('Number of Business Members'),
            'required' => TRUE,
            'default' => TRUE,
            'no_repeat' => TRUE,
            'dbAlias' => 'SUM(IF(contact_civireport.contact_sub_type LIKE \'%Members_Businesses_%\', 1, 0))',
          ),
        ),
        'filters' => [],
        'grouping' => 'contact-fields',
      ),
    );
    $this->_groupFilter = TRUE;
    $this->_tagFilter = TRUE;
    parent::__construct();
    $this->_columns['civicrm_value_synchronisati_26']['fields']['custom_101']['required'] = TRUE;
    $this->_columns['civicrm_value_synchronisati_26']['fields']['custom_102']['required'] = TRUE;
    $this->_columns['civicrm_value_synchronisati_26']['group_bys']['custom_101']['required'] = TRUE;
  }

  public function preProcess() {
    $this->assign('reportTitle', E::ts('Obiaa Usage Report'));
    parent::preProcess();
  }

  public function from() {

    $this->_from = "
         FROM civicrm_value_synchronisati_26 {$this->_aliases['civicrm_value_synchronisati_26']} 
         INNER JOIN civicrm_contact {$this->_aliases['civicrm_contact']} ON
           {$this->_aliases['civicrm_contact']}.id = {$this->_aliases ['civicrm_value_synchronisati_26']}.entity_id {$this->_aclFrom}";


  }

  /**
   * Add field specific select alterations.
   *
   * @param string $tableName
   * @param string $tableKey
   * @param string $fieldName
   * @param array $field
   *
   * @return string
   */
  public function selectClause(&$tableName, $tableKey, &$fieldName, &$field) {
    if ($tableName === 'civicrm_value_synchronisati_26' && $fieldName === 'bia_contact_reference_102') {
      return 'GROUP_CONCAT(DISTINCT SUBSTRING_INDEX(' . $this->_aliases['civicrm_value_synchronisati_26'] . '.bia_contact_reference_102, \'?\', 1))';
    }
    return parent::selectClause($tableName, $tableKey, $fieldName, $field);
  }

}
