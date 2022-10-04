<?php

use CRM_Biaproperty_ExtensionUtil as E;
use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\CiviEnvBuilder;

/**
 * FIXME - Add test description.
 *
 * Tips:
 *  - With HookInterface, you may implement CiviCRM hooks directly in the test class.
 *    Simply create corresponding functions (e.g. "hook_civicrm_post(...)" or similar).
 *  - With TransactionalInterface, any data changes made by setUp() or test****() functions will
 *    rollback automatically -- as long as you don't manipulate schema or truncate tables.
 *    If this test needs to manipulate schema or truncate tables, then either:
 *       a. Do all that using setupHeadless() and Civi\Test.
 *       b. Disable TransactionalInterface, and handle all setup/teardown yourself.
 *
 * @group headless
 */
class CRM_Biaproperty_Form_SellPropertyTest extends \PHPUnit\Framework\TestCase implements HeadlessInterface, HookInterface {
  use CRM_Biaproperty_Form_PropertyFormTestTrait;
  use Civi\Test\Api3TestTrait;
  use Civi\Test\ContactTestTrait;

  protected $formClass = 'CRM_Biaproperty_Form_SellProperty';

  protected $propertyId;

  protected $propertyOwnerId;

  protected $sellPropertyActivityType;

  /**
   * Setup used when HeadlessInterface is implemented.
   *
   * Civi\Test has many helpers, like install(), uninstall(), sql(), and sqlFile().
   *
   * @link https://github.com/civicrm/org.civicrm.testapalooza/blob/master/civi-test.md
   *
   * @return \Civi\Test\CiviEnvBuilder
   *
   * @throws \CRM_Extension_Exception_ParseException
   */
  public function setUpHeadless(): CiviEnvBuilder {
    return \Civi\Test::headless()
      ->installMe(__DIR__)
      ->apply();
  }

  public function setUp():void {
    parent::setUp();
    $contactTypeCheck = $this->callAPISuccess('ContactType', 'get', ['name' => 'Members_Property_Owners_']);
    if (count($contactTypeCheck['values']) == 0) {
      $this->callAPISuccess('ContactType', 'create', ['name' => 'Members_Property_Owners_', 'label' => "Members (Property Owners)", 'parent_id' => 'Organization']);
    }
    $this->propertyId = $this->callAPISuccess('Property', 'create', ['roll_no' => '12515251', 'property_address' => 'Test Sell Property Property', 'version' => 4])['id'];
    $this->propertyOwnerId = $this->organizationCreate();
    $this->callAPISuccess('PropertyOwner', 'create', ['property_id' => $this->propertyId, 'owner_id' => $this->propertyOwnerId, 'is_voter' => 1, 'version' => 4]);
    $this->sellPropertyActivityType = $this->callAPISuccess('OptionValue', 'create', ['label' => 'Property sold', 'name' => 'Property sold', 'value' => '1001', 'option_group_id' => 'activity_type']);
    $this->callAPISuccess('System', 'flush', []);
  }

  public function tearDown():void {
    parent::tearDown();
    $activities = $this->callAPISuccess('Activity', 'get', ['activity_type_id' => 'Property sold', 'options' => ['limit' => 0]]);
    foreach ($activities['values'] as $activity) {
      $this->callAPISuccess('Activity', 'delete', ['id' => $activity['id']]);
    }
    $this->callAPISuccess('OptionValue', 'delete', ['id' => $this->sellPropertyActivityType['id']]);
    $this->callAPISuccess('System', 'flush', []);
    $this->callAPISuccess('Contact', 'delete', ['id' => $this->propertyOwnerId, 'skip_undelete' => TRUE]);
    $this->callAPISuccess('Property', 'delete', ['id' => $this->propertyId, 'version' => 4]);
  }

  /**
   * Example: Test that a version is returned.
   */
  public function testSinglePropertyOwner():void {
    $_GET = [];
    $_GET['id'] = $this->propertyId;
    $_GET['cid'] = $this->propertyOwnerId;
    $_REQUEST = $_GET;
    $newPropertyOwner = $this->organizationCreate();
    $form = $this->getFormObject($this->formClass, ['owner_id' => $newPropertyOwner]);
    $form->buildForm();
    try {
      $form->postProcess();
    }
    catch (CRM_Core_Exception_PrematureExitException $e) {
      $propertyOnwerRecord = $this->callAPISuccess('PropertyOwner', 'get', ['owner_id' => $newPropertyOwner, 'version' => 4]);
      $this->assertCount(1, $propertyOnwerRecord['values']);
      $this->assertEquals($this->propertyId, $propertyOnwerRecord['values'][$propertyOnwerRecord['id']]['property_id']);
      $this->assertEquals(1, $propertyOnwerRecord['values'][$propertyOnwerRecord['id']]['is_voter']);
      $this->assertEquals('/index.php?q=civicrm/contact/view&reset=1&cid=' . $this->propertyOwnerId, $e->errorData['url']);
      $this->assertCount(1, $this->callAPISuccess('Activity', 'get', ['activity_type_id' => 'Property sold'])['values']);
    }
  }

  /**
   * Example: Test that we're using a fake CMS.
   */
  public function testSellPropertyCompletelyNewOwner():void {
    $_GET = [];
    $_GET['id'] = $this->propertyId;
    $_GET['cid'] = $this->propertyOwnerId;
    $_REQUEST = $_GET;
    $newOriginalPropertyOwner = $this->organizationCreate();
    $this->callAPISuccess('PropertyOwner', 'create', ['property_id' => $this->propertyId, 'owner_id' => $newOriginalPropertyOwner, 'is_voter' => 0, 'version' => 4]);
    $newPropertyOwner = $this->organizationCreate();
    $form = $this->getFormObject($this->formClass, ['owner_id' => $newPropertyOwner]);
    $form->buildForm();
    try {
      $form->postProcess();
    }
    catch (CRM_Core_Exception_PrematureExitException $e) {
      $propertyOnwerRecord = $this->callAPISuccess('PropertyOwner', 'get', ['owner_id' => $newPropertyOwner, 'version' => 4]);
      $this->assertCount(1, $propertyOnwerRecord['values']);
      $this->assertEquals($this->propertyId, $propertyOnwerRecord['values'][$propertyOnwerRecord['id']]['property_id']);
      $this->assertEquals(1, $propertyOnwerRecord['values'][$propertyOnwerRecord['id']]['is_voter']);
      $this->assertEquals('/index.php?q=civicrm/contact/view&reset=1&cid=' . $this->propertyOwnerId, $e->errorData['url']);
      $this->assertCount(2, $this->callAPISuccess('Activity', 'get', ['activity_type_id' => 'Property sold'])['values']);
    }
  }

  public function testSellPropertyCurrentOwnerChangingtobeVoting() {
    $_GET = [];
    $_GET['id'] = $this->propertyId;
    $_GET['cid'] = $this->propertyOwnerId;
    $_REQUEST = $_GET;
    $newOriginalPropertyOwner = $this->organizationCreate();
    $this->callAPISuccess('PropertyOwner', 'create', ['property_id' => $this->propertyId, 'owner_id' => $newOriginalPropertyOwner, 'is_voter' => 0, 'version' => 4]);
    $form = $this->getFormObject($this->formClass, ['owner_id' => $newOriginalPropertyOwner]);
    $form->buildForm();
    try {
      $form->postProcess();
    }
    catch (CRM_Core_Exception_PrematureExitException $e) {
      $propertyOnwerRecord = $this->callAPISuccess('PropertyOwner', 'get', ['owner_id' => $newOriginalPropertyOwner, 'version' => 4]);
      $this->assertCount(1, $propertyOnwerRecord['values']);
      $this->assertEquals($this->propertyId, $propertyOnwerRecord['values'][$propertyOnwerRecord['id']]['property_id']);
      $this->assertEquals(1, $propertyOnwerRecord['values'][$propertyOnwerRecord['id']]['is_voter']);
      $this->assertEquals('/index.php?q=civicrm/contact/view&reset=1&cid=' . $this->propertyOwnerId, $e->errorData['url']);
      $this->assertCount(1, $this->callAPISuccess('Activity', 'get', ['activity_type_id' => 'Property sold'])['values']);
    }
  }

}
