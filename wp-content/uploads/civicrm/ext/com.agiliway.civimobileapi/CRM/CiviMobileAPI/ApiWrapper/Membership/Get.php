<?php

/**
 * @deprecated will be deleted in version 7.0.0
 */
class CRM_CiviMobileAPI_ApiWrapper_Membership_Get implements API_Wrapper {

  /**
   * Interface for interpreting api input.
   *
   * @param array $apiRequest
   *
   * @return array
   */
  public function fromApiInput($apiRequest) {
    if (is_mobile_request()) {
      $apiRequest['params']['is_membership'] = 1;
      $contactsId = (new CRM_CiviMobileAPI_Utils_ContactFieldsFilter())->filterContacts($apiRequest['params']);

      $receiveDate = !empty($apiRequest['params']['receive_date']) ? (new CRM_CiviMobileAPI_Utils_Statistic_ChartBar())->getPrepareReceiveDate($apiRequest['params']) : NULL;

      if (!empty($apiRequest['params']['activity_type_id']) && $apiRequest['params']['activity_type_id'] == 'renew') {
        $renewalMembershipsId = CRM_CiviMobileAPI_Utils_Statistic_Utils::getRenewalMembershipIds();
        $renewalMembershipsParam = (!empty($renewalMembershipsId)) ? ["IN" => $renewalMembershipsId] : NULL;

        $apiRequest['params']['id'] = $renewalMembershipsParam;
      }

      if (!empty($contactsId)) {
        $apiRequest['params']['contact_id'] = ['IN' => $contactsId];

        if (!empty($receiveDate)) {
          $apiRequest['params']['start_date'] = ['BETWEEN' => [$receiveDate['start_date'], $receiveDate['end_date']]];
        }
      } else {
        $apiRequest['params']['contact_id'] = ['IS NULL' => 1];
      }
    }

    return $apiRequest;
  }

  /**
   * Interface for interpreting api output.
   *
   * @param array $apiRequest
   * @param array $result
   *
   * @return array
   */
  public function toApiOutput($apiRequest, $result) {
    if (is_string($apiRequest['params']['return'])) {
      $apiRequest['params']['return'] = explode(',', $apiRequest['params']['return']);
    }

    if (!empty($apiRequest['params']['options']['is_count']) && $apiRequest['params']['options']['is_count'] == 1) {
      return $result;
    }

    $result = $this->fillAdditionalInfo($apiRequest, $result);
    $result = $this->fillRelatedCount($apiRequest, $result);
    $result = $this->fillByRelationship($apiRequest, $result);

    return $result;
  }

  /**
   * Adds additional info
   *
   * @param array $apiRequest
   * @param array $result
   *
   * @return array
   */
  private function fillAdditionalInfo($apiRequest, $result) {
    if ($apiRequest['action'] == 'getsingle') {
      if (empty($apiRequest['params']['return'])
        || in_array('renewal_amount', $apiRequest['params']['return'])
        || in_array('format_renewal_amount', $apiRequest['params']['return'])
      ) {
        $result += $this->getAdditionalInfo($result, $apiRequest);
      }
    }
    else {
      if ($apiRequest['action'] == 'get') {
        foreach ($result['values'] as &$membership) {
          $membership += $this->getAdditionalInfo($membership, $apiRequest);
        }
      }
    }

    return $result;
  }

  /**
   * Gets additional info
   *
   * @param array $membership
   * @param array $apiRequest
   *
   * @return array
   */
  private function getAdditionalInfo($membership, $apiRequest) {
    $config = CRM_Core_Config::singleton();
    $additionalInfo = [];
    $contactId = NULL;

    if (!empty($apiRequest['params']['membership_contact_id'])) {
      $contactId = $apiRequest['params']['membership_contact_id'];
    } elseif (!empty($membership['contact_id'])) {
      $contactId = $membership['contact_id'];
    }

    $membershipId = (int)$membership['id'];
    $membershipCardSql = "SELECT cm.contact_id,cm.end_date,cc.organization_name
                      FROM civicrm_membership cm
                      LEFT JOIN civicrm_contact cc ON cm.contact_id = cc.id WHERE cm.id = $membershipId";
    $dao = CRM_Core_DAO::executeQuery($membershipCardSql);
    $dao->fetch();

    $additionalInfo['organization_name'] = $dao->organization_name;
    $additionalInfo['expiration_date'] = $dao->end_date;

    $additionalInfo['qrCode'] = $this->generateQRcode($membership['id']);

    if (!empty($contactId)) {
      try {
        $lastPayment = civicrm_api3('MembershipPayment', 'getsingle', [
          'return' => ["contribution_id.receive_date", "membership_id.contact_id", "contribution_id.payment_instrument_id"],
          'membership_id' => $membership['id'],
          'membership_id.contact_id' => $apiRequest['params']['membership_contact_id'],
          'options' => ['sort' => "contribution_id.receive_date desc", 'limit' => 1],
        ]);

        $additionalInfo['payment_instrument'] = CRM_CiviMobileAPI_Utils_Membership::getPaymentInstrumentLabel($lastPayment['contribution_id.payment_instrument_id']);
        $additionalInfo['last_payment_receive_date'] = $lastPayment['contribution_id.receive_date'];
      } catch (Exception $e) {}
    }

    if (empty($apiRequest['params']['return'])
      || in_array('renewal_amount', $apiRequest['params']['return'])
      || in_array('format_renewal_amount', $apiRequest['params']['return'])
    ) {
      $membershipTypeId = !empty($membership['membership_type_id']) ? $membership['membership_type_id'] : CRM_Core_DAO::getFieldValue('CRM_Member_DAO_Membership', $membership['id'], 'membership_type_id');

      $additionalInfo['renewal_amount'] = CRM_Core_DAO::getFieldValue('CRM_Member_DAO_MembershipType', $membershipTypeId, 'minimum_fee') ?: 0;
      $additionalInfo['format_renewal_amount'] = CRM_Utils_Money::format($additionalInfo['renewal_amount'], $config->defaultCurrency);
    }

    if (empty($apiRequest['params']['return']) || in_array('can_renewal', $apiRequest['params']['return'])) {
      $additionalInfo['can_renewal'] = !CRM_Core_DAO::getFieldValue('CRM_Member_DAO_Membership', $membership['id'], 'owner_membership_id') ? 1 : 0;
    }

    $additionalInfo['currency_code'] = $config->defaultCurrency;
    $additionalInfo['currency_symbol'] = $config->defaultCurrencySymbol;

    return $additionalInfo;
  }

  /**
   * @param array $apiRequest
   * @param array $result
   *
   * @return array
   */
  private function fillRelatedCount($apiRequest, $result) {
    if (!(empty($apiRequest['params']['return']) || in_array('related_count', $apiRequest['params']['return']))) {
      return $result;
    }

    if ($apiRequest['action'] == 'getsingle') {
      $result['related_count'] = $this->getRelatedCount($result);
    }
    else {
      if ($apiRequest['action'] == 'get') {
        foreach ($result['values'] as &$membership) {
          $membership['related_count'] = $this->getRelatedCount($membership);
        }
      }
    }

    return $result;
  }

  /**
   * Gets related count
   *
   * @param array $membership
   *
   * @return int
   */
  private function getRelatedCount($membership) {
    $ownerMembershipId = !empty($membership['owner_membership_id']) ? $membership['owner_membership_id'] : CRM_Core_DAO::getFieldValue('CRM_Member_DAO_Membership', $membership['id'], 'owner_membership_id');

    if ($ownerMembershipId) {
      return 0;
    }

    $query = '
      SELECT COUNT(m.id)
      FROM civicrm_membership m
      LEFT JOIN civicrm_membership_status ms ON ms.id = m.status_id
      LEFT JOIN civicrm_contact ct ON ct.id = m.contact_id
      WHERE m.owner_membership_id = %1 AND m.is_test = 0 AND ms.is_current_member = 1 AND ct.is_deleted = 0
    ';

    $numRelated = CRM_Core_DAO::singleValueQuery($query, [
      1 => [$membership['id'], 'Integer']
    ]);

    return $numRelated;
  }

  /**
   * @param array $apiRequest
   * @param array $result
   *
   * @return mixed
   */
  private function fillByRelationship($apiRequest, $result) {
    if (!(empty($apiRequest['params']['return']) || in_array('by_relationship_contact_id', $apiRequest['params']['return']))) {
      return $result;
    }

    if ($apiRequest['action'] == 'getsingle') {
      $result += $this->getRelatedContact($result);
    }
    else {
      if ($apiRequest['action'] == 'get') {
        foreach ($result['values'] as &$membership) {
          $membership += $this->getRelatedContact($membership);
        }
      }
    }

    return $result;
  }

  /**
   * Gets related Contact by membership
   *
   * @param array $membership
   *
   * @return array
   */
  private function getRelatedContact($membership) {
    $ownerMembershipId = !empty($membership['owner_membership_id']) ? $membership['owner_membership_id'] : CRM_Core_DAO::getFieldValue('CRM_Member_DAO_Membership', $membership['id'], 'owner_membership_id');

    if ($ownerMembershipId) {
      try {
        $contactId = !empty($membership['contact_id']) ? $membership['contact_id'] : CRM_Core_DAO::getFieldValue('CRM_Member_DAO_Membership', $membership['id'], 'contact_id');
        $byRelationshipContactId = CRM_Core_DAO::getFieldValue('CRM_Member_DAO_Membership', $ownerMembershipId, 'contact_id');
        $ownerMembershipTypeId = CRM_Core_DAO::getFieldValue('CRM_Member_DAO_Membership', $ownerMembershipId, 'membership_type_id');
        $ownerRelationshipTypes = str_replace(CRM_Core_DAO::VALUE_SEPARATOR, ",", CRM_Core_DAO::getFieldValue('CRM_Member_DAO_MembershipType', $ownerMembershipTypeId, 'relationship_type_id'));

        $sql = "
          SELECT relationship_type_id,
            CASE
              WHEN  contact_id_a = %1 AND contact_id_b = %2 THEN 'b_a'
              WHEN  contact_id_b = %1 AND contact_id_a = %2 THEN 'a_b'
            END AS 'direction'
          FROM civicrm_relationship
          WHERE relationship_type_id IN ($ownerRelationshipTypes)
            AND (
              (contact_id_a = %1 AND contact_id_b = %2 )
              OR (contact_id_b = %1 AND contact_id_a = %2 )
            )
        ";
        $relationship = CRM_Core_DAO::executeQuery($sql, [
          1 => [(int) $byRelationshipContactId, 'Integer'],
          2 => [(int) $contactId, 'Integer']
        ]);

        $label = '';
        while ($relationship->fetch()) {
          $label .= (!empty($label)) ? ', ' : '';
          $label .= CRM_Core_DAO::getFieldValue('CRM_Contact_DAO_RelationshipType', $relationship->relationship_type_id, "label_" . $relationship->direction, 'id');
        }

        return [
          'by_relationship_contact_id' => $byRelationshipContactId,
          'by_relationship_contact_id.display_name' => CRM_CiviMobileAPI_Utils_Contact::getDisplayName($byRelationshipContactId),
          'by_relationship_label' => $label,
        ];
      } catch (Exception $e) {
        return [];
      }
    }

    return [];
  }

  /**
   * Generates QRcode for memebrship card
   * Saves QRcode in Membership's custom fields
   *
   * @throws \api_Exception
   */
  public function generateQRcode($membershipId) {
    $hashCode = hash('ripemd160', "membershipId" . $membershipId);
    $config = CRM_Core_Config::singleton();
    $directoryName = $config->uploadDir . DIRECTORY_SEPARATOR . 'qr';
    CRM_Utils_File::createDir($directoryName);
    $imageName = $this->generateImageName($membershipId);
    $path = $directoryName . DIRECTORY_SEPARATOR . $imageName;
    $params = [
      'attachFile_1' => [
        'uri' => $path,
        'location' => $path,
        'description' => '',
        'type' => 'image/png'
      ],
    ];

    \PHPQRCode\QRcode::png("http://civimobile.org/membership?qr=" . $membershipId . '_' . $hashCode, $path, 'L', 9, 3);
    CRM_Core_BAO_File::processAttachment($params, 'civicrm_membership', $membershipId);
    $fileUrl = CRM_CiviMobileAPI_Utils_File::getFileUrl($membershipId,'civicrm_membership', $this->generateImageName($membershipId));
    return $fileUrl;
  }

  private function generateImageName($membershipId) {
    return 'membershipId_' . $membershipId . '.png';
  }

}
