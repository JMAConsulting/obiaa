<?php

namespace Civi\Api4\Action\CiviMobileGenerateEventDescription;


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

    $title = $this->getParams()['values']['title'];
    $type = $this->getParams()['values']['type'];

    $userInputAndData = $this->getParams()['values']['userInputAndData'];

    $result[] = (new AIBasedGeneratorHelper())->generate($title, $type, $userInputAndData);

    return $result;
  }

}

