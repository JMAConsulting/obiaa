<?php
/*
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC. All rights reserved.                        |
 |                                                                    |
 | This work is published under the GNU AGPLv3 license with some      |
 | permitted exceptions and without any warranty. For full license    |
 | and copyright information, see https://civicrm.org/licensing       |
 +--------------------------------------------------------------------+
 */
namespace Civi\Firewall\Event;

/**
 * Class DeclinedCardEvent
 */
class DeclinedCardEvent extends \Civi\Core\Event\GenericHookEvent {

  /**
   * @var string
   */
  public $ipAddress;

  /**
   * @var string
   */
  public $source;

  /**
   * @var string
   */
  public $eventType;

  /**
   * DeclinedCardEvent constructor.
   *
   * @param string $ipAddress
   * @param string|NULL $source
   */
  public function __construct(string $ipAddress, string $source = NULL) {
    $this->ipAddress = $ipAddress;
    $this->source = $source;
    $this->eventType = 'DeclinedCardEvent';
  }

  /**
   * Use this to trigger an event from your code with a single line
   *
   * @param string $ipAddress
   * @param string|NULL $source
   */
  public static function trigger(string $ipAddress, string $source = NULL) {
    $event = new \Civi\Firewall\Event\DeclinedCardEvent($ipAddress, $source);
    \Civi::dispatcher()->dispatch('civi.firewall.declinedcard', $event);
  }

}
