<?php

namespace Civi\CiviMobileAPI\PushNotification\Entity;

abstract class BasePushNotification {
  
  protected $action;
  protected $entity;
  protected $id;
  protected $entityInstance;

  /**
   * @param string $action
   * @param string $entity
   * @param int $id
   * @param object $entityInstance
   */
  public function __construct($action, $entity, $id, $entityInstance) {
    $this->action = $action;
    $this->entity = $entity;
    $this->id = $id;
    $this->entityInstance = $entityInstance;
  }
  
  /**
   * Gets contact (single or plural) which related to entity
   *
   * @return array
   */
  protected abstract function getContacts();
  
  /**
   * Gets text for push notification
   *
   * @return string
   */
  protected abstract function getMessage();
  
  /**
   * Gets title of entity
   *
   * @return string
   */
  protected abstract function getTitle();

}
