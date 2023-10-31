<?php
// phpcs:disable
use CRM_Biasync_ExtensionUtil as E;
// phpcs:enable

class CRM_Biasync_BAO_CivicrmPropertyLog extends CRM_Biasync_DAO_CivicrmPropertyLog {

    public static function retrieve(array $params) {
        $options = [];
        $properties = \Civi\Api4\CivicrmPropertyLog::get(FALSE)
         ->setLimit(100)
         ->execute();
       foreach ($properties as $property) {
         $options[$property['id']] = $property;
       }
       return $options;
   }
}
