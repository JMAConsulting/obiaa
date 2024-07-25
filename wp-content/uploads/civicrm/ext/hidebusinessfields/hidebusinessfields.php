<?php

require_once 'hidebusinessfields.civix.php';

use CRM_Hidebusinessfields_ExtensionUtil as E;

const ADD_ORGANISATION_FORM = "placeholder";

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function hidebusinessfields_civicrm_config(&$config): void {
  _hidebusinessfields_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function hidebusinessfields_civicrm_install(): void {
  _hidebusinessfields_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function hidebusinessfields_civicrm_enable(): void {
  _hidebusinessfields_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_buildForm().
 * 
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_buildForm
 */
function hidebusinessfields_civicrm_buildForm($formName, &$form) {
  if (
      ($formName == "CRM_Contact_Form_Contact") && 
      ($form->getVar('_contactType') == "Organization") &&
      ($form->getVar('_contactSubType') == "Members_Businesses_")
    ) {
    CRM_Core_Resources::singleton()->addScript(
      "CRM.$(function($) {
        $('#legal_name').parent().hide();
        $('#nick_name').parent().hide();
        $('#sic_code').parent().hide();
        let index = $('#Email-Bulkmail-html').index();
        $('#Email-Bulkmail-html').closest('tr').prev().children().eq(index).hide();
        $('#Email-Bulkmail-html').hide();
        const select2 = [
          '#s2id_phone_1_location_type_id',
          '#s2id_im_1_location_type_id',
          '#s2id_website_1_website_type_id'
        ];
        $.each(select2, function(index, value) {
          let location = $(value).index();
          $(value).closest('tr').next().find('a').click(function(){
            $(this).parent().prev('tr').children().eq(location+1).hide();
          });
          // Label
          $(value).closest('tr').prev().children().eq(location+1).hide();
          // Field
          $(value).parent().hide();
	});
        // Change image label
	$('label[for=\"image_URL\"]').text('Organization Logo');
	// Hide repeater fields
        const select2parts = [
            's2id_phone_', '_location_type_id',
            's2id_im_', '_location_type_id',
            's2id_website_', '_website_type_id'
        ];
	$(document).on('ajaxComplete', function(event, xhr, settings) {
	    $('[id=\"Email-Bulkmail-html\"]').hide();
            for (let i = 0; i<select2parts.length; i += 2) {
		// The field can have any number in the middle
                $(`[id*='\${select2parts[i]}'][id*='\${select2parts[i+1]}']`).parent().hide();
	    }
        });
      });"
    );
  }
}
