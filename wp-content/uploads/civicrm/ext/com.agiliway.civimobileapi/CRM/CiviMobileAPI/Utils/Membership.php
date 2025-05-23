<?php

/**
 * Class provide extension version helper methods
 */
class CRM_CiviMobileAPI_Utils_Membership {

  /**
   * Renewals membership
   *
   * @param array $params
   */
  public static function renewal($params) {
    try {
      $membershipContribution = FALSE;
      $membership = CRM_Member_BAO_Membership::findById($params['id']);
      $membershipType = CRM_Member_BAO_MembershipType::findById($membership->membership_type_id);
      list($userName) = CRM_Contact_BAO_Contact_Location::getEmailDetails(CRM_Core_Session::singleton()->get('userID'));

      $source = $membershipType->name . ' Membership: Offline membership renewal (by ' . $userName . ')';

      $renewalDate = !empty($params['renewal_date']) ? $params['renewal_date'] : date('YmdHis');
      $amount = !empty($params['renewal_amount']) ? $params['renewal_amount'] : $membershipType->minimum_fee;
      $financialTypeId = !empty($params['renewal_financial_type_id']) ? $params['renewal_financial_type_id'] : $membershipType->financial_type_id;

      $lineItems = self::getLineItems([
        'membership_id' => $membership->id,
        'membership_type_id' => $membershipType->id,
        'financial_type_id' => $financialTypeId,
        'receive_date' => $renewalDate,
        'amount' => $amount,
        'source' => $source
      ]);

      self::legacyProcessMembership(
        $membership->contact_id, $membership->membership_type_id, 0,
        $renewalDate, NULL, [], 1, $membership->id,
        FALSE,
        $membership->contribution_recur_id, NULL, FALSE, $membership->campaign_id
      );

      if ($amount) {
        $contributionParams = [
          'membership_id' => $membership->id,
          'contribution_recur_id' => $membership->contribution_recur_id,
          'campaign_id' => $membership->campaign_id,
          'contact_id' => $membership->contact_id,
          'receive_date' => $renewalDate,
          'total_amount' => $amount,
          'financial_type_id' => $financialTypeId,
          'membership_type_id' => $membershipType->id,
          'contribution_source' => $source,
          'lineItems' => $lineItems,
          'processPriceSet' => TRUE
        ];

        if (!empty($params['renewal_invoice_id'])) {
          $contributionParams['invoice_id'] = $params['renewal_invoice_id'];
        }

        $membershipContribution = CRM_Member_BAO_Membership::recordMembershipContribution($contributionParams);
      }

      static::sendEmail($membership->contact_id, $membership->id, $membershipType, $membershipContribution);
    }
    catch (Exception $e) {}
  }

  /**
   * Gets price set id
   *
   * @param int $membershipId
   *
   * @return int
   */
  private static function getPriceSetId($membershipId) {
    $contributionPageId = CRM_Member_BAO_Membership::getContributionPageId($membershipId);

    if ($contributionPageId) {
      $priceSetId = CRM_Price_BAO_PriceSet::getFor('civicrm_contribution_page', $contributionPageId);
    }
    else {
      $priceSetId = reset(CRM_Price_BAO_PriceSet::getDefaultPriceSet('membership'))['setID'];
    }

    return $priceSetId;
  }

  /**
   * @param array $params
   *
   * @return array
   * @throws \CRM_Core_Exception
   */
  private static function getLineItems($params) {
    $priceSetParams = [
      'membership_type_id' => $params['membership_type_id'],
      'financial_type_id' => $params['financial_type_id'],
      'total_amount' => $params['amount'],
      'receive_date' => $params['receive_date'],
      'record_contribution' => 1,
      'contribution_source' => $params['source'],
      'is_pay_later' => FALSE
    ];

    $priceSetId = self::getPriceSetId($params['membership_id']);

    $fields = civicrm_api4('PriceField', 'get', [
      'where' => [
        ['price_set_id', '=', $priceSetId],
      ],
      'checkPermissions' => FALSE,
    ])->getArrayCopy();

    foreach ($fields as &$field) {
      $priceFieldValues = civicrm_api4('PriceFieldValue', 'get', [
        'where' => [
          ['price_field_id', '=', $field['id']],
        ],
        'checkPermissions' => FALSE,
      ])->getArrayCopy();

      $field['options'] = $priceFieldValues;
      $priceSetParams['price_' . $field['id']] = $field['id'];
    }

    $lineItems = [];

    $fieldId = array_key_first($fields);

    [$params, $lineItems[$priceSetId]] = CRM_Price_BAO_PriceSet::getLine($priceSetParams, $lineItems[$priceSetId], '', $fields[$fieldId], $fieldId);

    return $lineItems;
  }

  /**
   * Sends "Memberships - Receipt (off-line)"
   *
   * @param $contactId
   *
   * @param $membershipId
   * @param $membershipType
   * @param $membershipContribution
   *
   * @throws \Exception
   */
  private static function sendEmail($contactId, $membershipId, $membershipType, $membershipContribution) {
    $membership = CRM_Member_BAO_Membership::findById($membershipId);
    $details = CRM_Contact_BAO_Contact_Location::getEmailDetails($contactId);
    $userDisplayName = $details[0];
    $userEmail = $details[1];
    $senderEmail = CRM_Core_BAO_Domain::getNameAndEmail();
    $senderEmailName = $senderEmail[0];
    $senderEmailAddress = $senderEmail[1];

    $params = [
      'groupName' => 'msg_tpl_workflow_membership',
      'valueName' => 'membership_offline_receipt',
      'contactId' => $contactId,
      'from' => $senderEmailName . " <" . $senderEmailAddress . ">",
      'toName' => $userDisplayName,
      'toEmail' => $userEmail,
      'isTest' => false,
      'tplParams' => [
        'receive_date' => $membershipContribution->receive_date,
        'mem_start_date' => CRM_Utils_Date::customFormat($membership->start_date),
        'mem_end_date' => CRM_Utils_Date::customFormat($membership->end_date),
        'membership_name' => CRM_Core_DAO::getFieldValue('CRM_Member_DAO_MembershipType', $membershipType->id),
        'receiptType' => 'membership renewal',
        'formValues'=> $formValues = [
          'paidBy' => static::getPaymentInstrumentLabel($membershipContribution->payment_instrument_id),
          'total_amount' => $membershipContribution->total_amount
        ],
      ]
    ];

    CRM_Core_BAO_MessageTemplate::sendTemplate($params);
  }

  /**
   *Gets label for payment instrument
   *
   * @param $paymentInstrumentValue
   *
   * @return array|string
   */
  public static function getPaymentInstrumentLabel($paymentInstrumentValue) {
    $label = civicrm_api4('OptionValue', 'get', [
      'select' => ['label'],
      'where' => [
        ['option_group_id:name', '=', 'payment_instrument'],
        ['value', '=', $paymentInstrumentValue]
      ],
      'limit' => 1,
      'checkPermissions' => FALSE,
    ])->first()['label'];

    return $label ?? '';
  }

  /**
   * @param int $contactID
   * @param int $membershipTypeID
   * @param bool $is_test
   * @param string $changeToday
   * @param int $modifiedID
   * @param $customFieldsFormatted
   * @param $numRenewTerms
   * @param int $membershipID
   * @param $pending
   * @param int $contributionRecurID
   * @param $membershipSource
   * @param $isPayLater
   * @param array $memParams
   * @param null|CRM_Contribute_BAO_Contribution $contribution
   * @param array $lineItems
   *
   * @return array
   * @throws \CRM_Core_Exception
   */
  protected static function legacyProcessMembership($contactID, $membershipTypeID, $is_test, $changeToday, $modifiedID, $customFieldsFormatted, $numRenewTerms, $membershipID, $pending, $contributionRecurID, $membershipSource, $isPayLater, $memParams = [], $contribution = NULL, $lineItems = []) {
    $renewalMode = $updateStatusId = FALSE;
    $allStatus = CRM_Member_PseudoConstant::membershipStatus();
    $format = '%Y%m%d';
    $statusFormat = '%Y-%m-%d';
    $membershipTypeDetails = CRM_Member_BAO_MembershipType::getMembershipType($membershipTypeID);
    $dates = [];
    $ids = [];

    $currentMembership = CRM_Member_BAO_Membership::getContactMembership($contactID, $membershipTypeID,
      $is_test, $membershipID, TRUE
    );
    if ($currentMembership) {
      $renewalMode = TRUE;

      if ($pending || in_array($currentMembership['status_id'], [
          array_search('Pending', $allStatus),
          array_search('Cancelled', CRM_Member_PseudoConstant::membershipStatus(NULL, " name = 'Cancelled' ", 'name', FALSE, TRUE)),
        ])) {

        $memParams = array_merge([
          'id' => $currentMembership['id'],
          'contribution' => $contribution,
          'status_id' => $currentMembership['status_id'],
          'start_date' => $currentMembership['start_date'],
          'end_date' => $currentMembership['end_date'],
          'line_item' => $lineItems,
          'join_date' => $currentMembership['join_date'],
          'membership_type_id' => $membershipTypeID,
          'max_related' => !empty($membershipTypeDetails['max_related']) ? $membershipTypeDetails['max_related'] : NULL,
          'membership_activity_status' => ($pending || $isPayLater) ? 'Scheduled' : 'Completed',
        ], $memParams);
        if ($contributionRecurID) {
          $memParams['contribution_recur_id'] = $contributionRecurID;
        }

        $membership = CRM_Member_BAO_Membership::create($memParams);
        return [$membership, $renewalMode, $dates];
      }

      CRM_Member_BAO_Membership::fixMembershipStatusBeforeRenew($currentMembership, $changeToday);

      if (!$currentMembership['is_current_member']) {
        $dates = CRM_Member_BAO_MembershipType::getRenewalDatesForMembershipType($currentMembership['id'],
          $changeToday,
          $membershipTypeID,
          $numRenewTerms
        );

        $currentMembership['join_date'] = CRM_Utils_Date::customFormat($currentMembership['join_date'], $format);
        foreach (['start_date', 'end_date'] as $dateType) {
          $currentMembership[$dateType] = $dates[$dateType] ?? NULL;
        }
        $currentMembership['is_test'] = $is_test;

        if (!empty($membershipSource)) {
          $currentMembership['source'] = $membershipSource;
        }

        if (!empty($currentMembership['id'])) {
          $ids['membership'] = $currentMembership['id'];
        }
        $memParams = array_merge($currentMembership, $memParams);
        $memParams['membership_type_id'] = $membershipTypeID;
        $memParams['log_start_date'] = CRM_Utils_Date::customFormat($dates['log_start_date'], $format);
      }
      else {
        $membership = new CRM_Member_DAO_Membership();
        $membership->id = $currentMembership['id'];
        $membership->find(TRUE);

        $dates = CRM_Member_BAO_MembershipType::getRenewalDatesForMembershipType($membership->id,
          $changeToday,
          $membershipTypeID,
          $numRenewTerms
        );

        $memParams['join_date'] = CRM_Utils_Date::isoToMysql($membership->join_date);
        $memParams['start_date'] = CRM_Utils_Date::isoToMysql($membership->start_date);
        $memParams['end_date'] = $dates['end_date'] ?? NULL;
        $memParams['membership_type_id'] = $membershipTypeID;
        $memParams['log_start_date'] = CRM_Utils_Date::customFormat($dates['log_start_date'], $format);

        if (!empty($membershipSource)) {
          $memParams['source'] = $membershipSource;
        }
        elseif (empty($membership->source)) {
          $memParams['source'] = CRM_Core_DAO::getFieldValue('CRM_Member_DAO_Membership',
            $currentMembership['id'],
            'source'
          );
        }

        if (!empty($currentMembership['id'])) {
          $ids['membership'] = $currentMembership['id'];
        }
        $memParams['membership_activity_status'] = ($pending || $isPayLater) ? 'Scheduled' : 'Completed';
      }
    }
    else {
      $memParams = array_merge([
        'contact_id' => $contactID,
        'membership_type_id' => $membershipTypeID,
      ], $memParams);

      if (!$pending) {
        $dates = CRM_Member_BAO_MembershipType::getDatesForMembershipType($membershipTypeID, NULL, NULL, NULL, $numRenewTerms);

        foreach (['join_date', 'start_date', 'end_date'] as $dateType) {
          $memParams[$dateType] = $dates[$dateType] ?? NULL;
        }

        $status = CRM_Member_BAO_MembershipStatus::getMembershipStatusByDate(CRM_Utils_Date::customFormat($dates['start_date'],
          $statusFormat
        ),
          CRM_Utils_Date::customFormat($dates['end_date'],
            $statusFormat
          ),
          CRM_Utils_Date::customFormat($dates['join_date'],
            $statusFormat
          ),
          'now',
          TRUE,
          $membershipTypeID,
          $memParams
        );
        $updateStatusId = $status['id'] ?? NULL;
      }
      else {
        $updateStatusId = array_search('Pending', $allStatus);
      }

      if (!empty($membershipSource)) {
        $memParams['source'] = $membershipSource;
      }
      $memParams['is_test'] = $is_test;
      $memParams['is_pay_later'] = $isPayLater;
    }

    if ($contributionRecurID) {
      $memParams['contribution_recur_id'] = $contributionRecurID;
    }

    if ($updateStatusId) {
      $memParams['status_id'] = $updateStatusId;
      $memParams['skipStatusCal'] = TRUE;
    }

    $memParams['is_override'] = FALSE;

    if ($modifiedID) {
      $memParams['is_for_organization'] = TRUE;
    }
    $params['modified_id'] = $modifiedID ?? $contactID;

    $memParams['contribution'] = $contribution;
    $memParams['custom'] = $customFieldsFormatted;
    $memParams['line_item'] = $lineItems;

    $membership = CRM_Member_BAO_Membership::create($memParams, $ids);
    $membership->find(TRUE);

    return [$membership, $renewalMode, $dates];
  }


}
