<?php
use CRM_Obiaareport_ExtensionUtil as E;

class CRM_Obiaareport_Form_Report_ObiaaUsageReport extends CRM_Report_Form {

  protected $_addressField = FALSE;

  protected $_emailField = FALSE;

  protected $_summary = NULL;

  protected $_customGroupGroupBy = TRUE;
  public function __construct() {
    $this->_customGroupExtends = ['Contact', 'Individual', 'Organization'];
    $this->optimisedForOnlyFullGroupBy = FALSE;
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
         INNER JOIN civicrm_contact {$this->_aliases['civicrm_contact']} ON {$this->_aliases['civicrm_contact']}.id = {$this->_aliases['civicrm_value_synchronisati_26']}.entity_id {$this->_aclFrom}";


  }

  /**
   * Build the report query.
   *
   * @param bool $applyLimit
   *
   * @return string
   */
  public function buildQuery($applyLimit = TRUE) {
    $this->buildGroupTempTable();
    $this->select();
    $this->from();
    $this->buildPermissionClause();
    $this->where();
    $this->groupBy();
    $this->orderBy();

    foreach ($this->unselectedOrderByColumns() as $alias => $field) {
      $clause = $this->getSelectClauseWithGroupConcatIfNotGroupedBy($field['table_name'], $field['name'], $field);
      if (!$clause) {
        $clause = "{$field['dbAlias']} as {$alias}";
      }
      $this->_select .= ", $clause ";
    }

    if ($applyLimit && empty($this->_params['charts'])) {
      $this->limit();
    }
    CRM_Utils_Hook::alterReportVar('sql', $this, $this);
    $this->_select = str_replace('value_synchronisati_26_civireport.bia_contact_reference_102', 'GROUP_CONCAT(DISTINCT SUBSTRING_INDEX(value_synchronisati_26_civireport.bia_contact_reference_102, \'?\', 1))', $this->_select);
    $sql = "{$this->_select} {$this->_from} {$this->_where} {$this->_groupBy} {$this->_having} {$this->_orderBy} {$this->_limit}";
    $this->addToDeveloperTab($sql);
    return $sql;
  }


}
