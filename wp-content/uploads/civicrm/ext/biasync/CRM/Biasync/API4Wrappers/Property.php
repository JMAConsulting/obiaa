<?php

class CRM_Myextension_API4Wrappers_Property implements API_Wrapper {

    public function fromApiInput(Civi\Api4\Generic\AbstractAction $apiRequest) {
        return $apiRequest;
    }

    /**
     * Marks modified property as unsynced
     */
    public function toApiOutput(Civi\Api4\Generic\AbstractAction $apiRequest, $result) {
        if (isset($result['id'])) {
            $log = \Civi\Api4\PropertyLog::update(TRUE)
                ->addWhere('property_id','=',$result['id'])
                ->addValue('is_synced',0)
                ->execute();
            }
        
        return $result;
    }
}