#!/usr/bin/env bash

BIA_HOSTNAME=$1
BIA_SITENAME=$2

pushd /var/www/obiaa.jmaconsulting.biz/htdocs

wp db export obiaa_wp_backup.sql
wp civicrm sql-dump > obiaa_civicrm_backup.sql
popd
pushd /var/www/$1/htdocs
wp db import /var/www/obiaa.jmaconsulting.biz/htdocs/obiaa_wp_backup.sql
CIVICRM_CREDS=`wp civicrm sql-connect`
cat /var/www/obiaa.jmaconsulting.biz/htdocs/obiaa_civicrm_backup.sql | sed -e 's/DEFINER[ ]*=[ ]*[^*]*\*/\*/' | $CIVICRM_CREDS
# Replace URLs with the new domain
wp search-replace 'obiaa.jmaconsulting.biz' $1
# Remove all contacts that aren't for user accounts or the domain contact
wp civicrm sql-query "DELETE FROM civicrm_contact WHERE id NOT IN (SELECT contact_id FROM civicrm_uf_match) AND id NOT IN (SELECT contact_id FROM civicrm_domain)"
# Update Domain Contact with correct information.
wp civicrm api contact.create organization_name='My Bia1' id=1 contact_type='Organization' contact_sub_type='BIA'
# update site title
wp option update blogname $2
popd
rm /var/www/obiaa.jmaconsulting.biz/htdocs/obiaa_wp_backup.sql /var/www/obiaa.jmaconsulting.biz/htdocs/obiaa_civicrm_backup.sql
