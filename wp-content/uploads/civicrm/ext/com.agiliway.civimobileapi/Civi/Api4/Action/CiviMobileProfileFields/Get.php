<?php

namespace Civi\Api4\Action\CiviMobileProfileFields;

use Civi\Api4\Generic\BasicGetAction;
use Civi\Api4\Generic\Result;
use CRM_CiviMobileAPI_Utils_Profile;
use CRM_Core_BAO_UFGroup;

class Get extends BasicGetAction {

  /**
   * @param Result $result
   * @return Result
   */
  public function _run(Result $result) {
    $params = $this->getParams();
    $formattedParams = [];
    
    foreach ($params['where'] as $item) {
      $formattedParams[$item[0]] = $item[2];
    }
    
    $uFJoin = civicrm_api4('UFJoin', 'get', [
      'where' => [
        ['entity_table', '=', $formattedParams["entity_table"]],
        ['entity_id', '=', $formattedParams["entity_id"]],
      ],
      'checkPermissions' => FALSE,
    ])->first();
    
    $fields = CRM_Core_BAO_UFGroup::getFields($uFJoin['uf_group_id']);
    CRM_CiviMobileAPI_Utils_Profile::prepareFields($fields);
    $result['profile_fields'] =  $fields;
    
    return $result;
  }
}
