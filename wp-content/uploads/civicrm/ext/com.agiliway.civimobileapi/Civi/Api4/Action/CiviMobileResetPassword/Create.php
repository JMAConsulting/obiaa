<?php

namespace Civi\Api4\Action\CiviMobileResetPassword;

use Civi\Api4\Generic\BasicCreateAction;
use Civi\Api4\Generic\Result;
use CRM_CiviMobileAPI_Utils_Extension;
use CRM_CiviMobileAPI_Utils_ResetPassword;
use CRM_CiviMobileAPI_ExtensionUtil as E;

class Create extends BasicCreateAction {
  
  /**
   * @param  Result  $result
   * @return Result
   */
  public function _run(Result $result) {
    if (!CRM_CiviMobileAPI_Utils_Extension::isAllowResetPassword()) {
      $result[] = ['Reset Password is not allowed'];
      
      return $result;
    }

    $email = $this->getParams()['values']['email'];
    $isMailSent = CRM_CiviMobileAPI_Utils_ResetPassword::resetPassword($email);
    
    $result[] = ['mail_sent' => $isMailSent ? 1 : 0];
    
    return $result;
  }
}
