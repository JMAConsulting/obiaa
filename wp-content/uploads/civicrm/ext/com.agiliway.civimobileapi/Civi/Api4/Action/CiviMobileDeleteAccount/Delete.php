<?php

namespace Civi\Api4\Action\CiviMobileDeleteAccount;

use Civi\Api4\Generic\BasicBatchAction;
use Civi\Api4\Generic\Result;

class Delete extends BasicBatchAction {
  
  /**
   * @param  Result  $result
   * @return Result
   */
  public function _run(Result $result) {
    $result[] = ['success'];
    
    return $result;
  }
}
