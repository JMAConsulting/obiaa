<?php

namespace Civi\Api4\Action\CiviMobileTabs;

use Civi\Api4\Generic\BasicGetAction;
use CRM_CiviMobileAPI_Utils_Extension;

class Get extends BasicGetAction {

  /**
   * Returns results to api
   *
   * @return array
   */
  protected function getRecords() {
    return CRM_CiviMobileAPI_Utils_Extension::getActiveCiviMobileTabs();
  }

}
