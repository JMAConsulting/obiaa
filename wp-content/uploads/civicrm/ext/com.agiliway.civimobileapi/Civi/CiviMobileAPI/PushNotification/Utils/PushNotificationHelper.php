<?php

namespace Civi\CiviMobileAPI\PushNotification\Utils;

class PushNotificationHelper
{
  private static $instance = null;
  
  private static $notifications = [];
  
  private function __construct() {
  }
  
  public static function addNotifications($notification) {
    self::$notifications[] = $notification;
  }
  
  public static function isSimilarNotification($notification) {
    return in_array($notification, self::$notifications);
  }
  
  public static function getInstance() {
    if (self::$instance == null) {
      self::$instance = new self();
    }
    
    return self::$instance;
  }
  
  public static function getAllNotifications() {
    return self::$notifications;
  }
  
  private function __clone() {
  }
  
  private function __wakeup() {
  }
  
}