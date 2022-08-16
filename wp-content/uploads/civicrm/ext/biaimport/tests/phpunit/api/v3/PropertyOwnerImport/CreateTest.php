<?php

use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 * PropertyOwnerImport.Create API Test Case
 * This is a generic test class implemented with PHPUnit.
 * @group headless
 */
class api_v3_PropertyOwnerImport_CreateTest extends \PHPUnit\Framework\TestCase implements HeadlessInterface, HookInterface, TransactionalInterface {
  use \Civi\Test\Api3TestTrait;
  use \Civi\Test\ContactTestTrait;

  /**
   * Member Property Owner contact type
   * @var array
   */
  protected $propertyOwnerContactType;

  /**
   * Set up for headless tests.
   *
   * Civi\Test has many helpers, like install(), uninstall(), sql(), and sqlFile().
   *
   * See: https://docs.civicrm.org/dev/en/latest/testing/phpunit/#civitest
   */
  public function setUpHeadless() {
    return \Civi\Test::headless()
      ->install('biaproperty')
      ->installMe(__DIR__)
      ->apply();
  }

  /**
   * The setup() method is executed before the test is executed (optional).
   */
  public function setUp(): void {
    /*$this->propertyOwnerContactType = $this->callAPISuccess('ContactType', 'create', [
      'name' => 'Members_Property_Owners_', 
      'parent_id' => 'Organization',
      'label' => 'Property Owner (members)',
    ]);*/
    parent::setUp();
  }

  /**
   * The tearDown() method is executed after the test was executed (optional)
   * This can be used for cleanup.
   */
  public function tearDown(): void {
    parent::tearDown();
  }

  /**
   * Simple example test case.
   *
   * Note how the function name begins with the word "test".
   */
  public function testPropertyOwnerImport() {
    $this->createLoggedInUser();
    $params = [
      'roll_no' => '20144562400000',
      'property_name' => 'Jackson Square Mall',
      'street_address' => '2 King St W',
      'city' => 'Hamilton',
      'postal_code' => 'L8P 1A1',
      'owner_1_first_name' => 'Sandra',
      'owner_1_last_name' => 'Claus',
      'owner_1_street_address' => '57 Grandview Avenue',
      'owner_1_supplemental_address_1' => 'C/0 Joe Murray',
      'owner_1_city' => 'Toronto',
      'owner_1_province' => 'ON',
      'owner_1_postal_code' => 'H0H 0H0',
      'owner_1_country' => 'Canada',
      'property_manager_first_name' => 'Edsel',
      'property_manager_last_name' => 'lee',
      'property_manager_email' => 'edsel.lopez+testproperty@jmaconsulting.biz',
    ];
    $this->callAPISuccess('PropertyOwnerImport', 'create', $params);
    $address = $this->callAPISuccess('Address', 'get', [
      'street_address' => $params['street_address'],
      'city' => $params['city'],
      'postal_code' => $params['postal_code'],
    ]);
    $property = $this->callAPISuccess('Property', 'get', [
       'version' => 4,
       'address_id' => $address['id'],
       'roll_no' => '20144562400000',
     ]);
     $propertyOwner = $this->callAPISuccess('PropertyOwner', 'get', [
       'version' => 4,
       'property_id' => key($property['values']),
     ]);
     $this->assertEquals($address['id'], $property['values'][key($property['values'])]['address_id']);
     $this->assertCount(1, $propertyOwner['values']);
     $propertyOwnerKey = key($propertyOwner['values']);
     $this->assertEquals(1, $propertyOwner['values'][$propertyOwnerKey]['is_voter']);
     $contact = $this->callAPISuccess('Contact', 'getsingle', ['id' => $propertyOwner['values'][$propertyOwnerKey]['owner_id']]);
     $this->assertEquals('Sandra Claus', $contact['organization_name']);
     $propertyOwnerRelationship = $this->callAPISuccess('Relationship', 'getsingle', ['contact_id_b' => $contact['id']]);
     $propertyOwnerContact = $this->callAPISuccess('Contact', 'getsingle', ['id' => $propertyOwnerRelationship['contact_id_a']]);
     $this->assertEquals('Edsel', $propertyOwnerContact['first_name']);
     $this->assertEquals('lee', $propertyOwnerContact['last_name']);
     $this->assertEquals($params['property_manager_email'], $this->callAPISuccess('Email', 'getsingle', ['contact_id' => $propertyOwnerContact['id']])['email']);
  }

  /**
   * Test Importing the same property twice
   */
  public function testPropertyOwnerMultipleTimes() {
    $this->createLoggedInUser();
    $params = [
      'roll_no' => '20144562400000',
      'property_name' => 'Jackson Square Mall',
      'street_address' => '2 King St W',
      'city' => 'Hamilton',
      'postal_code' => 'L8P 1A1',
      'owner_1_first_name' => 'Sandra',
      'owner_1_last_name' => 'Claus',
      'owner_1_street_address' => '57 Grandview Avenue',
      'owner_1_supplemental_address_1' => 'C/0 Joe Murray',
      'owner_1_city' => 'Toronto',
      'owner_1_province' => 'ON',
      'owner_1_postal_code' => 'H0H 0H0',
      'owner_1_country' => 'Canada',
      'property_manager_first_name' => 'Edsel',
      'property_manager_last_name' => 'lee',
      'property_manager_email' => 'edsel.lopez+testproperty@jmaconsulting.biz',
    ];
    $this->callAPISuccess('PropertyOwnerImport', 'create', $params);
    $this->callAPIFailure('PropertyOwnerImport', 'create', $params, 'Cannot import property as it already exists in the database');
  }

  public function testMultiplePropertyOwnerImport() {
    $this->createLoggedInUser();
    $params = [
      'roll_no' => '20144562400000',
      'property_name' => 'Jackson Square Mall',
      'street_address' => '2 King St W',
      'city' => 'Hamilton',
      'postal_code' => 'L8P 1A1',
      'owner_1_first_name' => 'Sandra',
      'owner_1_last_name' => 'Claus',
      'owner_1_street_address' => '57 Grandview Avenue',
      'owner_1_supplemental_address_1' => 'C/0 Joe Murray',
      'owner_1_city' => 'Toronto',
      'owner_1_province' => 'ON',
      'owner_1_postal_code' => 'H0H 0H0',
      'owner_1_country' => 'Canada',
      'owner_2_first_name' => 'Bugs',
      'owner_2_last_name' => 'Bunny',
      'owner_2_mobile_phone' => '555 555 5412',
      'owner_2_company_name' => 'My Big New Company',
      'owner_2_street_address' => 'Treehouse',
      'owner_2_unit' => '#2',
      'owner_2_supplemental_address_1' => 'C/0 Daffy Duck',
      'owner_2_city' => 'Toronto',
      'owner_2_province' => 'ON',
      'owner_2_postal_code' => 'H0H 0H0',
      'owner_2_country' => 'Canada',
      'owner_2_email' => 'support+testowner2@jmaconsulting.biz',
      'property_manager_first_name' => 'Edsel',
      'property_manager_last_name' => 'lee',
      'property_manager_email' => 'edsel.lopez+testproperty@jmaconsulting.biz',
    ];
    $this->callAPISuccess('PropertyOwnerImport', 'create', $params);
    $PropertyOwnerContact2 = $this->callAPISuccess('Contact', 'getsingle', ['contact_type' => 'Organization', 'organization_name' => 'My Big New Company']);
    $propertyOwner = $this->callAPISuccess('PropertyOwner', 'get', ['version' => 4, 'owner_id' => $PropertyOwnerContact2['id']]);
    $propertyOwnerRecordId = key($propertyOwner['values']);
    $this->assertEquals(0, $propertyOwner['values'][$propertyOwnerRecordId]['is_voter']);
    $propertyOwners = $this->callAPISuccess('PropertyOwner', 'get', ['version' => 4, 'property_id' => $propertyOwner['values'][$propertyOwnerRecordId]['property_id']]);
    $this->assertCount(2, $propertyOwners['values']);
    $propertyOwnerContact = $this->callAPISuccess('Contact', 'get', ['first_name' => 'Bugs', 'last_name' => 'Bunny', 'employer_id' => $PropertyOwnerContact2['id']]);
    $this->assertCount(1, $propertyOwnerContact['values']);
    $this->assertEquals('555 555 5412', $this->callAPISuccess('Phone', 'getsingle', ['contact_id' => $propertyOwnerContact['id']])['phone']);
  }

}
