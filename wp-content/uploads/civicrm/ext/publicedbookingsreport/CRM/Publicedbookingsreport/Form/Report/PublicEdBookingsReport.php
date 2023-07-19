<?php
use CRM_Publicedbookingsreport_ExtensionUtil as E;

class CRM_Publicedbookingsreport_Form_Report_PublicEdBookingsReport extends CRM_Report_Form {

  protected $_addressField = FALSE;

  protected $_emailField = FALSE;

  protected $_summary = NULL;

  protected $_customGroupGroupBy = FALSE; 
  function __construct() {
    $this->_columns = array(
      'civicrm_activity' => array(
        'dao' => 'CRM_Activity_DAO_Activity',
        'fields' => array(
          'activity_date_time1' => [
            'title' => ts('Activity Month'),
            'required' => TRUE,
            'dbAlias' => 'MONTH(activity_date_time)',
          ],
          'activity_date_time' => [
            'title' => ts('Activity Year'),
            'required' => TRUE,
            'dbAlias' => 'Year(activity_date_time)',
          ],
          'id' => [
            'no_display' => TRUE,
            'title' => ts('Activity ID'),
            'required' => TRUE,
          ],
        ),
      ),
      'civicrm_value_booking_infor_2' => [
        'dao' => 'CRM_Contact_DAO_Contact',
        'extends' => 'Activity',
        'fields' => array(
          'presentation_topics_40' => [
            'title' => ts('Presentation Topics'),
            'required' => TRUE,
          ],
          'contact_method_88' => [
            'title' => ts('Delivery Format'),
            'required' => TRUE,
          ],
        ),
      ],
      'civicrm_contact' => [
        'dao'     => 'CRM_Contact_DAO_Contact',
        'fields' => [
          'display_name'   => [
            'title'     => ts('Activity Contact'),
            'alias'     => 'ac',
            'required' => TRUE,
          ]
        ]
      ],
    );
    $this->_groupFilter = TRUE;
    $this->_tagFilter = TRUE;
    parent::__construct();
  }

  function preProcess() {
    $this->assign('reportTitle', E::ts('Public Ed Bookings Report'));
    parent::preProcess();
  }

  function from() {
    $this->_from = NULL;
    $this->_from = "
    FROM civicrm_activity {$this->_aliases['civicrm_activity']}
    LEFT JOIN civicrm_value_booking_infor_2 {$this->_aliases['civicrm_value_booking_infor_2']} ON {$this->_aliases['civicrm_value_booking_infor_2']}.entity_id = {$this->_aliases['civicrm_activity']}.id
    LEFT JOIN civicrm_activity_contact assignee ON assignee.activity_id = {$this->_aliases['civicrm_activity']}.id AND assignee.record_type_id = 2
    LEFT JOIN civicrm_activity_contact target ON target.activity_id = {$this->_aliases['civicrm_activity']}.id AND target.record_type_id = 3
    LEFT JOIN civicrm_activity_contact source ON source.activity_id = {$this->_aliases['civicrm_activity']}.id AND source.record_type_id = 1
    LEFT JOIN civicrm_contact ac ON ac.id = assignee.contact_id
    LEFT JOIN civicrm_contact tc ON tc.id = target.contact_id
    LEFT JOIN civicrm_contact sc ON sc.id = source.contact_id
    ";

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
  function selectClause(&$tableName, $tableKey, &$fieldName, &$field) {
    return parent::selectClause($tableName, $tableKey, $fieldName, $field);
  }

  /**
   * Add field specific where alterations.
   *
   * This can be overridden in reports for special treatment of a field
   *
   * @param array $field Field specifications
   * @param string $op Query operator (not an exact match to sql)
   * @param mixed $value
   * @param float $min
   * @param float $max
   *
   * @return null|string
   */
  public function whereClause(&$field, $op, $value, $min, $max) {
    return parent::whereClause($field, $op, $value, $min, $max);
  }

  public function where() {
    $this->_where = 'WHERE presentation_topics_40 IS NOT NULL || presentation_topics_40 <> "" AND activity_type_id IN (199,196)';

  }

  function alterDisplay(&$rows) {

    // custom code to alter rows
    $entryFound = FALSE;
    $checkList = array();
    // CRM_Core_Error::debug($rows); exit();
    foreach ($rows as $rowNum => $row) {

      // if (!empty($this->_noRepeats) && $this->_outputMode != 'csv') {
      //   // not repeat contact display names if it matches with the one
      //   // in previous row
      //   $repeatFound = FALSE;
      //   foreach ($row as $colName => $colVal) {
      //     if (CRM_Utils_Array::value($colName, $checkList) &&
      //       is_array($checkList[$colName]) &&
      //       in_array($colVal, $checkList[$colName])
      //     ) {
      //       $rows[$rowNum][$colName] = "";
      //       $repeatFound = TRUE;
      //     }
      //     if (in_array($colName, $this->_noRepeats)) {
      //       $checkList[$colName][] = $colVal;
      //     }
      //   }
      // }

      // if (array_key_exists('civicrm_membership_membership_type_id', $row)) {
      //   if ($value = $row['civicrm_membership_membership_type_id']) {
      //     $rows[$rowNum]['civicrm_membership_membership_type_id'] = CRM_Member_PseudoConstant::membershipType($value, FALSE);
      //   }
      //   $entryFound = TRUE;
      // }

      // if (array_key_exists('civicrm_address_state_province_id', $row)) {
      //   if ($value = $row['civicrm_address_state_province_id']) {
      //     $rows[$rowNum]['civicrm_address_state_province_id'] = CRM_Core_PseudoConstant::stateProvince($value, FALSE);
      //   }
      //   $entryFound = TRUE;
      // }

      // if (array_key_exists('civicrm_address_country_id', $row)) {
      //   if ($value = $row['civicrm_address_country_id']) {
      //     $rows[$rowNum]['civicrm_address_country_id'] = CRM_Core_PseudoConstant::country($value, FALSE);
      //   }
      //   $entryFound = TRUE;
      // }

      // if (array_key_exists('civicrm_contact_sort_name', $row) &&
      //   $rows[$rowNum]['civicrm_contact_sort_name'] &&
      //   array_key_exists('civicrm_contact_id', $row)
      // ) {
      //   $url = CRM_Utils_System::url("civicrm/contact/view",
      //     'reset=1&cid=' . $row['civicrm_contact_id'],
      //     $this->_absoluteUrl
      //   );
      //   $rows[$rowNum]['civicrm_contact_sort_name_link'] = $url;
      //   $rows[$rowNum]['civicrm_contact_sort_name_hover'] = E::ts("View Contact Summary for this Contact.");
      //   $entryFound = TRUE;
      // }

      // if (!$entryFound) {
      //   break;
      // }
    }
  }

}
