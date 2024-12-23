<?php

use CRM_Biaproperty_ExtensionUtil as E;
use Civi\Api4\Activity;
use Civi\Api4\Contact;
use Civi\Api4\Unit;
use Civi\Api4\UnitBusiness;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Biaproperty_Form_AddBuisness extends CRM_Core_Form {

    /**
     * Business Contact ID
     * @var int
     */
    protected $_bid;

    /**
     * Old Unit ID
     * @var int
     */
    protected $_uid;

    /**
     * Are we changing the title of the business
     * @var int
     */
    protected $_changeTitle;

    /**
     * Preprocess form.
     *
     * This is called before buildForm. Any pre-processing that
     * needs to be done for buildForm should be done here.
     *
     * This is a virtual function and should be redefined if needed.
     */
    public function preProcess() {
      parent::preProcess();

      $this->_action = CRM_Utils_Request::retrieve('action', 'String', $this);
      $this->_changeTitle = CRM_Utils_Request::retrieve('change_title', 'Positive', $this, FALSE);
      $this->_bid = CRM_Utils_Request::retrieve('bid', 'Positive', $this, FALSE);
      $this->_uid = CRM_Utils_Request::retrieve('uid', 'Positive', $this, FALSE);
      $status = CRM_Utils_Request::retrieve('status', 'Positive', $this, FALSE);
      if ($this->_changeTitle && empty($this->_bid)) {
        throw new \CRM_Core_Exception('If Moving business within the BIA need to supply a buisiness contact id');
      }
      if (empty($this->_bid) && empty($this->_uid)) {
        throw new \CRM_Core_Exception('Need to supply one of either a unit id or a business id');
      }
      if (!$this->_changeTitle && !empty($this->_bid) && $status == 1) {
        CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/contact/view',  ['reset' => 1, 'cid' => $this->_bid, 'selectedChild' => 'afsearchUnit1']));
      }
      if (!empty($this->_bid)) {
        $subTypes = Contact::get(FALSE)->addSelect('contact_sub_type:label')->addWhere('id', '=', $this->_bid)->execute()->first()['contact_sub_type:label'] ?? [];
        if (in_array('BIA Staff', $subTypes) || in_array('Government Staff/Member', $subTypes)) {
          CRM_Core_Error::statusBounce(E::ts('You cannot choose business contact of type Staff or Government Staff/Member'));
        }
      }

      $this->assign('action', $this->_action);
      $this->addFormRule([__CLASS__, 'formRule'], $this);

      $title = ($this->_changeTitle ? 'Move business within BIA' : ((!empty($this->_uid)) ? 'Add Business' : 'Become Member (Business)'));
      CRM_Utils_System::setTitle($title);
    }

    public static function formRule($fields, $files, $self) {
      $errors = [];
      // if you are adding business which is already linked to chosen unit then throw validation error
      if (!empty($fields['unit_id']) && !empty($fields['business_id'])) {
        $count = UnitBusiness::get(FALSE)
          ->addWhere('unit_id', '=', $fields['unit_id'])
          ->addWhere('business_id', '=', $fields['business_id'])
          ->execute()
          ->count();
        if ($count > 0) {
          $errors['unit_id'] = E::ts('Chosen contact already has a business at given property\'s unit. Please choose a different unit.');
        }
      }
      if (!empty($fields['business_id'])) {
         $subTypes = Contact::get(FALSE)->addSelect('contact_sub_type:label')->addWhere('id', '=', $fields['business_id'])->execute()->first()['contact_sub_type:label'] ?? [];
         if (in_array('BIA Staff', $subTypes) || in_array('Government Staff/Member', $subTypes)) {
           $errors['business_id'] = E::ts('You cannot choose business contact of type Staff or Government Staff/Member');
         }
      }

      return $errors;
    }

    public function buildQuickForm() {
      $elementNames = ['property_id', 'unit_id', 'business_id'];
      $defaults = [];
      if ($this->_changeTitle && $this->_bid) {
        $unitBusinesses = UnitBusiness::get(FALSE)
          ->addSelect('id', 'address.*')
          ->addJoin('Unit AS unit', 'INNER', ['unit_id', '=', 'unit.id'])
          ->addJoin('Address AS address', 'INNER', ['unit.address_id', '=', 'address.id'])
          ->addWhere('business_id', '=', $this->_bid)
          ->execute();
        $defaultID = NULL;
        foreach ($unitBusinesses as $unitBusiness) {
          $defaultID = ($this->_uid && $unitBusiness['unit_id'] == $this->_uid) ? $unitBusiness['id'] : NULL;
          $options[$unitBusiness['id']] = !empty($unitBusiness['address.street_unit']) ? $unitBusiness['address.street_unit'] . ' - ' . $unitBusiness['address.street_address'] : $unitBusiness['address.street_address'];
        }
        $defaultID = $defaultID ?: key($options);
        $defaults['unit_business_id'] = $defaultID;
        $this->add('select', 'unit_business_id', E::ts('Previous Unit #'), $options, TRUE, ['class' => 'crm-select2', 'multiple' => TRUE]);
        $elementNames = array_merge(['unit_business_id'], $elementNames);
      }
      $options = $units = [];
      $propertyElement = $this->addEntityRef('property_id',  E::ts('Property'), [
        'create' => TRUE,
        'entity' => 'Property',
        'api' => [
          'params' => [
            'options' => ['limit' => 100],
          ]
        ]
      ]);
      $params = [
        'placeholder' => '- select Unit -',
        'class' => 'crm-select2',
        'data-select-prompt' => '- select Unit -',
        'data-none-prompt' => 'no Unit found',
        'data-callback' => 'civicrm/ajax/jqUnit',
      ];
      $unitElement = $this->add('select', 'unit_id', E::ts('Unit #'), NULL, TRUE, $params);
      $businessElement = $this->addEntityRef('business_id', E::ts('Business'), ['placeholder' => '- Select Business -', 'create' => TRUE], empty($this->_bid));
      if (!empty($this->_uid) && !$this->_changeTitle) {
        $unit = Unit::get(FALSE)
          ->addSelect('address_id.street_address', 'address_id.street_unit', 'property_id')
          ->addWhere('id', '=', $this->_uid)
          ->execute()->first();
        $this->getElement('unit_id')->addOption(!empty($unit['address_id.street_unit']) ? '#' . $unit['address_id.street_unit'] . ' - ' . $unit['address_id.street_address'] : $unit['address_id.street_address'], $this->_uid);
        $defaults['unit_id'] = $this->_uid;
        $defaults['property_id'] = $unit['property_id'];
      }
      if (!empty($this->_bid)) {
        $defaults['business_id'] = $this->_bid;
      }
      $this->setDefaults($defaults);
      $hideBusinessField = 'false';
      // if we have a business id we are either moving or looking for a unit to add this business to it.
      if (!empty($this->_bid)) {
        $businessElement->freeze();
        $hideBusinessField = 'true';
      }
      elseif (!empty($this->_uid)) {
        // ok we have come from the unit listing and are linking a vacant unit to a business.
        $unitElement->freeze();
        $propertyElement->freeze();
      }
      $this->assign('elementNames', $elementNames);
      $addPropertyURL = CRM_Utils_System::url('civicrm/property/form', ['reset' => 1, 'action' => 'add', 'context' => 'create'], FALSE, NULL, FALSE, FALSE, FALSE);
      $addUnitURL = CRM_Utils_System::url('civicrm/unit/form', ['reset' => 1, 'action' => 'add', 'context' => 'create', 'pid' => ''], FALSE, NULL, FALSE, FALSE, FALSE);
      $this->assign('url', $addUnitURL);
      CRM_Core_Resources::singleton()->addScript(
        "CRM.$(function($) {
          if ({$hideBusinessField}) {
            $('label[for=\"business_id\"]').parent().parent().hide();
          }
         $('.add-unit-link').on('crmPopupFormSuccess.crmLivePage', function(e){
           var \$form = $(this).closest('form'),
             \$target = $('select[name=\"unit_id\"]', \$form),
             data = \$target.data(),
             val = $('#property_id').val();
           \$target.addClass('loading');
           $.getJSON(CRM.url(data.callback), {pid: val}, function(vals) {
             \$target.prop('disabled', false).removeClass('loading');
             CRM.utils.setOptions(\$target, vals || [], (vals && vals.length ? data.selectPrompt : data.nonePrompt));
             if (vals.length) {
               \$target.val(vals[vals.length - 1].key);
             }
           });
         });
         $('#property_id').on('change', function(e){
             var \$form = $(this).closest('form'),
               \$target = $('select[name=\"unit_id\"]', \$form),
               data = \$target.data(),
               val = $(this).val() + '&bid=0';
             \$target.prop('disabled', true);
             $('#add-unit').show();
             $('.add-unit-link').attr('href', '{$addUnitURL}' + val);
             if (\$target.is('select.crm-chain-select-control')) {
               $('select[name=\"unit_id\"]', \$form).prop('disabled', true).blur();
             }
             if (!(val && val.length)) {
               CRM.utils.setOptions(\$target.blur(), [], data.emptyPrompt);
             } else {
               \$target.addClass('loading');
               $.getJSON(CRM.url(data.callback), {pid: val, bid: 0}, function(vals) {
                 \$target.prop('disabled', false).removeClass('loading');
                 CRM.utils.setOptions(\$target, vals || [], (vals && vals.length ? data.selectPrompt : data.nonePrompt));
               });
             }
         });
         $('#add-unit').hide();
         CRM.config.entityRef.links.Property = [
          {label: 'Add Property', url: '{$addPropertyURL}'}
         ];
      });
     ");

      $this->addButtons([
        [
          'type' => 'upload',
          'name' => $this->_changeTitle ? E::ts('Move') : E::ts('Submit'),
          'isDefault' => TRUE,
        ],
        [
          'type' => 'cancel',
          'name' => E::ts('Cancel'),
        ],
      ]);
      parent::buildQuickForm();
    }

    public function postProcess() {
      $values = $this->controller->exportValues();

      // For both Move business within BIA AND Become Member (Business) set the selected unit status to Occupied
      Unit::update(FALSE)
        ->addWhere('id', '=', $values['unit_id'])
        ->addValue('unit_status', 1)
        ->execute();
      $contact = Contact::get(FALSE)
          ->addWhere('id', '=', $values['business_id'])
          ->execute()->first();
      $subTypes = (array) $contact['contact_sub_type'];
      // #1 Become a Member (Business)
      if (!$this->_changeTitle || (empty($subTypes) || !in_array('Members_Businesses_', $subTypes))) {
        // 1.1 First convert the contact to Member (Business) subtype
        $subTypes[] = 'Members_Businesses_';
        $params = [
          'id' => $values['business_id'],
          'contact_type' => 'Organization',
          'contact_sub_type' => $subTypes,
        ];
        \Civi::log()->debug('{contact} {subTypes}', ['contact' => $contact, 'subTypes' => $subTypes]);
        // 1.2 If individial then become a organization first with Member (Business) activity, record a activity for these contact type change
        if ($contact['contact_type'] == 'Individual') {
          // 1.3 Wipe out the individual subtype (if any)
          $params['contact_sub_type'] = ['Members_Businesses_'];
          $params['organization_name'] = $contact['display_name'];
          \Civi\Api4\Activity::create(FALSE)
            ->addValue('activity_type_id:name', 'Contact type changed')
            ->addValue('target_contact_id', $values['business_id'])
            ->addValue('assignee_contact_id', $values['business_id'])
            ->addValue('source_contact_id', CRM_Core_Session::getLoggedInContactID())
            ->addValue('status_id:name', 'Completed')
            ->addValue('subject', 'Business contact type changed from Individual to Organization')
            ->execute();
        }
        civicrm_api4('Contact', 'update', [
          'values' => $params,
        ]);

        // 1.4 After conversion, use the contact ID to create a new Unit-business record
        UnitBusiness::create(FALSE)
          ->addValue('unit_id', $values['unit_id'])
          ->addValue('business_id', $values['business_id'])
          ->execute();
      }
      else {
        // #2 Move Business within BIA

        // 2.1 If user has selected previous units that would be moved then :
        if (!empty($values['unit_business_id'])) {
          $unitIds = UnitBusiness::get(FALSE)->addWhere('id', 'IN', (array) $values['unit_business_id'])->execute()->column('unit_id');
          foreach ((array) $values['unit_business_id'] as $key => $id) {
            // 2.2 Update the existing unit-business record with the newly selected unit
            if ($key == 0) {
               $movedBusinessID = UnitBusiness::update(FALSE)
                  ->addValue('unit_id', $values['unit_id'])
                  ->addWhere('id', '=', $id)
                  ->execute()->first()['id'];
             }
             else {
                // 2.2 Delete all other unit business records
                UnitBusiness::delete(FALSE)->addWhere('id', '=', $id)->execute();
             }
          }
          foreach ($unitIds as $unitId) {
            $unitBusinessRecordCount = UnitBusiness::get(FALSE)->addWhere('unit_id', '=', $unitId)->execute()->count();
            if ($unitBusinessRecordCount == 0) {
              Unit::update(FALSE)->addValue('unit_status', 2)->addWhere('id', '=', $unitId)->execute();
            }
          }

          // 2.3 Record activity for this 'Move Business within BIA' action
          Activity::create(FALSE)
            ->addValue('activity_type_id:name', 'Move Business within BIA')
            ->addValue('target_contact_id', $values['business_id'])
            ->addValue('assignee_contact_id', $values['business_id'])
            ->addValue('source_contact_id', CRM_Core_Session::getLoggedInContactID())
            ->addValue('status_id:name', 'Completed')
            ->addValue('subject', 'Move Business within BIA')
            ->addValue('source_record_id', $movedBusinessID)
            ->execute();
        }
        else {
          // 2.4 or add new business
          UnitBusiness::create(FALSE)
            ->addValue('unit_id', $values['unit_id'])
            ->addValue('business_id', $values['business_id'])
            ->execute();
        }
      }
      if ($this->_uid) {
        $property = Unit::get(FALSE)->addWhere('id', '=', $this->_uid)->addSelect('property_id', 'property_id.property_address', 'property_id.name')->execute()->first();
        $title = (!empty($property['property_id.name'])) ? $property['property_id.name'] . ' - ' . $property['property_id.property_address'] : $property['property_id.property_address'];
        CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/biaunits') . '#?' . CRM_Utils_System::makeQueryString(['pid' => $property['property_id'], 'title' => $title, 'reset' => 1]));
      }
      parent::postProcess();
      // Now redirect to the business contact's unit tab on summary page.
      CRM_Utils_System::redirect(CRM_Utils_System::url('civicrm/contact/view',  ['reset' => 1, 'cid' => $this->_bid, 'selectedChild' => 'afsearchUnit1']));
    }

}
