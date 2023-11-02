<?php

class CRM_Biasync_API3Wrappers_Property implements API_Wrapper {

    public function fromApiInput($apiRequest) {
        return $apiRequest;
    }

    /**
     * Marks modified property as unsynced
     */
    public function toApiOutput($apiRequest, $result) {
        if (isset($result['id'])) {
            $log = \Civi\Api4\PropertyLog::update(TRUE)
                ->addWhere('property_id','=',$result['id'])
                ->addValue('is_synced',0)
                ->execute();
        }
        return $result;
    }
}