<?php

class CRM_CivirulesActions_Contact_Subtype extends CRM_Civirules_Action {

  /**
   * Method processAction to execute the action
   *
   * @param CRM_Civirules_TriggerData_TriggerData $triggerData
   * @access public
   *
   */
  public function processAction(CRM_Civirules_TriggerData_TriggerData $triggerData) {
    $contactId = $triggerData->getContactId();

    $subTypes = CRM_Contact_BAO_Contact::getContactSubType($contactId);
    $contactType = CRM_Contact_BAO_Contact::getContactType($contactId);

    $changed = false;
    $action_params = $this->getActionParameters();
    foreach($action_params['sub_type'] as $sub_type) {
      if (CRM_Contact_BAO_ContactType::isExtendsContactType($sub_type, $contactType)) {
        if (!in_array($sub_type, $subTypes)) {
          $subTypes[] = $sub_type;
          $changed = true;
        }
      }
    }
    if ($changed) {
      $params['id'] = $contactId;
      $params['contact_id'] = $contactId;
      $params['contact_type'] = $contactType;
      $params['contact_sub_type'] = $subTypes;
      CRM_Contact_BAO_Contact::writeRecord($params);
    }
  }

  /**
   * Returns condition data as an array and ready for export.
   * E.g. replace ids for names.
   *
   * @return array
   */
  public function exportActionParameters() {
    $action_params = parent::exportActionParameters();
    foreach($action_params['sub_type'] as $i=>$j) {
      try {
        $action_params['sub_type'][$i] = civicrm_api3('ContactType', 'getvalue', [
          'return' => 'name',
          'id' => $j,
        ]);
      } catch (CRM_Core_Exception $e) {
      }
    }
    return $action_params;
  }

  /**
   * Returns condition data as an array and ready for import.
   * E.g. replace name for ids.
   *
   * @return string
   */
  public function importActionParameters($action_params = NULL) {
    foreach($action_params['sub_type'] as $i=>$j) {
      try {
        $action_params['sub_type'][$i] = civicrm_api3('ContactType', 'getvalue', [
          'return' => 'id',
          'name' => $j,
        ]);
      } catch (CRM_Core_Exception $e) {
      }
    }
    return parent::importActionParameters($action_params);
  }

  /**
   * Method to return the url for additional form processing for action
   * and return false if none is needed
   *
   * @param int $ruleActionId
   * @return bool
   * @access public
   */
  public function getExtraDataInputUrl($ruleActionId) {
    return $this->getFormattedExtraDataInputUrl('civicrm/civirule/form/action/contact/subtype', $ruleActionId);
  }

  /**
   * Returns a user friendly text explaining the condition params
   * e.g. 'Older than 65'
   *
   * @return string
   * @access public
   */
  public function userFriendlyConditionParams() {
    $params = $this->getActionParameters();
    $label = ts('Set contact subtype to: ');
    $subTypeLabels = array();
    $subTypes = CRM_Contact_BAO_ContactType::contactTypeInfo();
    foreach($params['sub_type'] as $sub_type) {
      $subTypeLabels[] = $subTypes[$sub_type]['parent_label'].' - '.$subTypes[$sub_type]['label'];
    }
    $label .= implode(', ', $subTypeLabels);
    return $label;
  }

}
