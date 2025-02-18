<?php

use CRM_Biaproperty_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Biaproperty_Form_CloseBusiness extends CRM_Core_Form {
  protected $_bid;

  protected $_uid;

  /**
   * Preprocess form.
   *
   * This is called before buildForm. Any pre-processing that
   * needs to be done for buildForm should be done here.
   *
   * This is a virtual function and should be redefined if needed.
   */
  public function preProcess() {
    parent::preProcess();

    $this->_bid = CRM_Utils_Request::retrieve('bid', 'String', $this);
    $this->_uid = CRM_Utils_Request::retrieve('uid', 'String', $this);
    CRM_Utils_System::setTitle(E::ts('Close business of %1', [1 => CRM_Contact_BAO_Contact::displayName($this->_bid)]));
  }

  public function buildQuickForm() {
    $this->add('datepicker', 'bia_closing_date', E::ts('Close in BIA Date'), [], TRUE, ['time' => FALSE]);

    //default closing date to latest closing business activity date if any or current date
    $activityDateTime = \Civi\Api4\Activity::get(FALSE)
      ->addSelect('activity_date_time')
      ->addWhere('activity_type_id:name', '=', 'Business closed')
      ->addWhere('target_contact_id', '=', $this->_bid)
      ->addOrderBy('id', 'DESC')
      ->execute()
      ->first()['activity_date_time'] ?? date('Y-m-d H:i:s');
    $defaults = ['bia_closing_date' => $activityDateTime];
    $this->setDefaults($defaults);

    $this->assign('elementNames', $this->getRenderableElementNames());
    $this->addButtons([
        [
          'type' => 'upload',
          'name' => E::ts('Close Business'),
          'isDefault' => TRUE,
        ],
        [
          'type' => 'cancel',
          'name' => E::ts('Cancel'),
        ],
      ]);

    parent::buildQuickForm();
  }

  public function postProcess() {
    $values = $this->exportValues();
    CRM_Biaproperty_Utils::closeBusiness($this->_bid, $this->_uid, $values['bia_closing_date']);
  }


  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

}
