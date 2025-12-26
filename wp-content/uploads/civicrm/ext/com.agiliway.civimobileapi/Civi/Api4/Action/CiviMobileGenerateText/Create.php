<?php

namespace Civi\Api4\Action\CiviMobileGenerateText;

use Civi\Api4\Generic\DAOCreateAction;
use Civi\Api4\Generic\Result;
use Civi\CiviMobileAPI\AIBasedGenerator\Utils\AIBasedGeneratorHelper;

class Create extends DAOCreateAction {

  /**
   * @param Result $result
   *
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

