# gcpstats

![Screenshot](/images/gcpbillingproject.png)

![Screenshot](/images/gcpbillingservice.png)

Scheduled job to pull Google Cloud Platform billing data grouped by project.

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Requirements

* PHP v7.4+
* CiviCRM (5+)

**Completion of the following:**
1. Created a Billing Administration project linked to a Billing Account
2. Installed the BigQuery API and the Data Transfer Service API
3. Enabled Billing Export for the linked Billing Account (Automatically creates a service account)
4. Created a Dataset within the Billing Administration project that would contain the exported billing data
5. Generated a valid keyfile for the Billing Admin service account

See the following guide if some or none of these steps were not completed: https://cloud.google.com/billing/docs/how-to/export-data-bigquery-setup

## Getting Started
After installing this extension, download the necessary Google Cloud PHP clients
using ```composer```.
```bash
composer require google/cloud-bigquery
# for only the bigquery library
```
or
```bash
composer require google/cloud
# to install the entire google cloud suite at once
```
See installation instructions for each library here:
https://github.com/googleapis/google-cloud-php/tree/main/BigQuery

It would also be very helpful to have he ```gcloud``` CLI installed: https://cloud.google.com/sdk/docs/install

If bigquery query returns with a non-successful return code (ex. 400), ensure that your service account has the
necessary permissions to fulfill your request. If this occurs, in your terminal:

```bash
# to authenticate your user
gcloud auth application-default set-quota-project PROJECT_ID
# to give service account bigquery permissions
gcloud projects add-iam-policy-binding obiaa-351723 --member "serviceAccount:SERVICE_ACCOUNT_NAME@PROJECT_ID.iam.gserviceaccount.com" --role "roles/bigquery.user"
```
_replace role with ```bigquery.admin``` if necessary._

## Authentication
Generate a keyfile for the billing administration service account if not done already, and
define it in your ```civicrm.settings.php``` file similar to the following:
```php
if (!defined('GCP_BILLING_KEY_JSON')) {
  define('GCP_BILLING_KEY_JSON',
    '{
      "type": "service_account",
      "project_id": "PROJECT_ID",
      "private_key_id": "PRIVATE_KEY_ID",
      "private_key": "-----BEGIN PRIVATE KEY-----PRIVATE_KEY-----END PRIVATE KEY-----\n",
      "client_email": "SERVICE_ACCOUNT_EMAIL",
      "client_id": "CLIENT_ID",
      "auth_uri": "https://accounts.google.com/o/oauth2/auth",
      "token_uri": "https://oauth2.googleapis.com/token",
      "auth_provider_x509_cert_url": "https://www.googleapis.com/oauth2/v1/certs",
      "client_x509_cert_url": "https://www.googleapis.com/robot/v1/metadata/x509/SERVICE_ACCOUNT_EMAIL",
      "universe_domain": "googleapis.com"
    }'
  );
}
```
In addition, put your Billing Administration ```project_id``` and ```billing_account_id```
in the ```civicrm.settings.php``` file.
```php
// Billing Administration project id
if (!defined('PROJECT_ID')) {
  define('PROJECT_ID', 'PROJECT_NAME');
}
// ID of your billing account
if (!defined('BILLING_ACCOUNT_ID')) {
  define('BILLING_ACCOUNT_ID', 'ID');
}
```

## Extension Installation

### Using web UI
Learn more about installing CiviCRM extensions in the [CiviCRM Sysadmin Guide](https://docs.civicrm.org/sysadmin/en/latest/customize/extensions/).

### Using cv
Sysadmins and developers may download the `.zip` file for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
cd <extension-dir>
cv dl gcpstats@https://github.com/FIXME/gcpstats/archive/master.zip
```
or
```bash
cd <extension-dir>
cv dl gcpstats@https://lab.civicrm.org/extensions/gcpstats/-/archive/main/gcpstats-main.zip
```
### Using Git
Sysadmins and developers may clone the [Git](https://en.wikipedia.org/wiki/Git) repo for this extension and
install it with the command-line tool [cv](https://github.com/civicrm/cv).

```bash
git clone https://github.com/FIXME/gcpstats.git
cv en gcpstats
```
or
```bash
git clone https://lab.civicrm.org/extensions/gcpstats.git
cv en gcpstats
```


## Known Issues

