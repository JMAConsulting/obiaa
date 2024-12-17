<?php

use Civi\Api4\UnitBusiness;
use Civi\Api4\Contact;
use Civi\Api4\Activity;
use Civi\Api4\PropertyOwner;
use Civi\Api4\Unit;


class CRM_Biaproperty_Utils {

  public static function closeBusiness($bid, $uid, $date = NULL) {
//    $bid = CRM_Utils_Type::escape($_GET['bid'], 'Positive');
//    $uid = CRM_Utils_Type::escape($_GET['uid'], 'Positive', FALSE);
    if (!$bid) {
      CRM_Core_Error::statusBounce(ts('Missing contact ID'));
    }
    $entries = UnitBusiness::get(FALSE)
        ->addSelect('id', 'unit_id', 'address.*')
        ->addJoin('Unit AS unit', 'LEFT', ['unit_id', '=', 'unit.id'])
        ->addJoin('Address AS address', 'LEFT', ['unit.address_id', '=', 'address.id'])
        ->addWhere('business_id', '=', $bid)
        ->execute();

    foreach ($entries as $entry) {
      if ($uid && $entry['unit_id'] != $uid) { continue; }
      UnitBusiness::delete(FALSE)
      ->addWhere('id', '=', $entry['id'])
      ->execute();

      Activity::create(FALSE)
        ->addValue('activity_type_id:name', 'Business closed')
        ->addValue('activity_date_time', date('YmdHis', strtotime($date)))
        ->addValue('target_contact_id', $bid)
        ->addValue('assignee_contact_id', $bid)
        ->addValue('source_contact_id', CRM_Core_Session::getLoggedInContactID())
        ->addValue('status_id:name', 'Completed')
        ->addValue('subject', 'Business closed')
        ->addValue('details', 'Business closed at Unit - ' . (!empty($entry['address.street_unit']) ? '#' . $entry['address.street_unit'] . ', ' : '') . $entry['address.street_address'] . ')')
        ->execute();
    }

    if (UnitBusiness::get(FALSE)->addWhere('business_id', '=', $bid)->execute()->count() == 0) {
      $cts = Contact::get(FALSE)
        ->addSelect('contact_sub_type:name')
        ->addWhere('id', '=', $bid)
        ->execute()->first()['contact_sub_type:name'];
      unset($cts[array_search('Members_Businesses_', $cts)]);
      Contact::update(FALSE)
        ->addWhere('id', '=', $bid)
        ->addValue('Business_Details.Close_Date', $date)
        ->addValue('contact_sub_type', $cts)
        ->execute();
    }
    CRM_Core_Session::setStatus(ts('Business closed successfully'), ts('Business closed'), 'success');
    CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/contact/view', 'cid=' . $bid));
  }

  public static function assignVote() {
   $oid =  CRM_Utils_Type::escape($_GET['oid'], 'Positive');
   $pid =  CRM_Utils_Type::escape($_GET['pid'], 'Positive');
   $title = CRM_Utils_Type::escape($_GET['title'], 'String');
    if (!$pid || !$oid) {
      CRM_Core_Session::setStatus('', ts('Missing essential property id and/or owner id.'), 'error');
      CRM_Utils_System::civiExit(1);
    }
    PropertyOwner::update(FALSE)
      ->addValue('is_voter', 0)
      ->addWhere('is_voter', '=', 1)
      ->addWhere('property_id', '=', $pid)
      ->execute();
    $id = PropertyOwner::get(FALSE)
      ->addSelect('id')
      ->addWhere('property_id', '=', $pid)
      ->addWhere('owner_id', '=', $oid)
      ->execute()->first()['id'];
    PropertyOwner::update(FALSE)
      ->addValue('is_voter', 1)
      ->addWhere('id', '=', $id)
      ->execute();
    CRM_Utils_System::redirect(CRM_Utils_System::url('/civicrm/biaunits#?pid=' . $pid . '&title=' . $title ));
  }

  public static function jqUnit() {
    $result = [];
    $units = Unit::get(FALSE)
      ->addSelect('address_id.street_address', 'address_id.street_unit')
      ->addWhere('property_id', '=', $_GET['pid'])
      ->setLimit(100)
      ->execute();
    foreach ($units as $unit) {
      $result[] = [
        'key' => $unit['id'],
        'value' => $unit['address_id.street_unit'] ? '#' . $unit['address_id.street_unit'] . ' - ' . $unit['address_id.street_address'] : $unit['address_id.street_address'],
      ];
    }
    CRM_Utils_JSON::output($result);
  }

}
