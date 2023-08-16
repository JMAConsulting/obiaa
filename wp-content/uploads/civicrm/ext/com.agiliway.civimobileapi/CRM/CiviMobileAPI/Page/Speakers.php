<?php

class CRM_CiviMobileAPI_Page_Speakers extends CRM_Core_Page {

  public function run() {
    $eventId = CRM_Utils_Request::retrieve('event_id', 'Positive');
    $this->assign('event_id', $eventId);
    return parent::run();
  }

}
