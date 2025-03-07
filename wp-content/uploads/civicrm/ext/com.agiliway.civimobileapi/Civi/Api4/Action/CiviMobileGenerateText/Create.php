<?php

namespace Civi\Api4\Action\CiviMobileGenerateText;


use API_Exception;
use Civi\Api4\Generic\DAOCreateAction;
use Civi\Api4\Generic\Result;
use Civi\CiviMobileAPI\AIBasedGenerator\Utils\AIBasedGeneratorHelper;
use CRM_CiviMobileAPI_ExtensionUtil as E;


class Create extends DAOCreateAction {
  /**
   * @param Result $result
   * @return Result
   */
  public function _run(Result $result) {
    $result[] = (new AIBasedGeneratorHelper())->generate(
      $this->getParams()['values']['params'],
      $this->getParams()['values']['userInputAndData']
    );

    return $result;
  }

}

