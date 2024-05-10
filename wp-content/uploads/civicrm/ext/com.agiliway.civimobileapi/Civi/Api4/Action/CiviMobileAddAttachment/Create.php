<?php

namespace Civi\Api4\Action\CiviMobileAddAttachment;

use Civi;
use API_Exception;
use Civi\Api4\Generic\Result;
use Civi\Api4\Generic\BasicCreateAction;
use CRM_CiviMobileAPI_ExtensionUtil as E;

class Create extends BasicCreateAction {
  
  /**
   * @param  Result  $result
   * @return Result
   * @throws API_Exception
   */
  public function _run(Result $result) {
    $entityId = $this->getValue('entity_id');
    $entityTable = $this->getValue('entity_table');
    $maxFileSize = Civi::settings()->get('maxFileSize');
    $maxNumAttachments = Civi::settings()->get('max_attachments');
    
    $isFileUploaded = is_uploaded_file($_FILES['files']['tmp_name'][0]) && $_FILES['files']['error'][0] != 4;
    
    if ($isFileUploaded) {
      $fileCount = count($_FILES['files']['name']);
      
      $attachmentCount = civicrm_api3('Attachment', 'getcount', [
        'entity_table' => $entityTable,
        'entity_id' => $entityId,
      ]);
      
      if ($attachmentCount >= $maxNumAttachments) {
        throw new api_Exception(E::ts('Cannot add more attachments. Maximum limit reached'));
      }
      
      $remainingAttachments = $maxNumAttachments - $attachmentCount;
      
      if ($fileCount > $remainingAttachments) {
        throw new api_Exception(E::ts('You attempted to add ' . $fileCount . ' attachments, but you can add only ' . $remainingAttachments . ' more attachments'));
      }
      
      foreach ($_FILES['files']['name'] as $index => $fileName) {
        $fileSize = $_FILES['files']['size'][$index];
        
        if ($fileSize > ($maxFileSize * 1024 * 1024)) {
          throw new api_Exception(E::ts('File size of ' . $fileName . ' exceeds the maximum limit of ' .  $fileSize . 'MB'));
        }
      }
      
      foreach ($_FILES['files']['name'] as $index => $fileName) {
        $attachmentParams = [
          'entity_table' => $entityTable,
          'entity_id' => $entityId,
          'mime_type' => $_FILES['files']['type'][$index],
          'name' => $_FILES['files']['name'][$index],
          'options' => [
            'move-file' => $_FILES['files']['tmp_name'][$index],
          ],
        ];
        
        civicrm_api3('Attachment', 'create', $attachmentParams);
      }
      
      $result[] = ['success' => 1];
    } else {
      throw new api_Exception(E::ts('Files were not uploaded'));
    }
    
    return $result;
  }
}
