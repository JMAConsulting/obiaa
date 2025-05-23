<?php

class CRM_CiviMobileAPI_Hook_PostProcess_ManageEventLocation {

  /**
   * Removes all venues in EventSession if loc_block_id was changed.
   */
  public static function run($formName, &$form) {
    if ($formName == 'CRM_Event_Form_ManageEvent_Location') {
      try {
        $event = CRM_Event_BAO_Event::findById($form->_id);
        if (!empty($event->loc_block_id) && $event->loc_block_id != $form->getVar('_oldLocBlockId')) {
          CRM_CiviMobileAPI_BAO_EventSession::deleteAllVenues($event->id);
        }
      } catch (Exception $e) {
      }
    }
  }
}
