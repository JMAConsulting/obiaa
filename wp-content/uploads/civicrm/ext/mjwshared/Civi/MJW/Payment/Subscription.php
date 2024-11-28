<?php

namespace Civi\MJW\Payment;

use Brick\Money\Money;
use Civi\Api4\Contribution;
use Civi\Api4\ContributionRecur;

class Subscription {

  /**
   * The ContributionRecur as retrieved by API4.
   *
   * @var array
   */
  protected array $recur;

  public function setRecur(array $recur) {
    $this->recur = $recur;
  }

  public function getRecur(): array {
    return $this->recur;
  }

  /**
   * Updates the ContributionRecur entity, and if a template contribution
   * exists, update that along with its single line item.
   *
   * @param \Brick\Money\Money $newAmount
   *
   * @return array
   * @throws \Brick\Money\Exception\MoneyMismatchException
   * @throws \Brick\Money\Exception\UnknownCurrencyException
   * @throws \CRM_Core_Exception
   * @throws \Civi\API\Exception\UnauthorizedException
   * @throws \Civi\Core\Exception\DBQueryException
   */
  public function updateRecurAndTemplateContributionAmount(Money $newAmount): array {
    $recur = $this->getRecur();
    if (empty($recur)) {
      throw new \CRM_Core_Exception('Must set recur first');
    }

    // Check if amount is the same
    if (Money::of($recur['amount'], $recur['currency'])->compareTo($newAmount) === 0) {
      \Civi::log()->debug('nothing to do. Amount is already the same');
      return $recur;
    }

    // Get the template contribution
    // Calling ensureTemplateContributionExists will *always* return a template contribution
    //   Either it will have created one or will return the one that already exists.
    $templateContributionID = \CRM_Contribute_BAO_ContributionRecur::ensureTemplateContributionExists($recur['id']);

    // Now we update the template contribution with the new details
    // This will automatically update the Contribution LineItems as well.
    Contribution::update(FALSE)
      ->addValue('id', $templateContributionID)
      ->addValue('total_amount', $newAmount->getAmount()->toFloat())
      ->addWhere('id', '=', $templateContributionID)
      ->execute()
      ->first();

    // Update the recur
    // If we update a template contribution the recur will automatically be updated
    // (see CRM_Contribute_BAO_Contribution::self_hook_civicrm_post)
    // We need to make sure we updated the template contribution first because
    //   CRM_Contribute_BAO_ContributionRecur::self_hook_civicrm_post will also try to update it.
    $this->setRecur(ContributionRecur::get(FALSE)
      ->addWhere('id', '=', $recur['id'])
      ->execute()
      ->first());

    return $this->getRecur();
  }

}
