<?php

namespace Civi\Api4\Action\CiviMobileCoreSelectValues;

use Civi\Api4\Generic\BasicGetAction;
use Civi\Api4\Generic\Result;
use CRM_Core_SelectValues;

class Get extends BasicGetAction {

  public function _run(Result $result) {
    $type = $this->getWhere()[0][2];

    $options = [];

    if ($type == 'groupVisibility') {
      $options = CRM_Core_SelectValues::groupVisibility();
    }
    elseif ($type == 'groupContactStatus') {
      $options = CRM_Core_SelectValues::groupContactStatus();
    }
    elseif ($type == 'contactTypes') {
      $contactTypes = civicrm_api4('ContactType', 'get', [
        'limit' => 0,
        'checkPermissions' => FALSE,
      ])->getArrayCopy();

      $result->exchangeArray($contactTypes);
      return;
    }

    $formattedOptions = [];

    foreach ($options as $key => $value) {
      $formattedOptions[] = [
        'value' => $key,
        'label' => $value,
      ];
    }

    $result->exchangeArray($formattedOptions);
  }

}
