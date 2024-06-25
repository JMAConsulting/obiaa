<?php
define("PAYMENTPAGE", 4);
define("LOCALDOLLARSPAGE", 5);
define('CATEGORYCUSTOMGROUP', 4);
require_once 'obiaacustomizations.civix.php';
// phpcs:disable
use CRM_Obiaacustomizations_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function obiaacustomizations_civicrm_config(&$config) {
  _obiaacustomizations_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function obiaacustomizations_civicrm_install() {
  _obiaacustomizations_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function obiaacustomizations_civicrm_enable() {
  _obiaacustomizations_civix_civicrm_enable();
}

function obiaacustomizations_civicrm_permission(&$permissions) {
  $prefix = ts('CiviContribute') . ": ";
  $permissions['manage payment pages'] = array(
    'label' => $prefix . ts('manage Payment pages'),
    'description' => ts('manage Payment page configuration'),
  );
  $permissions['administer payments'] = array(
    'label' => $prefix . ts('administer payments'),
    'description' => ts('administer payment configuration'),
  );
}

function obiaacustomizations_civicrm_links(string $op, string $objectName, $objectID, array &$links, ?int &$mask, array &$values) {
  if ('CustomField' == $objectName && (is_user_logged_in() && !in_array('administrator', wp_get_current_user()->roles))) {
    $links = [
     CRM_Core_Action::PREVIEW => [
          'name' => ts('Preview Field Display'),
          'url' => 'civicrm/admin/custom/group/preview',
          'qs' => 'action=preview&reset=1&fid=%%id%%',
          'title' => ts('Preview Custom Field'),
      ]
    ];
  }
  if ('CustomGroup' == $objectName && (is_user_logged_in() && !in_array('administrator', wp_get_current_user()->roles))) {
    $links = [
      CRM_Core_Action::PREVIEW => [
          'name' => ts('Preview'),
          'url' => 'civicrm/admin/custom/group/preview',
          'qs' => 'reset=1&gid=%%id%%',
          'title' => ts('Preview Custom Data Set'),
      ],
        CRM_Core_Action::BROWSE => [
          'name' => ts('View Custom Fields'),
          'url' => 'civicrm/admin/custom/group/field',
          'qs' => 'reset=1&action=browse&gid=%%id%%',
          'title' => ts('View and Edit Custom Fields'),
        ],
    ];
  }
}

function obiaacustomizations_civicrm_pageRun(&$page) {
  if (in_array(get_class($page), ['CRM_Custom_Page_Group','CRM_Custom_Page_Field']) && (is_user_logged_in() && !in_array('administrator', wp_get_current_user()->roles))) {
    Civi::resources()->addScript("
      CRM.$(function($) {
        $('#newCustomDataGroup, #newCustomField').hide();
      });
    ", -100, 'html-header');
  }
  if (get_class($page) == "CRM_Contribute_Page_DashBoard" && !CRM_Core_Permission::check('manage payment pages')) {
    CRM_Core_Resources::singleton()->addScript(
      "CRM.$(function($) {
        $('a.button:contains(\"Manage Payment Pages\")').parent().hide();;
      });
    ");
  }
  if (get_class($page) == "CRM_Contribute_Page_ContributionPage" && !CRM_Core_Permission::check('manage payment pages')) {
    CRM_Core_Error::statusBounce(ts('You do not have permission to access this page.'));
  }
  if ($page->getVar('_name') == 'CRM_Contact_Page_View_UserDashBoard') {
    CRM_Utils_System::redirect('/wp-admin/admin.php?page=CiviCRM&q=civicrm/contact/view&reset=1&cid=' . $page->_contactId);
  }
  /* Remove Add New Payment Button on Payment Page for non Admin Users */
  if($page->getVar('_name') == 'CRM_Contribute_Page_DashBoard') {
    if(is_user_logged_in()) {
      if(!current_user_can('administrator')) {
        Civi::resources()->addScriptFile('obiaacustomizations', 'js/contributionDashboard.js');
      }
    }
  }

  if($page->getVar('_name') == 'CRM_Contribute_Page_Tab') {
    if(is_user_logged_in()) {
      if(!current_user_can('administrator')) {
        Civi::resources()->addStyleFile('obiaacustomizations', 'js/creditCardPaymentPage.css');
      }
    }
  }
/*
      if (CRM_Core_Permission::check('administer CiviCRM')) {
        Civi::resources()
          ->addScriptFile('org.civicrm.tutorial', 'js/tutorial-admin.js', -101, 'html-header')
          ->addPermissions(['aadminister CiviCRM'])
          ->addVars('tutorial', [
            'basePath' => Civi::resources()->getUrl('org.civicrm.tutorial'),
            'urlPath' => implode('/', $page->urlPath),
          ]);
       }
*/
Civi::resources()->addScript("
CRM.$(function($) {
if (CRM.checkPerm('administer payments')) {
CRM.menubar.removeItem('tutorial_add');
}
});
");
}

function obiaacustomizations_civicrm_buildForm($formName, &$form) {
Civi::resources()->addScript("
CRM.$(function($) {
if (CRM.checkPerm('administer payments')) {
CRM.menubar.removeItem('tutorial_add');
}
});
");
/*
      if (CRM_Core_Permission::check('administer CiviCRM')) {
        Civi::resources()
          ->addScriptFile('org.civicrm.tutorial', 'js/tutorial-admin.js', -101, 'html-header')
          ->addPermissions(['aadminister CiviCRM'])
          ->addVars('tutorial', [
            'basePath' => Civi::resources()->getUrl('org.civicrm.tutorial'),
            'urlPath' => implode('/', $form->urlPath),
          ]);
       }
 */
  if ('CRM_Contact_Form_Contact' == $formName) {
//CRM_Core_Error::debug('$this->_editOptions', $form);exit;
    Civi::resources()->addScript("
    CRM.$(function($) {
      $('#contact_sub_type').parent().hide();
    });
    ", -100, 'html-header');
  }
  if ($formName == "CRM_Contribute_Form_Contribution" && (in_array($form->_action, [CRM_Core_Action::ADD, CRM_Core_Action::UPDATE]))) {
    $financialType = array_search('General Payment', CRM_Contribute_BAO_Contribution::buildOptions('financial_type_id', 'get'));
    $form->setDefaults(['financial_type_id' => $financialType]);
    CRM_Core_Resources::singleton()->addScript(
      "CRM.$(function($) {
        $('.crm-contribution-form-block-financial_type_id, #s2id_currency, #totalAmountORPriceSet, #selectPriceSet, #totalAmountBlock .description, #softCredit, .crm-Premium-accordion, #recurringPaymentBlock').hide();
        $(document).ajaxComplete(function(){
          $('.crm-contribution-form-block-non_deductible_amount, .crm-contribution-form-block-fee_amount, .crm-contribution-form-block-creditnote_id, #recurringPaymentBlock').hide();
        });
        $('#total_amount').css('margin-left', '-3px');
      });
    ");
  }

  // Custom Js Payment page
  if($formName == 'CRM_Contribute_Form_Contribution_Main' && $form->_id == PAYMENTPAGE ) {
    Civi::resources()->addScriptFile('obiaacustomizations', 'js/payment.js');
    Civi::resources()->addStyleFile('obiaacustomizations', 'css/forms.css');
  }
  // Custom Js Local Dollars  page
  if($formName == 'CRM_Contribute_Form_Contribution_Main' && $form->_id == LOCALDOLLARSPAGE ) {
    Civi::resources()->addScriptFile('obiaacustomizations', 'js/localdollars.js');
    Civi::resources()->addStyleFile('obiaacustomizations', 'css/forms.css');
  }
  // Custom JS for Custom Group.
  if ($formName === 'CRM_Contact_Form_Inline_CustomData' && $form->_groupID == CATEGORYCUSTOMGROUP) {
//    Civi::resources()->addScriptFile('obiaacustomizations', 'js/inlineContactCustomData.js');
  }
  if ($formName == 'CRM_Csvimport_Import_Form_DataSource') {
   $form->add('select', 'entity', ts('Entity To Import'), [
	'' => ts('- select -'),
	'PropertyOwnerImport' => 'PropertyOwnerImport',
	'BusinessImport' => 'BusinessImport',
    ]);
    CRM_Core_Resources::singleton()->addScript(
      "CRM.$(function($) {
        $('.crm-import-datasource-form-block-allowEntityUpdate, .crm-import-datasource-form-block-ignoreCase').hide();
      });
    ");
  }

}

function obiaacustomizations_civicrm_alterMailContent(&$content) {
  if ($content['valueName'] == 'contribution_offline_receipt') {
    $customGroupHtml = "{if !empty(\$customGroup)}
      {foreach from=\$customGroup item=value key=customName}
       <tr>
        <th {\$headerStyle}>
         {\$customName}
        </th>
       </tr>
       {foreach from=\$value item=v key=n}
        <tr>
         <td {\$labelStyle}>
          {\$n}
         </td>
         <td {\$valueStyle}>
          {\$v}
         </td>
        </tr>
       {/foreach}
      {/foreach}
     {/if}";
    $newCustomGroupHtml = "{if !empty(\$customGroup)}
      {foreach from=\$customGroup item=value key=customName}
      {if \$customName neq 'Payment Details'}
       <tr>
        <th {\$headerStyle}>
         {\$customName}
        </th>
       </tr>
       {foreach from=\$value item=v key=n}
        <tr>
         <td {\$labelStyle}>
          {\$n}
         </td>
         <td {\$valueStyle}>
          {\$v}
         </td>
        </tr>
       {/foreach}
      {/if}
      {/foreach}
     {/if}";
    foreach (['subject', 'html', 'text'] as $key) {
      $content[$key] = str_replace('{contact.display_name}', '{assign var="receiptalternate" value="{contribution.custom_56}"}{if $receiptalternate}{contribution.custom_56}{else}{contact.display_name}{/if}', $content[$key]);
      $content[$key] = str_replace('{$greeting}', '{assign var="receiptalternate" value="{contribution.custom_56}"}{if $receiptalternate}Dear {contribution.custom_56}{else}{$greeting}{/if}', $content[$key]);
      $content[$key] = str_replace($customGroupHtml, $newCustomGroupHtml, $content[$key]);
    }
  }
  if ($content['valueName'] == 'contribution_online_receipt') {
    $customPre = "{if !empty(\$customPre)}
      <tr>
       <th {\$headerStyle}>
        {\$customPre_grouptitle}
       </th>
      </tr>
      {foreach from=\$customPre item=customValue key=customName}
       {if (!empty(\$trackingFields) and ! in_array(\$customName, \$trackingFields)) or empty(\$trackingFields)}
        <tr>
         <td {\$labelStyle}>
          {\$customName}
         </td>
         <td {\$valueStyle}>
          {\$customValue}
         </td>
        </tr>
       {/if}
      {/foreach}
     {/if}";
    $customPreHtml = "{if !empty(\$customPre)}
      <tr>
       <th {\$headerStyle}>
        {\$customPre_grouptitle}
       </th>
      </tr>
      {foreach from=\$customPre item=customValue key=customName}
       {if ((!empty(\$trackingFields) and ! in_array(\$customName, \$trackingFields)) or empty(\$trackingFields)) and \$customName neq ' Receipt Made Out To ' and \$customName neq ' First Name ' and \$customName neq ' Last Name '}
        <tr>
         <td {\$labelStyle}>
          {\$customName}
         </td>
         <td {\$valueStyle}>
          {\$customValue}
         </td>
        </tr>
       {/if}
      {/foreach}
     {/if}";

   $customPost = "{if (!empty(\$trackingFields) and ! in_array(\$customName, \$trackingFields)) or empty(\$trackingFields)}";
   $customPostHtml = "{if ((!empty(\$trackingFields) and ! in_array(\$customName, \$trackingFields)) or empty(\$trackingFields)) and \$customName neq 'Receipt Made Out To' and \$customName neq 'First Name' and \$customName neq 'Last Name'}";
   foreach (['subject', 'html', 'text'] as $key) {
      $content[$key] = str_replace('{contact.display_name}', '{assign var="receiptalternate" value="{contribution.custom_56}"}{if $receiptalternate}{contribution.custom_56}{else}{contact.display_name}{/if}', $content[$key]);
      $content[$key] = str_replace('{$greeting}', '{assign var="receiptalternate" value="{contribution.custom_56}"}{if $receiptalternate}Dear {contribution.custom_56}{else}{$greeting}{/if}', $content[$key]);
      $content[$key] = str_replace('{$billingName}', '{assign var="receiptalternate" value="{contribution.custom_56}"}{if $receiptalternate}{$receiptalternate}{else}{$billingName}{/if}', $content[$key]);
      //$content[$key] = str_replace($customPre, $customPreHtml, $content[$key]);
      $content[$key] = str_replace($customPost, $customPostHtml, $content[$key]);
    }
  }
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function obiaacustomizations_civicrm_preProcess($formName, &$form) {
//
//}


function obiaacustomizations_civicrm_navigationMenu(&$menu) {
  $civiMobile = [
    'name' => E::ts('CiviMobile'),
    'permission' => 'administer payments',
    'operator' => NULL,
    'separator' => NULL,
  ];

  _obiaacustomizations_civix_insert_navigation_menu($params, 'Mailings', [
    'label' => E::ts('New Mailing (Traditional)'),
    'name' => 'traditional_mailing',
    'permission' => 'administer payments',
    'child' => [],
    'operator' => 'OR',
    'separator' => 0,
    'url' => CRM_Utils_System::url('civicrm/a/', NULL, TRUE, '/mailing/new/traditional'),
  ]);
  _obiaacustomizations_civix_insert_navigation_menu($menu, 'Administer/', $civiMobile);

 if (CRM_Core_Session::getLoggedInContactID()) {
    $cid = CRM_Core_Session::getLoggedInContactID();
    $contactSubType = \Civi\Api4\Contact::get(FALSE)
      ->addSelect('contact_sub_type:label')
      ->addWhere('id', '=', $cid)
      ->execute()->first()['contact_sub_type:label'][0];
    if ($contactSubType == 'BIA Staff') {
      foreach ($menu as &$item) {
        if (CRM_Utils_Array::value('name', $item['attributes']) === 'Home') {
          unset($item['child'][1]);
          return;
        }
      }
    }
  }
}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
//function obiaacustomizations_civicrm_navigationMenu(&$menu) {
//  _obiaacustomizations_civix_insert_navigation_menu($menu, 'Mailings', array(
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ));
//  _obiaacustomizations_civix_navigationMenu($menu);
//}
