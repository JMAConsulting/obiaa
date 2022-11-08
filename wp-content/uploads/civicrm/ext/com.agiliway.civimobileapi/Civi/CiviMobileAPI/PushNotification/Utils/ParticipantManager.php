<?php

namespace Civi\CiviMobileAPI\PushNotification\Utils;

class ParticipantManager 
{
    private static $instance = null;
      
    private $participantStorage = [];

    private function __construct() {
    }

    public function addParticipantIds($participantId) {
        if(empty($participantId)){
            return;
        }
        $this->participantStorage[] = $participantId;
    }

    public function isParticipantIdInStorage($participantId) {
        if(empty($participantId)){
            return false;
        }
        return in_array($participantId, $this->participantStorage);
    }

    public function deleteParticipantIdFromStorage($participantId) {
        if(empty($participantId)){
            return;
        }
        $newStorage = [];
        foreach($this->participantStorage as $value){
            if($value == $participantId){
                continue;
            } else {
                $newStorage[] = $value;
            }
        }
        $this->participantStorage = $newStorage;

    }

    public static function getInstance() {
        if (self::$instance == null) {
          self::$instance = new self();
        }
        
        return self::$instance;
    }

    private function __clone() {
    }
    
    private function __wakeup() {
    }
}
