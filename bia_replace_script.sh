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
echo "UPDATE civicrm_option_value ov INNER JOIN civicrm_option_group og ON og.id = ov.option_group_id SET ov.label = '\"$2\" <info@$1>', ov.name = '\"$2\" <info@$1>' WHERE ov.default = 1 AND og.name = 'from_email_address'" | $CIVICRM_CREDS
echo "DELETE ov.* FROM civicrm_option_value ov INNER JOIN civicrm_option_group og ON og.id = ov.option_group_id WHERE ov.value IN ('Bakeries', 'Dine', 'Miscellaneous', 'Shoppe', 'Sip') AND og.name = 'Business_Category_Child_Class_Unique'" | $CIVICRM_CREDS
# update site title
wp option update blogname $2
popd
rm /var/aegir/platforms/obiaa-staging/sites/bia1.jmaconsulting.biz/obiaa_backup.sql
