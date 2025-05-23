<?php

class CRM_CiviMobileAPI_Hook_Utils {
  public static function is_mobile_request() {
    $null = NULL;

    return CRM_Utils_Request::retrieve('civimobile', 'Int', $null, FALSE, FALSE, 'GET');
  }

  /**
   * Adds hook civimobile_secret_validation, which you can use to add own secret
   * validation
   */
  public static function civimobileapi_secret_validation() {
    $nullObject = NULL;
    $validated = TRUE;
    CRM_Utils_Hook::singleton()
      ->commonInvoke(1, $validated, $nullObject, $nullObject, $nullObject, $nullObject, $nullObject, 'civimobile_secret_validation', '');
    if (!$validated) {
      http_response_code(404);
      exit;
    }
  }

  public static function getTextGenerationButton(string $generateType) {
    $isEmptyChatGptSettings = empty(Civi::settings()->get('civimobile_openai_secret_key') || Civi::settings()->get('civimobile_openai_model'));
    return '<button class="generate-text-button" data-generate-type="' . $generateType . '" type="button" ' . ($isEmptyChatGptSettings ? 'disabled' : '') . '><i class="crm-i fa-spinner"></i> Autogenerate</button><span> </span>';
  }

  public static function civimobile_add_generate_description_popup() {
    if (empty(CRM_Core_Region::instance('page-footer')->get('civimobile-generate-description-popup'))) {
      CRM_Core_Region::instance('page-footer')->add([
        'template' => "CRM/CiviMobileAPI/generate-description-popup.tpl",
        'name' => 'civimobile-generate-description-popup'
      ]);
    }
  }

  public static function civimobile_add_qr_popup() {
    if (empty($_GET['snippet'])) {
      if (Civi::settings()->get('civimobile_is_allow_public_website_url_qrcode') == 1 || CRM_Core_Permission::check('administer CiviCRM')) {

        $params = [
          'apple_link' => 'https://itunes.apple.com/us/app/civimobile/id1404824793?mt=8',
          'google_link' => 'https://play.google.com/store/apps/details?id=com.agiliway.civimobile',
          'civimobile_logo' => CRM_CiviMobileAPI_ExtensionUtil::url('/img/civimobile_logo.svg'),
          'app_store_img' => CRM_CiviMobileAPI_ExtensionUtil::url('/img/app-store.png'),
          'google_play_img' => CRM_CiviMobileAPI_ExtensionUtil::url('/img/google-play.png'),
          'civimobile_phone_img' => CRM_CiviMobileAPI_ExtensionUtil::url('/img/civimobile-phone.png'),
          'font_directory' => CRM_CiviMobileAPI_ExtensionUtil::url('/font'),
          'qr_code_link' => CRM_CiviMobileAPI_Install_Entity_ApplicationQrCode::getPath(),
          'small_popup_background_color' => '#e8ecf0',
          'advanced_popup_background_color' => '#e8ecf0',
          'button_background_color' => '#5589b7',
          'button_text_color' => 'white',
          'description_text' => 'Congratulations, your CiviCRM supports <b>CiviMobile</b> application now. You can download the mobile application at AppStore or Google PlayMarket.',
          'description_text_color' => '#3b3b3b',
          'is_showed_popup' => empty($_COOKIE["civimobile_popup_close"]),
        ];

        CRM_CiviMobileAPI_Utils_HookInvoker::qrCodeBlockParams($params);

        $params['is_showed_popup'] = $params['is_showed_popup'] ? 1 : 0;

        CRM_Core_Smarty::singleton()->assign($params);
        CRM_Core_Region::instance('page-body')->add([
          'template' => 'CRM/CiviMobileAPI/popup.tpl',
        ]);
      }
    }
  }
}
