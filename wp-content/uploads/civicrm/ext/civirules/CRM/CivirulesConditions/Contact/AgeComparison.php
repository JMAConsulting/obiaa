<?php
/**
 * Class for CiviRules AgeComparison (extending generic ValueComparison)
 *
 * @author Jaap Jansma (CiviCooP) <jaap.jansma@civicoop.org>
 * @license AGPL-3.0
 */

use CRM_Civirules_ExtensionUtil as E;

class CRM_CivirulesConditions_Contact_AgeComparison extends CRM_CivirulesConditions_Generic_ValueComparison {

  /**
   * Returns value of the field
   *
   * @param object CRM_Civirules_TriggerData_TriggerData $triggerData
   * @return mixed
   * @access protected
   */
  protected function getFieldValue(CRM_Civirules_TriggerData_TriggerData $triggerData) {
    $sourceBirthDate = civicrm_api3('Contact', 'getvalue', array('id' => $triggerData->getContactId(), 'return' => 'birth_date'));
    if ($sourceBirthDate) {
      $birthDate = new DateTime($sourceBirthDate);
      return $birthDate->diff(new DateTime('now'))->y;
    }
    return false; //undefined birth date
  }

  /**
   * Returns a user friendly text explaining the condition params
   * e.g. 'Older than 65'
   *
   * @return string
   * @access public
   */
  public function userFriendlyConditionParams() {
    switch ($this->getOperator()) {
      case '=':
        return E::ts('Age is %1', [1 => $this->getComparisonValue()]);
      case '>':
        return E::ts('Age is older than %1', [1 => $this->getComparisonValue()]);
      case '<':
        return E::ts('Age is younger than %1', [1 => $this->getComparisonValue()]);
      case '>=':
        return E::ts('Age is %1 or older than %1', [1 => $this->getComparisonValue()]);
      case '<=':
        return E::ts('Age is %1 or younger than %1', [1 => $this->getComparisonValue()]);
        break;
      case '!=':
        return E::ts('Age is not %1', [1 => $this->getComparisonValue()]);
    }
    return '';
  }

  /**
   * This function validates whether this condition works with the selected trigger.
   *
   * This function could be overriden in child classes to provide additional validation
   * whether a condition is possible in the current setup. E.g. we could have a condition
   * which works on contribution or on contributionRecur then this function could do
   * this kind of validation and return false/true
   *
   * @param CRM_Civirules_Trigger $trigger
   * @param CRM_Civirules_BAO_Rule $rule
   * @return bool
   */
  public function doesWorkWithTrigger(CRM_Civirules_Trigger $trigger, CRM_Civirules_BAO_Rule $rule) {
    return $trigger->doesProvideEntity('Contact');
  }

  protected function getComparisonValue() {
    return $this->conditionParams['value'];
  }
}
