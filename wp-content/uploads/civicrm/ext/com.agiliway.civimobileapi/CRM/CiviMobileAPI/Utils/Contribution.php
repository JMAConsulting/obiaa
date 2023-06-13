<?php

/**
 * Class provide Contribution helper methods
 */
class CRM_CiviMobileAPI_Utils_Contribution {

  /**
   * Transforms statistic
   *
   * @param $statistic
   *
   * @return array
   */
  public static function transformStatistic($statistic) {
    if (!empty($statistic['total'])) {
      $statistic['total']['avg'] = self::transform($statistic['total']['avg']);
      $statistic['total']['amount'] = self::transform($statistic['total']['amount']);
      $statistic['total']['mode'] = self::transform($statistic['total']['mode']);
      $statistic['total']['median'] = self::transform($statistic['total']['median']);
    }

    if (!empty($statistic['cancel'])) {
      $statistic['cancel']['avg'] = self::transform($statistic['cancel']['avg']);
      $statistic['cancel']['amount'] = self::transform($statistic['cancel']['amount']);
    }

    return $statistic;
  }

  /**
   * Delete unnecessary characters
   *
   * @param $string
   *
   * @return array
   */
  private static function transform($string) {
    return !empty($string) ? explode(",&nbsp;", $string) : [];
  }

}
