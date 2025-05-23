<?php

class CRM_CiviMobileAPI_Hook_Pre_GroupTree {

  public static function run($formName, &$form) {
    if ($formName == 'CRM_Activity_Form_Activity' || $formName == 'CRM_Custom_Form_CustomDataByType') {
      $groupTree = $form->getVar('_groupTree');

      if (!empty($groupTree)) {
        foreach ($groupTree as $key => $customGroup) {
          if ($customGroup['name'] == CRM_CiviMobileAPI_Install_Entity_CustomGroup::SURVEY) {
            unset($groupTree[$key]);
          }
        }
        $form->setVar('_groupTree', $groupTree);
      }
    }
  }
}
