<?php

class CRM_CiviMobileAPI_Utils_Statistic_ChartBar {

  /**
   * Separator for currency formatting
   */
  private static $currencySeparator = '___';

  /**
   * Add amounts with extra currencies to statistics
   *
   * @param $statistics
   * @return mixed
   */
  private static function addExtraAmountsToStatistics($statistics) {
    $currencies = [];

    foreach ($statistics as $stat) {
      foreach ($stat['amounts'] as $amount) {
        if (!in_array($amount['currency'], $currencies)) {
          $currencies[] = $amount['currency'];
        }
      }
    }

    foreach ($statistics as &$stat) {
      $availableCurrencies = [];
      foreach ($stat['amounts'] as $amount) {
        $availableCurrencies[] = $amount['currency'];
      }

      $additionalCurrencies = array_diff($currencies, $availableCurrencies);
      foreach ($additionalCurrencies as $currency) {
        $stat['amounts'][] = [
          'amount' => 0,
          'currency' => $currency,
          'formattedAmount' => CRM_Utils_Money::format(0, $currency),
        ];
      }
    }

    return $statistics;
  }

  /**
   * Get statistic by period
   *
   * @param $listOfContactId
   * @param $params
   * @return array
   */
  public function periodDivide($listOfContactId, $params) {
    $preparedReceiveDate = $this->getPrepareReceiveDate($params);
    $period = $this->findPeriod($preparedReceiveDate['start_date'], $preparedReceiveDate['end_date']);

    $statistics = [];

    if ($period == 'days') {
      $statistics = $this->dayPeriodDivide($listOfContactId, $params);
    }
    elseif ($period == 'weeks') {
      $statistics = $this->weekPeriodDivide($listOfContactId, $params);
    }
    elseif ($period == 'months') {
      $statistics = $this->monthPeriodDivide($listOfContactId, $params);
    }
    elseif ($period == 'years') {
      $statistics = $this->yearPeriodDivide($listOfContactId, $params);
    }

    if ($params['is_membership'] == 1) {
      return $statistics;
    } else {
      return self::addExtraAmountsToStatistics($statistics);
    }

  }

  /**
   * Get period for contribution statistic
   *
   * @param $startDate
   * @param $endDate
   * @return false|string
   */
  public function findPeriod($startDate, $endDate) {
    $startDate = date_create($startDate);
    $endDate = date_create($endDate);
    $interval = date_diff($startDate, $endDate);
    $isNextYear = $interval->y > 0 && !($interval->y == 1 && $interval->m == 0 && $endDate->format('m-d') == '01-01');

    if ($interval->days <= 14) {
      return 'days';
    }
    elseif ($interval->days > 14 && $interval->days <= 60) {
      return 'weeks';
    }
    elseif ($interval->days > 60 && !$isNextYear) {
      return 'months';
    }
    elseif ($isNextYear) {
      return 'years';
    }
    else
      return FALSE;
  }

  /**
   * Get Summary with Selector
   *
   * @param $selector
   * @return mixed
   */
  private static function getSummary($selector) {
    $config = CRM_Core_Config::singleton();
    $configMoneyFormat = $config->moneyformat;
    $monetaryThousandSeparator = $config->monetaryThousandSeparator;
    $monetaryDecimalPoint = $config->monetaryDecimalPoint;
    $config->moneyformat = '%C' . self::$currencySeparator . '%a';
    $config->monetaryThousandSeparator = '';
    $config->monetaryDecimalPoint = '.';

    $summary = $selector->getSummary();
    $config->moneyformat = $configMoneyFormat;
    $config->monetaryThousandSeparator = $monetaryThousandSeparator;
    $config->monetaryDecimalPoint = $monetaryDecimalPoint;

    return $summary;
  }

  /**
   * Get statistic by days
   *
   * @param $listOfContactId
   * @param $params
   * @return array
   */
  public function dayPeriodDivide($listOfContactId, $params) {
    $preparedReceiveDate = $this->getPrepareReceiveDate($params);

    $endDate = date_create($preparedReceiveDate['end_date']);
    $firstDate = date_create($preparedReceiveDate['start_date']);
    $secondDate = clone $firstDate;
    $statistic = [];

    if (!empty($params['is_membership'])) {
      $membershipTypes = $this->getMembershipTypes($params);

      foreach ($membershipTypes as $typeId => $typeName) {
        $params['membership_type_id'] = $typeName;
        $statistic[$typeId] = $this->getMembershipStatistic($firstDate, $secondDate, $endDate, $listOfContactId, $params, '1 days');
      }

      return $statistic;
    }

    while ($secondDate->getTimestamp() < $endDate->getTimestamp()) {
      $secondDate = date_add($secondDate, date_interval_create_from_date_string("1 days"));

      if ($secondDate->getTimestamp() > $endDate->getTimestamp()) {
        $secondDate = clone $endDate;
      }

      $formattedFirstDate = $firstDate->format('Y-m-d');
      $formattedSecondDate = $secondDate->format('Y-m-d');

      $selector = $this->getContributeSelector($listOfContactId, $params,  $formattedFirstDate, $formattedFirstDate . ' 23:59:59');
      $statistic[] = $this->transformStatistic(self::getSummary($selector), $formattedFirstDate, $formattedSecondDate);
      $firstDate = clone $secondDate;
    }

    return $statistic;
  }

  /**
   * Get statistic by weeks
   *
   * @param $listOfContactId
   * @param $params
   * @return array
   */
  public function weekPeriodDivide($listOfContactId, $params) {
    $preparedReceiveDate = $this->getPrepareReceiveDate($params);
    $endDate = date_create($preparedReceiveDate['end_date']);
    $firstDate = date_create($preparedReceiveDate['start_date']);
    $secondDate = clone $firstDate;

    $statistic = [];

    if (!empty($params['is_membership'])) {
      $membershipTypes = $this->getMembershipTypes($params);

      foreach ($membershipTypes as $typeId => $typeName) {
        $params['membership_type_id'] = $typeName;
        $statistic[$typeId] = $this->getMembershipStatistic($firstDate, $secondDate, $endDate, $listOfContactId, $params, '7 days');
      }

      return $statistic;
    }

    while ($secondDate->getTimestamp() < $endDate->getTimestamp()) {
      $secondDate = date_add($secondDate, date_interval_create_from_date_string('7 days'));

      if ($secondDate->getTimestamp() > $endDate->getTimestamp()) {
        $secondDate = clone $endDate;
      }

      $formattedFirstDate = $firstDate->format('Y-m-d');
      $prevSecondDate = clone $secondDate;
      $prevSecondDate->modify('-1 second');
      $prevDay = clone $secondDate;
      $prevDay->modify('-1 days');

      $selector = $this->getContributeSelector($listOfContactId, $params,  $formattedFirstDate, $prevSecondDate->format('Y-m-d H:i:s'));
      $statistic[] = $this->transformStatistic(self::getSummary($selector), $formattedFirstDate, $prevDay->format('Y-m-d'));

      $firstDate = clone $secondDate;
    }

    return $statistic;
  }

  /**
   * Get statistic by months
   *
   * @param $listOfContactId
   * @param $params
   * @return array
   */
  public function monthPeriodDivide($listOfContactId, $params) {
    $preparedReceiveDate = $this->getPrepareReceiveDate($params);
    $endDate = date_create($preparedReceiveDate['end_date']);
    $firstDate = date_create($preparedReceiveDate['start_date']);
    $secondDate = date_create(date("Y-m", strtotime($preparedReceiveDate['start_date'])) . '-01');

    $statistic = [];

    if (!empty($params['is_membership'])) {
      $membershipTypes = $this->getMembershipTypes($params);

      foreach ($membershipTypes as $typeId => $typeName) {
        $params['membership_type_id'] = $typeName;
        $statistic[$typeId] = $this->getMembershipStatistic($firstDate, $secondDate, $endDate, $listOfContactId, $params, '1 month');
      }

      return $statistic;
    }

    while ($secondDate->getTimestamp() < $endDate->getTimestamp()) {
      $secondDate = date_add($secondDate, date_interval_create_from_date_string('1 months'));

      if ($secondDate->getTimestamp() > $endDate->getTimestamp()) {
        $secondDate = clone $endDate;
      }

      $formattedFirstDate = $firstDate->format('Y-m-d');
      $prevSecondDate = clone $secondDate;
      $prevSecondDate->modify('-1 second');

      $selector = $this->getContributeSelector($listOfContactId, $params,  $formattedFirstDate, $prevSecondDate->format('Y-m-d H:i:s'));
      $statistic[] = $this->transformStatistic(self::getSummary($selector), $formattedFirstDate, $secondDate->format('Y-m-d'));

      $firstDate = clone $secondDate;
    }

    return $statistic;
  }

  /**
   * Get statistic by years
   *
   * @param $listOfContactId
   * @param $params
   * @return array
   */
  public function yearPeriodDivide($listOfContactId, $params) {
    $preparedReceiveDate = $this->getPrepareReceiveDate($params);

    $date = date_create($preparedReceiveDate['start_date']);
    $endDate = date_create($preparedReceiveDate['end_date']);

    $firstDate = date_create($preparedReceiveDate['start_date']);
    $secondDate = date_create(date('Y', $date->getTimestamp()) . '-01-01');
    $statistic = [];

    if (!empty($params['is_membership'])) {
      $membershipTypes = $this->getMembershipTypes($params);

      foreach ($membershipTypes as $typeId => $typeName) {
        $params['membership_type_id'] = $typeName;
        $statistic[$typeId] = $this->getMembershipStatistic($firstDate, $secondDate, $endDate, $listOfContactId, $params, '1 year');
      }

      return $statistic;
    }

    while ($secondDate->getTimestamp() < $endDate->getTimestamp()) {
      $secondDate = date_add($secondDate, date_interval_create_from_date_string('1 year'));

      if ($secondDate->getTimestamp() > $endDate->getTimestamp()) {
        $secondDate = clone $endDate;
      }

      $formattedFirstDate = $firstDate->format('Y-m-d');
      $prevSecondDate = clone $secondDate;
      $prevSecondDate->modify('-1 second');

      $selector = $this->getContributeSelector($listOfContactId, $params,  $formattedFirstDate, $prevSecondDate->format('Y-m-d H:i:s'));
      $statistic[] = $this->transformStatistic(self::getSummary($selector), $formattedFirstDate, $secondDate->format('Y-m-d'));

      $firstDate = clone $secondDate;
    }

    return $statistic;
  }

  /**
   * Get contribute selector
   *
   * @param $listOfContactId
   * @param $params
   * @param $firstDate
   * @param $secondDate
   * @return CRM_Contribute_Selector_Search
   */
  public function getContributeSelector($listOfContactId, $params, $firstDate, $secondDate) {
    $contactQuery = ["IN" => $listOfContactId];
    $contributionFields = (new CRM_CiviMobileAPI_Utils_Statistic_ContactsContribution)->getContributionFieldsParams($params);

    $totalQueryParams = [
      'contact_id' => (!empty($listOfContactId)) ? $contactQuery : ['IS NULL' => 1],
      'receive_date' => [
        'BETWEEN' => [
          $firstDate,
          $secondDate,
        ]
      ],
      'financial_type_id' => $contributionFields['financial_type_id'],
      'payment_instrument_id' => $contributionFields['payment_instrument_id'],
      'contribution_status_id' => $contributionFields['contribution_status_id'],
      'total_amount' => $contributionFields['total_amount'],
    ];

    $selectedContributions = CRM_Contact_BAO_Query::convertFormValues($totalQueryParams);
    return new CRM_Contribute_Selector_Search($selectedContributions);
  }

  /**
   * Get membership types
   *
   * @param $params
   * @return array
   */
  public function getMembershipTypes($params) {
    $membershipTypes = [];

    if (!empty($params['membership_type_id'])) {
      foreach ($params['membership_type_id']['IN'] as $membershipId) {
        $membershipDetails = CRM_Member_BAO_MembershipType::getMembershipTypeDetails($membershipId);
        $membershipTypes[$membershipId] = $membershipDetails['name'];
      }
    } else {
      $membershipTypes = CRM_Member_BAO_MembershipType::getMembershipTypes(FALSE);
    }

    return $membershipTypes;
  }


  /**
   * Get membership statistic
   *
   * @param $firstDate
   * @param $secondDate
   * @param $endDate
   * @param $listOfContactId
   * @param $params
   * @param $period
   * @return array
   */
  public function getMembershipStatistic($firstDate, $secondDate, $endDate, $listOfContactId, $params, $period) {
    $statistic = [];
    $secondDate = clone $secondDate;

    while ($secondDate->getTimestamp() < $endDate->getTimestamp()) {
      $secondDate = date_add($secondDate, date_interval_create_from_date_string($period));

      if ($secondDate->getTimestamp() > $endDate->getTimestamp()) {
        $secondDate = clone $endDate;
      }

      $formattedFirstDate = $firstDate->format('Y-m-d');
      $prevSecondDate = clone $secondDate;
      $prevSecondDate->modify('-1 day');

      $statistic[] = [
        'count' => $this->getMembershipCount($listOfContactId, $params, $formattedFirstDate, $prevSecondDate->format('Y-m-d')),
        'first_date' => $formattedFirstDate,
        'second_date' => $secondDate->format('Y-m-d')
      ];

      $firstDate = clone $secondDate;
    }

    return $statistic;
  }

  /**
   * Return format statistic
   *
   * @param $statistic
   * @param $firstDate
   * @param $secondDate
   * @return array
   */
  public function transformStatistic($statistic, $firstDate, $secondDate) {
    $newAmount = [];

    if (!empty($statistic['total'])) {
      $statistic['total']['amount'] = CRM_CiviMobileAPI_Utils_Statistic_Utils::explodesString($statistic['total']['amount']);
      $clearAmounts = [];
      foreach ($statistic['total']['amount'] as $moneyString) {
        $amountParts = explode(self::$currencySeparator, $moneyString);
        $currencyName = $amountParts[0];
        $clearAmount = $amountParts[1];
        $formattedAmount = CRM_Utils_Money::format($clearAmount, $currencyName);
        $clearAmounts[] = $clearAmount;

        $newAmount[] = [
          'amount' => (float)$clearAmount,
          'currency' => $currencyName,
          'formattedAmount' => $formattedAmount,
        ];
      }
      $statistic['total']['amount'] = $clearAmounts;
      $statistic['total']['first_date'] = $firstDate;
      $statistic['total']['second_date'] = $secondDate;
    }

    if (!empty($statistic['cancel'])) {
      $statistic['cancel']['amount'] = CRM_CiviMobileAPI_Utils_Statistic_Utils::explodesString($statistic['cancel']['amount']);
    }

    return [
      'amount' => $statistic['total']['amount'],
      'amounts' => $newAmount,
      'first_date' => $statistic['total']['first_date'],
      'second_date' => $statistic['total']['second_date']
    ];
  }

  /**
   * Returns prepared receive date
   *
   * @param $params
   * @return array
   */
  public function getPrepareReceiveDate($params) {
    $defaultIntervals = CRM_CiviMobileAPI_Utils_Statistic_Utils::getDefaultContributionDateInterval();

    if (!empty($params['receive_date']['BETWEEN']) || empty($params['receive_date'])) {
      $startDate = !empty($params['receive_date']['BETWEEN'][0]) ? $params['receive_date']['BETWEEN'][0] : $defaultIntervals['min_receive_date'];
      $endDate = !empty($params['receive_date']['BETWEEN'][1]) ? $params['receive_date']['BETWEEN'][1] : $defaultIntervals['max_receive_date'];
    } elseif (!empty($params['receive_date']['<'])) {
      $startDate = $defaultIntervals['min_receive_date'];
      $endDate = $params['receive_date']['<'];
    } elseif (!empty($params['receive_date']['>'])) {
      $startDate = $params['receive_date']['>'];
      $endDate = ((int)date('Y') + 1) . '-01-01';
    }

    return [
      'start_date' => $startDate,
      'end_date' => $endDate
    ];
  }

  /**
   * Get count of membership for chart bar
   *
   * @param $contactsId
   * @param $params
   * @param $startDate
   * @param $endDate
   * @return int
   */
  public function getMembershipCount($contactsId, $params, $startDate, $endDate) {
    try {
      return civicrm_api3('Membership', 'getcount', [
        'options' => ['limit' => 0],
        'start_date' => ['BETWEEN' => [$startDate, $endDate]],
        'contact_id' => !empty($contactsId) ? ['IN' => $contactsId] : NULL,
        'membership_type_id' => !empty($params['membership_type_id']) ? ['IN' => [$params['membership_type_id']]] : NULL,
      ]);
    } catch (CiviCRM_API3_Exception $e) {
      return 0;
    }
  }

}
