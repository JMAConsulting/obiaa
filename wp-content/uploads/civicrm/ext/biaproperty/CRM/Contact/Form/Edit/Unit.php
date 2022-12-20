<?php

use CRM_Biaproperty_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Contact_Form_Edit_Unit {

    public static function buildQuickForm(&$form, $addressBlockCount = NULL, $sharing = TRUE, $inlineEdit = FALSE) {
      $form->addEntityRef('property_id',  E::ts('Property'), [
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
      $form->add('select', 'unit_id', 'Unit', NULL, TRUE, $params);
      $form->assign('elements', ['property_id', 'unit_id']);
      $form->assign('url', CRM_Utils_System::url('civicrm/unit/form', 'reset=1&action=add&context=create&pid='));
      $addPropertyURL = CRM_Utils_System::url('civicrm/property/form', ['reset' => 1, 'action' => 'add', 'context' => 'create'], FALSE, NULL, TRUE, FALSE, FALSE);
      $addUnitURL = str_replace('&amp;', '&', CRM_Utils_System::url('civicrm/unit/form', ['reset' => 1, 'action' => 'add', 'context' => 'create', 'pid' => ''], FALSE, NULL, TRUE, FALSE, FALSE));
      CRM_Core_Resources::singleton()->addScript(
       "CRM.$(function($) {
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
               val = $(this).val();
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
    }

}
