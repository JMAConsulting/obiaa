<?php

use CRM_CiviMobileAPI_ExtensionUtil as E;

/**
 * @deprecated will be deleted in version 7.0.0
 */
class CRM_CiviMobileAPI_ApiWrapper_Participant_Get implements API_Wrapper {

  /**
   * Interface for interpreting api input
   *
   * @param array $apiRequest
   *
   * @return array
   */
  public function fromApiInput($apiRequest) {
    return $apiRequest;
  }

  /**
   * Interface for interpreting api output
   *
   * @param $apiRequest
   * @param $result
   *
   * @return array
   * @throws API_Exception
   */
  public function toApiOutput($apiRequest, $result) {
    if (empty($result['values'])) {
      return $result;
    }

    $activeParam = !empty($apiRequest['params']['status_active']) ? $apiRequest['params']['status_active'] : null;
    $customQrCode = "custom_" . CRM_CiviMobileAPI_Utils_CustomField::getId(
      CRM_CiviMobileAPI_Install_Entity_CustomGroup::QR_CODES,
      CRM_CiviMobileAPI_Install_Entity_CustomField::QR_CODE);

    $contactIds = [];
    $participantStatusTypesIds = [];
    foreach ($result['values'] as $key => $value) {
      $contactIds[] = $value['contact_id'];
      if (!empty($value['participant_status_id'])) {
        $participantStatusTypesIds[] = $value['participant_status_id'];
      }
    }

    if (!empty($contactIds)) {
      $contacts = civicrm_api3('Contact', 'get', [
        'return' => ["image_URL"],
        'id' => ['IN' => $contactIds],
        'options' => ['limit' => 0],
      ])['values'];
    } else {
      $contacts = [];
    }

    if ($activeParam == 1 && !empty($participantStatusTypesIds)) {
      $participantStatusTypes = civicrm_api3('ParticipantStatusType', 'get', [
        'id' => ['IN' => array_unique($participantStatusTypesIds)],
        'options' => ['limit' => 0],
      ])['values'];
    } else {
      $participantStatusTypes = [];
    }

    $currentCMS = CRM_CiviMobileAPI_Utils_CmsUser::getInstance()->getSystem();
    foreach ($result['values'] as $key => &$value) {
      $imageUrl = !empty($contacts[$value['contact_id']]) ? $contacts[$value['contact_id']]['image_URL'] : '';

      if ($activeParam == 1) {
        $statusInfo = !empty($participantStatusTypes[$value['participant_status_id']]) ? $participantStatusTypes[$value['participant_status_id']] : [];

        if (!empty($statusInfo) && $statusInfo['is_active'] != 1) {
          unset($result['values'][$key]);
          $result['count'] -= 1;
        }
      }

      $displayImageUrl = '';
      if (!empty($value[$customQrCode])) {
        $photoName = 'participantId_' . $value['participant_id'] . '.png';
        $displayImageUrl = CRM_Utils_System::url('civicrm/civimobile/file', ['photo' => $photoName], TRUE, NULL, FALSE);

        if ($currentCMS == CRM_CiviMobileAPI_Utils_CmsUser::CMS_JOOMLA) {
          $displayImageUrl = preg_replace('/administrator\//', 'index.php', $displayImageUrl);
        }
      }

      $value['qr_token'] = !empty($value[$customQrCode]) ? $value[$customQrCode] : '';
      $value['image_URL'] = $imageUrl;
      $value['display_image_URL'] = $displayImageUrl;
    }

    $result['values'] = array_values($result['values']);

    return $result;
  }

}
