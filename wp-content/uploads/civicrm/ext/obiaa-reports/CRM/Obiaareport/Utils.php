<?php
use CRM_Obiaareport_ExtensionUtil as E;

class CRM_Obiaareport_Utils {

  public static function addMembershipFilter(&$filters) {
    foreach (['Region', 'BIA'] as $name) {
      $customField = \Civi\Api4\CustomField::get(FALSE)
        ->addSelect('label', 'option_group_id:name', 'column_name')
        ->addWhere('name', '=', $name)
        ->addWhere('custom_group_id:name', '=', 'Membership_Status')
        ->execute()->first();
        $filters[$name] = [
          'title' => $cfFilter['label'],
          'type' => CRM_Utils_Type::T_STRING,
          'dbAlias' => 'member.' . $customField['column_name'],
        ];
        if (!empty($customField['option_group_id:name'])) {
          $filters[$name]['operatorType'] = CRM_Report_Form::OP_MULTISELECT;
          $filters[$name]['options'] = CRM_Core_OptionGroup::values($customField['option_group_id:name']);
        }
     }
  }

  public static function addMembershipTableJoin($entity = 'Business') {
    $join = '';
    if ($entity == 'Business') {
      return 'LEFT JOIN civicrm_value_membership_st_12 member ON member.entity_id = civicrm_unit_business.business_id';
    }
    elseif ($entity == 'Property') {
      return 'LEFT JOIN civicrm_property p ON p.id = civicrm_unit.property_id
        LEFT JOIN civicrm_property_owner po ON po.property_id = p.id
        LEFT JOIN civicrm_value_membership_st_12 member ON member.entity_id = po.owner_id
      ';
    }
    elseif ($entity == 'ActivityContact') {
      return 'LEFT JOIN civicrm_value_membership_st_12 member ON member.entity_id = civicrm_activity_contact.contact_id';
    }

    return $join;
  }

}
