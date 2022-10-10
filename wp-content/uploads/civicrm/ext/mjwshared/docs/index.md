# Payment Shared library

This library is used by all payment processors by MJW Consulting and other extensions.

It provides multiple functions such as APIs, refund UI, shared code and a compatibility layer to support multiple versions of CiviCRM without requiring explicit support in the payment processor.

## Setup

#### Job.process_paymentprocessor_webhooks

This job processes new webhook events in the `civicrm_paymentprocessor_webhook` table.

* Run: Always
* Domain-specific: YES. This job MUST be run on every domain you have setup if using multisite/multidomain.

## Support and Maintenance

This extension is supported and maintained by:

[![MJW Consulting](images/mjwconsulting.jpg)](https://www.mjwconsult.co.uk)

We offer paid [support and development](https://mjw.pt/support) as well as a [troubleshooting/investigation service](https://mjw.pt/investigation).
