#!/usr/bin/env bash

BIA_HOSTNAME=$1
BIA_SITENAME=$2

pushd /var/aegir/platforms/obiaa-staging/sites/bia1.jmaconsulting.biz/

wp db export obiaa_backup.sql
popd
pushd /var/aegir/platforms/obiaa/sites/$1
CIVICRM_CREDS=`wp civicrm sql-connect`
cat /var/aegir/platforms/obiaa-staging/sites/bia1.jmaconsulting.biz/obiaa_backup.sql | sed -e 's/DEFINER[ ]*=[ ]*[^*]*\*/\*/' | $CIVICRM_CREDS
echo "UPDATE civicrm_setting SET value = 's:38:\"/var/aegir/platforms/obiaa/wp-load.php\";' WHERE name='wpLoadPhp'" | $CIVICRM_CREDS
# Replace URLs with the new domain
wp search-replace --all-tables-with-prefix 'bia1.jmaconsulting.biz' $BIA_HOSTNAME
wp civicrm api system.flush
# Remove all contacts that aren't for user accounts or the domain contact
echo "DELETE FROM civicrm_contact WHERE id NOT IN (SELECT contact_id FROM civicrm_uf_match) AND id NOT IN (SELECT contact_id FROM civicrm_domain)" | $CIVICRM_CREDS
echo "DELETE FROM civicrm_unit" | $CIVICRM_CREDS
echo "DELETE FROM civicrm_property" | $CIVICRM_CREDS
# Update Domain Contact with correct information.
wp civicrm api contact.create organization_name="My Bia $2" id=1 contact_type='Organization' contact_sub_type='BIA'
# update site title
wp option update blogname $2
popd
rm /var/aegir/platforms/obiaa-staging/sites/bia1.jmaconsulting.biz/obiaa_backup.sql
