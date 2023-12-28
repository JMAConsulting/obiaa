<?php
use CRM_Gcpstats_ExtensionUtil as E;

require_once Civi::paths()->getPath("[civicrm.vendor]/autoload.php");

use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;
use Google\Cloud\Billing\V1\CloudBillingClient;
use Google\Cloud\BigQuery\BigQueryClient;

/**
 * Job.Getawsmailingstats API
 *
 * @param array $params
 *
 * @return array
 *   API result descriptor
 *
 * @throws API_Exception|ApiException*@throws \Google\ApiCore\ApiException
 * @throws ValidationException
 * @throws Exception
 * @see civicrm_api3_create_success
 *
 */
function civicrm_api3_job_Getgcpbillingstats($params): array{
  // Authenticate Client using service account KeyFile
  $bigQuery = new BigQueryClient([
    'keyFile' => json_decode(GCP_BILLING_KEY_JSON, true)
  ]);
  // Retrieve relevant name constants
  $billingTableName = "`" . BILLING_ADMIN_PROJECT_ID . "." . DATASET_NAME . "." . "gcp_billing_export_resource_v1_" . BILLING_ACCOUNT_ID . "`";
  $projectId = PROJECT_ID;
  // Set invoice date to last month
  $dateTime = new DateTime();
  $dateTime->modify('first day of last month');
  $year = $dateTime->format('Y');
  $month = $dateTime->format('m');
  $invoiceMonth = $year . $month;
  $query = <<<ENDSQL
     SELECT
     project.id as id,
     project.name as name,
     sum(usage.amount) as total_usage,
     sum(cost) as total_cost,
     SUM(IFNULL((SELECT SUM(c.amount) FROM UNNEST(credits) c), 0)) as total_credits
    FROM $billingTableName
    WHERE invoice.month = "$invoiceMonth"
    AND project.id = "$projectId"
    GROUP BY 1, 2
    ORDER BY 1
    ENDSQL;
  // Execute Query using BigQuery API
  $queryJobConfig = $bigQuery->query($query);
  $queryResults = $bigQuery->runQuery($queryJobConfig);
  $cost = 0;
  $credit = 0;
  $totalUsage = 0;
  if ($queryResults->isComplete()) {
    $rows = $queryResults->rows();
    // Should just be one row
    foreach ($rows as $row) {
      $cost += (float) $row['total_cost'];
      $credit += (float) $row['total_credits'];
      $totalUsage += $row['total_usage'];
    }
  } else {
    throw new Exception('The query failed to complete');
  }
  $avgCost = round(($cost / $totalUsage) * 1000, 2); // average cost per 1000 units (DB won't store if too small)
  \Civi::log()->debug('avg: ' . $avgCost . PHP_EOL);
  $gcpBillingInfoActivityType = \Civi\Api4\OptionValue::get()
    ->addWhere('option_group_id:name', '=', 'activity_type')
    ->addWhere('name', '=', 'Google Billing Information')
    ->execute()->first();
  $billingInfoActivity = \Civi\Api4\Activity::create()
    ->addValue('activity_type_id', $gcpBillingInfoActivityType['value'])
    ->addValue('source_contact_id', 1) // don't remove this
    ->addValue('GCP_Billing_Stats.Total_Cost', $cost)
    ->addValue('GCP_Billing_Stats.Total_Credits', $credit)
    ->addValue('GCP_Billing_Stats.Total_Usage', $totalUsage)
    ->addValue('GCP_Billing_Stats.Invoice_Month', $year . '-' . $month)
    ->addValue('GCP_Billing_Stats.Project_Id', $projectId)
    ->addValue('GCP_Billing_Stats.Avg_Cost_Per_Thousand', $avgCost)
    ->execute()->first();
  return civicrm_api3_create_success($billingInfoActivity, $params, 'Job', 'Getgcpbillingstatistics');
}

function _convertNestedArrayToString3($array, $indentation = ''): string {
  $output = '';

  foreach ($array as $key => $value) {
    if (is_array($value)) {
      $output .= $indentation . $key . ":\n";
      $output .= _convertNestedArrayToString3($value, $indentation . '    ');
    } else {
      $output .= $indentation . $key . ': ' . $value . "\n";
    }
  }
  return $output;
}

/**
 * Job.Getgcpbillingstats API specification (optional)
 * This is used for documentation and validation.
 *
 * @param array $spec description of fields supported by this API call
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/api-architecture/
 */
function _civicrm_api3_job_Getgcpbillingstats_spec(&$spec): void{
  return;
}
