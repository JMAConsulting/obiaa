<?php

class CRM_CiviMobileAPI_PushNotification_Utils_CaseDeleteService {

    private static $instance = null;

    protected $recentlyDeletedIds = [];

    public function addRecentlyDeletedId($caseId) {
        $this->recentlyDeletedIds[] = $caseId;
    }

    public function isCaseRecentlyDeleted($caseId) {
        return in_array($caseId, $this->recentlyDeletedIds);
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new CRM_CiviMobileAPI_PushNotification_Utils_CaseDeleteService();
        }

        return self::$instance;
    }

    private function __clone() {
    }

    private function __wakeup() {
    }

    private function __construct() {
    }
}
