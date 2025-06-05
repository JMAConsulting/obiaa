<?php

class CRM_CiviMobileAPI_Hook_AlterAngular_EditEmail {

  public static function run($angular) {
    $angular->add(\Civi\Angular\ChangeSet::create('civimobile-new-email-autogenerate')
      ->alterHtml('~/crmMailing/EditMailingCtrl/2step.html', function(phpQueryObject $doc) {

        $hasChatGptAccess = CRM_Core_Permission::check('CiviMobile ChatGPT access');
        $additionalInfo = '<a class="helpicon" onclick="handleAutogenerateHelp()"></a>';

        if ($hasChatGptAccess) {
          $buttonHtml = CRM_CiviMobileAPI_Hook_Utils::getTextGenerationButton('mailing-html');
          $buttonPlainText = CRM_CiviMobileAPI_Hook_Utils::getTextGenerationButton('mailing-plain-text');

          $elements = $doc->find('div[crm-ui-accordion]');
          $elements->eq(0)->prepend($buttonHtml . $additionalInfo);
          $elements->eq(1)->prepend($buttonPlainText . $additionalInfo);
        }
      })
    );
  }
}
