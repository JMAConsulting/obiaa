<?php

class CRM_CiviMobileAPI_Hook_AlterContent_AutoGenerateAI {

  public static function run(&$content, $context, $tplName, &$object) {
    if ($context != "form")
      return;

    $hasChatGptAccess = CRM_Core_Permission::check('CiviMobile ChatGPT access');
    $additionalInfo = '<a class="helpicon" href="#" onclick="handleAutogenerateHelp()"></a>';

    if ($tplName == "CRM/Event/Form/ManageEvent/Location.tpl") {
      if (CRM_CiviMobileAPI_Utils_Agenda_AgendaConfig::isAgendaActiveForEvent($object->_id)) {
        $content = "<div class='status'>If you change the location for an event, all venues will be deleted from sessions.</div>" . $content;
      }
    }
    else if ($tplName == "CRM/Event/Form/ManageEvent/EventInfo.tpl") {
      if ($hasChatGptAccess) {
        $buttonHtml = CRM_CiviMobileAPI_Hook_Utils::getTextGenerationButton('event-description');

        $content = str_replace('<label for="description">Complete Description</label>', $buttonHtml . $additionalInfo . '<label for="summary">Event Description</label>', $content);
      }
      if (CRM_CiviMobileAPI_Utils_Agenda_AgendaConfig::isAgendaActiveForEvent($object->_id)) {
        $content = "<div class='status'>If you change the date, some event sessions may stop displaying.</div>" . $content;
      }
    }
    else if ($tplName == "CRM/Campaign/Form/Petition.tpl" && $hasChatGptAccess) {
      $buttonHtmlIntroduction = CRM_CiviMobileAPI_Hook_Utils::getTextGenerationButton('petition-introduction');
      $buttonHtmlThankYouMessage = CRM_CiviMobileAPI_Hook_Utils::getTextGenerationButton('thank-you-message');

      $content = str_replace('<label for="instructions">Introduction</label>', $buttonHtmlIntroduction . $additionalInfo . '<label for="summary">Petition Introduction</label>', $content);
      $content = str_replace('<label for="thankyou_text">Thank-you Message</label>', $buttonHtmlThankYouMessage . $additionalInfo . '<label for="summary">Petition Thank-you Message</label>', $content);
    }
    else if ($tplName == "CRM/Campaign/Form/Survey/Main.tpl"  && $hasChatGptAccess) {
      $buttonHtml = CRM_CiviMobileAPI_Hook_Utils::getTextGenerationButton('survey-instructions');

      $content = str_replace('<label for="instructions">Instructions for interviewers</label>', $buttonHtml . $additionalInfo . '<label for="summary">Survey Instructions for interviewers</label>', $content);
    }
    else if ($tplName == "CRM/Campaign/Form/Campaign.tpl"  && $hasChatGptAccess) {
      $buttonGoalsText = CRM_CiviMobileAPI_Hook_Utils::getTextGenerationButton('campaign-goals');
      $buttonHtmlDescription = CRM_CiviMobileAPI_Hook_Utils::getTextGenerationButton('campaign-description');

      $content = str_replace('<label for="goal_general">Campaign Goals</label>', $buttonGoalsText . $additionalInfo . '<label for="summary">Campaign Goals</label>', $content);
      $content = str_replace('<label for="description">Description</label>', $buttonHtmlDescription . $additionalInfo . '<label for="summary">Campaign Description</label>', $content);
    }
    else if ($tplName == "CRM/Admin/Form/MessageTemplates.tpl"  && $hasChatGptAccess) {
      $buttonHtml = CRM_CiviMobileAPI_Hook_Utils::getTextGenerationButton('message-template-html');
      $buttonPlainText = CRM_CiviMobileAPI_Hook_Utils::getTextGenerationButton('message-template-plain-text');
      $content = preg_replace('/<div class="crm-accordion-body">/', '<div class="crm-accordion-body" id="message-template-html">' . $buttonHtml . $additionalInfo, $content, 1);
      $content = preg_replace('/<div class="crm-accordion-body">/', '<div class="crm-accordion-body" id="message-template-plain-text">' . $buttonPlainText . $additionalInfo, $content, 1);
    }
    else if ($tplName == "CRM/Contact/Form/Task/Email.tpl"  && $hasChatGptAccess) {
      $buttonHtml = CRM_CiviMobileAPI_Hook_Utils::getTextGenerationButton('contact-mail-html');
      $buttonPlainText = CRM_CiviMobileAPI_Hook_Utils::getTextGenerationButton('contact-mail-plain-text');
      $content = preg_replace('/<div class="crm-accordion-body">/', '<div class="crm-accordion-body" id="contact-mail-html">' . $buttonHtml . $additionalInfo, $content, 1);
      $content = preg_replace('/<div class="crm-accordion-body">/', '<div class="crm-accordion-body" id="contact-mail-plain-text">' . $buttonPlainText . $additionalInfo, $content, 1);
    }
  }
}
