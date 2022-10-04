<?php


trait CRM_Biaproperty_Form_PropertyFormTestTrait {

  /**
   * Instantiate form object.
   *
   * We need to instantiate the form to run preprocess, which means we have to trick it about the request method.
   *
   * @param string $class
   *   Name of form class.
   *
   * @param array $formValues
   *
   * @param string $pageName
   *
   * @param array $searchFormValues
   *   Values for the search form if the form is a task eg.
   *   for selected ids 6 & 8:
   *   [
   *      'radio_ts' => 'ts_sel',
   *      'task' => CRM_Member_Task::PDF_LETTER,
   *      'mark_x_6' => 1,
   *      'mark_x_8' => 1,
   *   ]
   *
   * @return \CRM_Core_Form
   */
  public function getFormObject($class, $formValues = [], $pageName = '', $searchFormValues = []) {
    $_POST = $formValues;
    /* @var CRM_Core_Form $form */
    $form = new $class();
    $_SERVER['REQUEST_METHOD'] = 'GET';
    switch ($class) {
      case strpos($class, '_Form_') !== FALSE:
        $form->controller = new CRM_Core_Controller_Simple($class, $pageName);
        break;

      default:
        $form->controller = new CRM_Core_Controller();
    }
    if (!$pageName) {
      $pageName = $form->getName();
    }
    $form->controller->setStateMachine(new CRM_Core_StateMachine($form->controller));
    $_SESSION['_' . $form->controller->_name . '_container']['values'][$pageName] = $formValues;
    if ($searchFormValues) {
      $_SESSION['_' . $form->controller->_name . '_container']['values']['Search'] = $searchFormValues;
    }
    if (isset($formValues['_qf_button_name'])) {
      $_SESSION['_' . $form->controller->_name . '_container']['_qf_button_name'] = $formValues['_qf_button_name'];
    }
    return $form;
  }

}
