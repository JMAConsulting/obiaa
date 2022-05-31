# CiviCRM Permissions Sync

A plugin which keeps CiviCRM permissions in sync with WordPress capabilities.

#### Notes ####

This plugin has been developed using a minimum of *WordPress 5.1* and *CiviCRM 5.12*.

#### Installation ####

There are two ways to install from GitLab:

###### ZIP Download ######

If you have downloaded *CiviCRM Permissions Sync* as a ZIP file from the GitLab repository, do the following to install and activate the plugin:

1. Unzip the .zip file and, if needed, rename the enclosing folder so that the plugin's files are located directly inside `/wp-content/plugins/civicrm-permissions-sync`
2. Activate (or network-activate) the plugin
3. You're done.

###### git clone ######

If you have cloned the code from GitLab, it is assumed that you know what you're doing.

### Usage ####
1. Add permissions you want exposed in WP by going to CiviCRM > Administrator > Users and Permissions > WordPress Access Control. Check permission under Administrator role.
2. You will now see these available in WP capabilities.
3. To make available for Groups plugin, go to Groups > Capabilities > click Refresh.
