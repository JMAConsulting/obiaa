CiviCRM uses the _session_start()_ PHP construct for rendering its pages. WordPress signals that this could be of some risque for other modules and puts an error in the health check dashboard.

The exact discussion is found at https://lab.civicrm.org/dev/wordpress/-/issues/32

At this time no fix exists in CiviCRM. Changing the PHP construct induce other problems in CiviCRM and on the WordPress no direct loss of functionality is seen. So this plugins hides the warnings for the time being.

Its based on a solution suggested by Dia on StackExchange (https://civicrm.stackexchange.com/questions/35227/wordpress-site-health-contains-critical-errors-unless-civicrm-is-disabled)
