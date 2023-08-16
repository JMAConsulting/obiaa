<?php

class CRM_CiviMobileAPI_Utils_Statistic_ContactsContribution {

  /**
   * Get contribution statistic for one contact
   *
   * @param $params
   * @return array
   */
  public function getSingleContactContributionStatistic($params) {
    $contributionSelectors = $this->getContributionSelectors($params);
    $selector = $contributionSelectors['selector'];
    $currentYearSelector = $contributionSelectors['current_year_selector'];
    $preparedReceiveDate = (new CRM_CiviMobileAPI_Utils_Statistic_ChartBar())->getPrepareReceiveDate($params);

    $statistic = [
      'all_time' => CRM_CiviMobileAPI_Utils_Contribution::transformStatistic($selector->getSummary()),
      'current_year' => CRM_CiviMobileAPI_Utils_Contribution::transformStatistic($currentYearSelector->getSummary()),
      'period' => (new CRM_CiviMobileAPI_Utils_Statistic_ChartBar())->findPeriod($preparedReceiveDate['start_date'], $preparedReceiveDate['end_date'])
    ];

    return $statistic;
  }

  /**
   * Get contribution statistic for selected contacts
   *
   * @param $params
   * @param $listOfContactId
   * @param $startDate
   * @param $endDate
   * @return array
   */
  public function getSelectedContactsContributionStatistic($params, $listOfContactId) {
    $allTimeParams = $params;

    if (!empty($allTimeParams['receive_date']['BETWEEN'][0]) && !empty($allTimeParams['receive_date']['BETWEEN'][1])) {
      $allTimeParams['receive_date']['BETWEEN'][1] = date('YmdHis', strtotime($allTimeParams['receive_date']['BETWEEN'][1] . " -1 seconds"));
    }
    $contributionSelectors = $this->getContributionSelectors($allTimeParams, $listOfContactId);
    $selector = $contributionSelectors['selector'];
    $currentYearSelector = $contributionSelectors['current_year_selector'];
    $preparedReceiveDate = (new CRM_CiviMobileAPI_Utils_Statistic_ChartBar())->getPrepareReceiveDate($params);

    $statistic = [
      'all_time' => CRM_CiviMobileAPI_Utils_Contribution::transformStatistic($selector->getSummary()),
      'current_year' => CRM_CiviMobileAPI_Utils_Contribution::transformStatistic($currentYearSelector->getSummary()),
      'chart_bar' => (new CRM_CiviMobileAPI_Utils_Statistic_ChartBar)->periodDivide($listOfContactId, $params),
      'period' => (new CRM_CiviMobileAPI_Utils_Statistic_ChartBar())->findPeriod($preparedReceiveDate['start_date'], $preparedReceiveDate['end_date'])
    ];

    return $statistic;
  }

  /**
   * Get contribution selectors
   *
   * @param $params
   * @param null $listOfContactId
   * @return CRM_Contribute_Selector_Search[]
   */
  public function getContributionSelectors($params, $listOfContactId = NULL) {
    $contributionFields = $this->getContributionFieldsParams($params);

    if (!empty($params['contact_id'])) {
      $contactId = $params['contact_id'];
    } else {
      if (!empty($listOfContactId)) {
        $contactId = ["IN" => $listOfContactId];
      }
      else {
        $contactId = ['IS NULL' => 1];
      }
    }
    $totalQueryParams = [
      'contact_id' => $contactId,
      'receive_date' => $contributionFields['receive_date'],
      'financial_type_id' => $contributionFields['financial_type_id'],
      'payment_instrument_id' => $contributionFields['payment_instrument_id'],
      'contribution_status_id' => $contributionFields['contribution_status_id'],
      'total_amount' => $contributionFields['total_amount'],
    ];

    $currentYearQueryParams = [
      'contact_id' => $contactId,
      'receive_date' => [
        'BETWEEN' => [
          date('Y') . '-01-01',
          date('Y-m-d H:i:s', strtotime('12/31 +23 hours 59 minutes 59 seconds')),
        ]
      ],
      'financial_type_id' => $contributionFields['financial_type_id'],
      'payment_instrument_id' => $contributionFields['payment_instrument_id'],
      'contribution_status_id' => $contributionFields['contribution_status_id'],
      'total_amount' => $contributionFields['total_amount'],
    ];

    $selectedContactContributions = CRM_Contact_BAO_Query::convertFormValues($totalQueryParams);
    $selectedContactCurrentYearContributions = CRM_Contact_BAO_Query::convertFormValues($currentYearQueryParams);

    $selector = new CRM_Contribute_Selector_Search($selectedContactContributions);
    $currentYearSelector = new CRM_Contribute_Selector_Search($selectedContactCurrentYearContributions);

    return [
      'selector' => $selector,
      'current_year_selector' => $currentYearSelector
    ];
  }

  /**
   * Get contribution fields params
   *
   * @param $params
   * @return array
   */
  public function getContributionFieldsParams($params) {
    $receiveDate = !empty($params['receive_date']) ? $params['receive_date'] : NULL;

    $paymentInstrumentsId = !empty($params['payment_instrument_id']) ? $this->getPaymentInstrumentId($params['payment_instrument_id']['IN']) : NULL;
    $paymentInstrumentsParam = !empty($paymentInstrumentsId) ? ['IN' => $paymentInstrumentsId] : NULL;

    $financialTypesId = !empty($params['financial_type_id']) ? $this->getFinancialTypeId($params['financial_type_id']['IN']) : NULL;
    $financialTypesParam = !empty($financialTypesId) ? ['IN' => $financialTypesId] : NULL;

    $contributionStatusesId = !empty($params['contribution_status_id']) ? $this->getContributionStatusId($params['contribution_status_id']['IN']) : NULL;
    $contributionStatusesParam = !empty($contributionStatusesId) ? ['IN' => $contributionStatusesId] : NULL;

    $totalAmountParam = !empty($params['total_amount']) ? $params['total_amount'] : NULL;

    return [
      'receive_date' => $receiveDate,
      'payment_instrument_id' => $paymentInstrumentsParam,
      'financial_type_id' => $financialTypesParam,
      'contribution_status_id' => $contributionStatusesParam,
      'total_amount' => $totalAmountParam
    ];
  }

  /**
   * Get Ids of payment instruments
   *
   * @param $paymentInstrumentsParam
   * @return array
   */
  public function getPaymentInstrumentId($paymentInstrumentsParam) {
    $paymentInstrumentsId = [];

    try {
      $paymentInstruments = civicrm_api3('OptionValue', 'get', [
        'sequential' => 1,
        'return' => ["name", "value"],
        'option_group_id' => "payment_instrument",
      ])['values'];
    } catch (CiviCRM_API3_Exception $e) {
      return [];
    }

    if (!empty($paymentInstruments)) {
      foreach ($paymentInstruments as $paymentInstrument) {
        foreach ($paymentInstrumentsParam as $paymentInstrumentParam) {
          if ($paymentInstrumentParam == $paymentInstrument['name']) {
            $paymentInstrumentsId[] = $paymentInstrument['value'];
          }
        }
      }
    }

    return $paymentInstrumentsId;
  }

  /**
   * Get Ids of financial types
   *
   * @param $financialTypesParam
   * @return array
   */
  public function getFinancialTypeId($financialTypesParam) {
    $financialTypesId = [];

    try {
      $financialTypes = civicrm_api3('FinancialType', 'get', [
        'sequential' => 1,
      ])['values'];
    } catch (CiviCRM_API3_Exception $e) {
      return [];
    }

    if (!empty($financialTypes)) {
      foreach ($financialTypes as $financialType) {
        foreach ($financialTypesParam as $financialTypeParam) {
          if ($financialTypeParam == $financialType['name']) {
            $financialTypesId[] = $financialType['id'];
          }
        }
      }
    }

    return $financialTypesId;
  }

  /**
   * Get Ids of contribution statuses
   *
   * @param $contributionStatusesParam
   * @return array
   */
  public function getContributionStatusId($contributionStatusesParam) {
    $contributionStatusId = [];

    try {
      $contributionStatuses = civicrm_api3('OptionValue', 'get', [
        'sequential' => 1,
        'return' => ["name", "value"],
        'option_group_id' => "contribution_status",
      ])['values'];
    } catch (CiviCRM_API3_Exception $e) {
      return [];
    }

    if (!empty($contributionStatuses)) {
      foreach ($contributionStatuses as $contributionStatus) {
        foreach ($contributionStatusesParam as $contributionStatusParam) {
          if ($contributionStatusParam == $contributionStatus['name']) {
            $contributionStatusId[] = $contributionStatus['value'];
          }
        }
      }
    }

    return $contributionStatusId;
  }

}
