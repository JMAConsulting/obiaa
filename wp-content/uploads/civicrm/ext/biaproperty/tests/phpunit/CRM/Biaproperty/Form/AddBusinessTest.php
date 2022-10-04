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
class CRM_Biaproperty_Form_AddBusinessTest extends \PHPUnit\Framework\TestCase implements HeadlessInterface, HookInterface {
  use CRM_Biaproperty_Form_PropertyFormTestTrait;
  use Civi\Test\Api3TestTrait;
  use Civi\Test\ContactTestTrait;

  protected $formClass = 'CRM_Biaproperty_Form_AddBuisness';

  protected $propertyId;

  protected $unitId;

  protected $propertyOwnerId;

  protected $movePropertyActivityType;

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
    $propertyOwnerTypeCheck = $this->callAPISuccess('ContactType', 'get', ['name' => 'Members_Property_Owners_']);
    if (count($propertyOwnerTypeCheck['values']) == 0) {
      $this->callAPISuccess('ContactType', 'create', ['name' => 'Members_Property_Owners_', 'label' => "Members (Property Owners)", 'parent_id' => 'Organization']);
    }
    $businessTypeCheck = $this->callAPISuccess('ContactType', 'get', ['name' => 'Members_Businesses_']);
    if (count($businessTypeCheck['values']) == 0) {
      $this->callAPISuccess('ContactType', 'create', ['name' => 'Members_Businesses_', 'label' => "Members (Business)", 'parent_id' => 'Organization']);
    }
    $this->propertyId = $this->callAPISuccess('Property', 'create', ['roll_no' => '12515251', 'property_address' => 'Test Sell Property Property', 'version' => 4])['id'];
    $unitAddress = $this->callAPISuccess('Address', 'Create', ['street_unit' => 'Whole Property', 'street_address' => '15 Test St', 'city' => 'Hamilton', 'postal_code' => '1525', 'version' => 4]);
    $this->unitId = $this->callAPISuccess('Unit', 'create', ['address_id' => $unitAddress['id'], 'property_id' => $this->propertyId, 'version' => 4])['id'];
    $this->propertyOwnerId = $this->organizationCreate();
    $this->callAPISuccess('PropertyOwner', 'create', ['property_id' => $this->propertyId, 'owner_id' => $this->propertyOwnerId, 'is_voter' => 1, 'version' => 4]);
    $this->movePropertyActivityType = $this->callAPISuccess('OptionValue', 'create', ['label' => 'Move Business within BIA', 'name' => 'Move Business within BIA', 'value' => '1002', 'option_group_id' => 'activity_type']);
    $this->callAPISuccess('System', 'flush', []);
  }

  public function tearDown():void {
    $this->callAPISuccess('OptionValue', 'delete', ['id' => $this->movePropertyActivityType['id']]);
    $this->callAPISuccess('System', 'flush', []);
    $this->callAPISuccess('Unit', 'delete', ['id' => $this->unitId, 'version' => 4]);
    $this->callAPISuccess('Property', 'delete', ['id' => $this->propertyId, 'version' => 4]);
    parent::tearDown();
  }

  /**
   * Test that Submitting the form where by we are assigning a business a unit it works
   */
  public function testAddBusinessFromUnitListing():void {
    $_GET = [];
    $_GET['uid'] = $this->unitId;
    $_REQUEST = $_GET;
    $newBusiness = $this->organizationCreate();
    $unit = $this->callAPISuccess('Unit', 'get', ['id' => $this->unitId, 'version' => 4])['values'][$this->unitId];
    $form = $this->getFormObject($this->formClass, ['business_id' => $newBusiness, 'unit_id' => $this->unitId, 'property_id' => $unit['property_id']]);
    $form->buildForm();
    $this->assertEquals(['unit_id' => $this->unitId, 'property_id' => $unit['property_id']], $form->_defaultValues);
    try {
      $form->postProcess();
    }
    catch (CRM_Core_Exception_PrematureExitException $e) {
      $this->assertEquals('/index.php?q=civicrm/biaunits#?pid=' . $this->propertyId .'&title=Test+Sell+Property+Property&reset=1', $e->errorData['url']);
      $this->assertCount(1, $this->callAPISuccess('UnitBusiness', 'get', ['unit_id' => $this->unitId, 'version' => 4])['values']);
      $this->assertEquals(['Members_Businesses_'], $this->callAPISuccess('Contact', 'get', ['id' => $newBusiness])['values'][$newBusiness]['contact_sub_type']);
    }
    $this->callAPISuccess('Contact', 'delete', ['id' => $newBusiness, 'skip_undelete' => TRUE]);
  }

  /**
   * Test that Submitting the form where by we are assigning a business a unit it works
   */
  public function testAddBusinessForPropertyOwner():void {
    $_GET = [];
    $newBusiness = $this->organizationCreate(['contact_sub_type' => 'Members_Property_Owners_']);
    $_GET['bid'] = $newBusiness;
    $_REQUEST = $_GET;
    $unit = $this->callAPISuccess('Unit', 'get', ['id' => $this->unitId, 'version' => 4])['values'][$this->unitId];
    $form = $this->getFormObject($this->formClass, ['business_id' => $newBusiness, 'unit_id' => $this->unitId, 'property_id' => $unit['property_id']]);
    $form->buildForm();
    $this->assertEquals(['business_id' => $newBusiness], $form->_defaultValues);
    try {
      $form->postProcess();
    }
    catch (CRM_Core_Exception_PrematureExitException $e) {
      $this->assertCount(1, $this->callAPISuccess('UnitBusiness', 'get', ['unit_id' => $this->unitId, 'version' => 4])['values']);
      $this->assertEquals(['Members_Property_Owners_', 'Members_Businesses_'], $this->callAPISuccess('Contact', 'get', ['id' => $newBusiness])['values'][$newBusiness]['contact_sub_type']);
    }
    $this->callAPISuccess('Contact', 'delete', ['id' => $newBusiness, 'skip_undelete' => TRUE]);
  }

  /**
   * Test that going from business to add a new unit works.
   */
  public function testAddUnitToBusiness():void {
    $newBusiness = $this->organizationCreate();
    $_GET = [];
    $_GET['bid'] = $newBusiness;
    $_REQUEST = $_GET;
    $form = $this->getFormObject($this->formClass, ['unit_id' => $this->unitId, 'property_id' => $this->propertyId, 'business_id' => $newBusiness]);
    $form->buildForm();
    $this->assertEquals(['business_id' => $newBusiness], $form->_defaultValues);
    try {
      $form->postProcess();
    }
    catch (CRM_Core_Exception_PrematureExitException $e) {
      $this->assertCount(1, $this->callAPISuccess('UnitBusiness', 'get', ['unit_id' => $this->unitId, 'version' => 4])['values']);
      $this->assertEquals(['Members_Businesses_'], $this->callAPISuccess('Contact', 'get', ['id' => $newBusiness])['values'][$newBusiness]['contact_sub_type']);
    }
    $this->callAPISuccess('Contact', 'delete', ['id' => $newBusiness, 'skip_undelete' => TRUE]);
  }

  public function testMoveBusiness(): void {
    $this->createLoggedInUser();
    $newBusiness = $this->organizationCreate(['contact_sub_type' => ['Members_Businesses_']]);
    $unitBusiness = $this->callAPISuccess('UnitBusiness', 'create', ['unit_id' => $this->unitId, 'business_id' => $newBusiness, 'version' => 4]);
    $_GET = [];
    $_GET['bid'] = $newBusiness;
    $_GET['change_title'] = 1;
    $_REQUEST = $_GET;
    $newUnitAddress = $this->callAPISuccess('Address', 'Create', ['street_unit', '#2', 'street_address' => '15 Test St', 'city' => 'Hamilton', 'postal_code' => '1525', 'version' => 4]);
    $newUnit = $this->callAPISuccess('Unit', 'create', ['address_id' => $newUnitAddress['id'], 'property_id' => $this->propertyId, 'version' => 4])['id'];
    $form = $this->getFormObject($this->formClass, ['unit_id' => $newUnit, 'property_id' => $this->propertyId, 'unit_business_id' => $unitBusiness['id'], 'business_id' => $newBusiness]);
    $form->buildForm();
    $this->assertEquals(['business_id' => $newBusiness, 'unit_business_id' => $unitBusiness['id']], $form->_defaultValues);
    try {
      $form->postProcess();
    }
    catch (CRM_Core_Exception_PrematureExitException $e) {
      $this->assertCount(1, $this->callAPISuccess('UnitBusiness', 'get', ['unit_id' => $newUnit, 'version' => 4])['values']);
      $this->assertCount(0, $this->callAPISuccess('UnitBusiness', 'get', ['unit_id' => $this->unitId, 'version' => 4])['values']);
      $this->assertEquals(['Members_Businesses_'], $this->callAPISuccess('Contact', 'get', ['id' => $newBusiness])['values'][$newBusiness]['contact_sub_type']);
      $this->assertEquals(2, $this->callAPISuccess('Unit', 'get', ['id' => $this->unitId, 'version' => 4])['values'][$this->unitId]['unit_status']);
      $this->assertEquals(1, $this->callAPISuccess('Unit', 'get', ['id' => $newUnit, 'version' => 4])['values'][$newUnit]['unit_status']);
    }
    $this->callAPISuccess('Contact', 'delete', ['id' => $newBusiness, 'skip_undelete' => TRUE]);
  }

}
