<?php
use CRM_Newstripepaymentreport_ExtensionUtil as E;

require_once 'Stripeschedulereport.variables.php';

/**
 * Job.Stripepaymentreport API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_job_Stripepaymentreport_spec(&$spec) {

}

/**
 * Job.Stripepaymentreport API
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @see civicrm_api3_create_success
 *
 * @throws API_Exception
 */
function civicrm_api3_job_Stripepaymentreport($params) {

  $contributions = [];
  $resultsActivity = [];

  try {

    //get contact id, payment process type, email address, total amount, month, site name
    //get current year and previous month
    $currentYear = date("Y");
    $currentMonth = (int) date("m");

    $getYear = $currentYear;
    $getMonth = 1;

    if ($currentMonth == 1) {
      $getYear = $currentYear - 1;
      $getMonth = 12;
    }
    else {
      $getMonth = $currentMonth - 1;
    }
    //get total amount
    $contributions = \Civi\Api4\Contribution::get()
      ->addSelect('SUM(total_amount)')
      ->addJoin('FinancialTrxn AS financial_trxn', 'LEFT', ['financial_trxn.trxn_id', '=', 'trxn_id'])
      ->addJoin('PaymentProcessor AS payment_processor', 'LEFT', ['payment_processor.id', '=', 'financial_trxn.payment_processor_id'])
      ->addJoin('PaymentProcessorType AS payment_processor_type', 'LEFT', ['payment_processor_type.id', '=', 'payment_processor.payment_processor_type_id'])
      ->addJoin('FinancialType AS financial_type', 'LEFT', ['financial_type.id', '=', 'financial_type_id'])
      ->addWhere('trxn_id', 'IS NOT NULL')
      ->addWhere('invoice_id', 'IS NOT NULL')
      ->addWhere('contribution_status_id', '=', 1)
      ->addWhere('payment_processor_type.name', '=', 'Stripe')
      ->addWhere('financial_trxn.is_payment', '=', TRUE)
      ->addWhere('MONTH(receive_date)', '=', $getMonth)
      ->addWhere('YEAR(receive_date)', '=', $getYear)
      ->execute()->first();

    if (!empty($contributions)) {
      // create activities
      $resultsActivity = \Civi\Api4\Activity::create()
        ->addValue('source_contact_id', rand())
        ->addValue('subject', 'Date is ' . $getYear . ' ' . $getMonth . ' Total: ' . $contributions['SUM:total_amount'])
        ->addValue('Stripe_Monthly_Total_Amount.Stripe_Monthly_Total_Amount', $contributions['SUM:total_amount'])
        ->addValue('activity_date_time', date('Y-m-d H:i:s'))
        ->addValue('status_id', 1)
        ->addValue('activity_type_id', OBIAAREPORT_ACTIVITY_TYPE)
        ->addValue('priority_id', 2)
        ->execute();
    }
  }
  //catch exception
  catch (Exception $e) {
    echo 'Message: ' . $e->getMessage();
  }

  return civicrm_api3_create_success((array) $contributions, $params, 'Job', 'Stripepaymentschedule');

}
