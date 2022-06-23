<?php

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
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function obiaacustomizations_civicrm_xmlMenu(&$files) {
  _obiaacustomizations_civix_civicrm_xmlMenu($files);
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
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function obiaacustomizations_civicrm_postInstall() {
  _obiaacustomizations_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function obiaacustomizations_civicrm_uninstall() {
  _obiaacustomizations_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function obiaacustomizations_civicrm_enable() {
  _obiaacustomizations_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function obiaacustomizations_civicrm_disable() {
  _obiaacustomizations_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function obiaacustomizations_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _obiaacustomizations_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function obiaacustomizations_civicrm_managed(&$entities) {
  _obiaacustomizations_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function obiaacustomizations_civicrm_caseTypes(&$caseTypes) {
  _obiaacustomizations_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function obiaacustomizations_civicrm_angularModules(&$angularModules) {
  _obiaacustomizations_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function obiaacustomizations_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _obiaacustomizations_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function obiaacustomizations_civicrm_entityTypes(&$entityTypes) {
  _obiaacustomizations_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function obiaacustomizations_civicrm_themes(&$themes) {
  _obiaacustomizations_civix_civicrm_themes($themes);
}

function obiaacustomizations_civicrm_permission(&$permissions) {
  $prefix = ts('CiviContribute') . ": ";
  $permissions['manage payment pages'] = array(
    $prefix . ts('manage Payment pages'),
    ts('manage Payment page configuration'),
  );
}

function obiaacustomizations_civicrm_pageRun(&$page) {
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
}

function obiaacustomizations_civicrm_buildForm($formName, &$form) {
  if ($formName == "CRM_Contribute_Form_Contribution" && (in_array($form->_action, [CRM_Core_Action::ADD, CRM_Core_Action::UPDATE]))) {
    $financialType = array_search('General Payment', CRM_Contribute_BAO_Contribution::buildOptions('financial_type_id', 'get'));
    $form->setDefaults(['financial_type_id' => $financialType]);
    CRM_Core_Resources::singleton()->addScript(
      "CRM.$(function($) {
        $('.crm-contribution-form-block-financial_type_id, #s2id_currency, #totalAmountORPriceSet, #selectPriceSet, #totalAmountBlock .description, #softCredit, .crm-Premium-accordion').hide();
        $(document).ajaxComplete(function(){
          $('.crm-contribution-form-block-non_deductible_amount, .crm-contribution-form-block-fee_amount, .crm-contribution-form-block-creditnote_id').hide();
        });
        $('#total_amount').css('margin-left', '-3px');
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
      $content[$key] = str_replace('{contact.display_name}', '{contribution.custom_56}', $content[$key]);
      $content[$key] = str_replace('{contact.email_greeting}', 'Dear {contribution.custom_56}', $content[$key]);
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
       {if ((!empty(\$trackingFields) and ! in_array(\$customName, \$trackingFields)) or empty(\$trackingFields)) and \$customName neq 'Receipt Made Out To'}
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

   $customPost = "{if !empty(\$customPost)}
      <tr>
       <th {\$headerStyle}>
        {\$customPost_grouptitle}
       </th>
      </tr>
      {foreach from=\$customPost item=customValue key=customName}
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
   $customPostHtml = "{if !empty(\$customPost)}
      <tr>
       <th {\$headerStyle}>
        {\$customPost_grouptitle}
       </th>
      </tr>
      {foreach from=\$customPost item=customValue key=customName}
       {if ((!empty(\$trackingFields) and ! in_array(\$customName, \$trackingFields)) or empty(\$trackingFields)) and \$customName neq 'Receipt Made Out To'}
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
   foreach (['subject', 'html', 'text'] as $key) {
      $content[$key] = str_replace('{contact.display_name}', '{contribution.custom_56}', $content[$key]);
      $content[$key] = str_replace('{contact.email_greeting}', 'Dear {contribution.custom_56}', $content[$key]);
      $content[$key] = str_replace($customPre, $customPreHtml, $content[$key]);
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
