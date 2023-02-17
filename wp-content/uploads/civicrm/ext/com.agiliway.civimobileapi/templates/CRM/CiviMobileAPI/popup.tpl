<div class="qr_open_popup">
  <button class="open_btn" id="open_btn">{ts domain=com.agiliway.civimobileapi}Open Qr Code{/ts}</button>
</div>

<div id="civimobile-popup" class="civi-mobile-popup-wrap">
  <div class="civi-mobile-popup-close"></div>

  <div class="civi-mobile-popup-block">
    <div class="civi-mobile-popup-body">
      <div class="civi-mobile-top">
        <img src="{$civimobile_logo}" alt="CiviMobile logo" class="civi-mobile-popup-logo">
        <button class="civi-mobile-popup-more">{ts domain=com.agiliway.civimobileapi}MORE{/ts}</button>
      </div>
      <div class="civi-mobile-popup-qr">
        <p>{ts domain=com.agiliway.civimobileapi}Scan QR code for login into app{/ts}</p>
        <img src="{$qr_code_link}" alt="qr-code">
      </div>
    </div>
  </div>

  <div class="civi-mobile-popup-block-advanced">
    <div class="civi-mobile-popup-body-advanced">
      <p>{$description_text}</p>
      <div class="civi-mobile-popup-bottom">
        <div class="civi-mobile-popup-more-left-block">
          <a target="_blank" href="{$apple_link}"><img src="{$app_store_img}" alt="app-store"></a>
          <a target="_blank" href="{$google_link}"><img src="{$google_play_img}" alt="google-play"></a>
          <div class="civi-mobile-popup-qr">
            <p>{ts domain=com.agiliway.civimobileapi}Scan QR code for login into app{/ts}</p>
            <img src="{$qr_code_link}" alt="qr-code">
          </div>
        </div>
        <div class="civi-mobile-popup-more-right-block">
          <img src="{$civimobile_phone_img}" alt="civimobile-phone">
        </div>
      </div>
    </div>
  </div>

</div>

{literal}
<style>
  @font-face {
    font-family: 'Roboto';
    src: url('{/literal}{$font_directory}{literal}/Roboto-Regular.ttf') format('truetype');
    font-weight: normal;
    font-style: normal;
  }

  @font-face {
    font-family: 'Roboto';
    src: url('{/literal}{$font_directory}{literal}/Roboto-Bold.ttf') format('truetype');
    font-weight: bold;
    font-style: normal;
  }

  #civimobile-popup.civi-mobile-popup-wrap {
    font-family: 'Roboto', sans-serif;
    position: fixed;
    bottom: 0;
    right: 0;
    display: none;
    z-index: 1050;
    box-shadow: 0 8px 20px 0 rgba(0, 0, 0, 0.13);
  }

  #civimobile-popup .civi-mobile-popup-block {
    width: 126px;
  }

  .qr_open_popup {
    position: fixed;
    right: 25px;
    bottom: 25px;
  }

  .qr_open_popup button {
    background-color: #4CAF50; /* Green */
    border: none;
    color: white;
    padding: 10px 3px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 14px;
    margin: 4px 2px;
    cursor: pointer;
    border-radius: 5px;
    z-index: 9999;
  }

  #civimobile-popup .civi-mobile-popup-close {
    position: absolute;
    display: block;
    width: 22px;
    height: 22px;
    top: -27px;
    right: 0;
    background: rgba(160, 172, 183, 0.12);
    border-radius: 100%;
    cursor: pointer;
  }

  #civimobile-popup .civi-mobile-popup-close:before, #civimobile-popup .civi-mobile-popup-close:after {
    position: absolute;
    left: 11px;
    top: 5px;
    content: ' ';
    height: 12px;
    width: 1px;
    background-color: #a0acb7;
  }

  #civimobile-popup .civi-mobile-popup-close:before {
    transform: rotate(45deg);
  }

  #civimobile-popup .civi-mobile-popup-close:after {
    transform: rotate(-45deg);
  }

  #civimobile-popup .civi-mobile-popup-body {
    background: {/literal}{$small_popup_background_color}{literal};
    width: 100%;
    text-align: center;
  }

  #civimobile-popup .civi-mobile-top {
    padding: 8px;
  }

  #civimobile-popup .civi-mobile-popup-more {
    display: block;
    width: 100%;
    height: 24px;
    border-radius: 2px;
    background-color: {/literal}{$button_background_color}{literal};
    margin: 14px 0;
    font-size: 12px;
    color: {/literal}{$button_text_color}{literal};
    padding: 3px;
    border: none;
    cursor: pointer;
  }

  #civimobile-popup .civi-mobile-popup-logo {
    display: block;
    width: 100%;
    margin-top: 14px;
  }

  #civimobile-popup .civi-mobile-popup-qr {
    background: white;
    padding: 8px;
  }

  #civimobile-popup .civi-mobile-popup-qr p {
    color: black;
    font-size: 12px;
    margin: 0;
  }

  #civimobile-popup .civi-mobile-popup-qr img {
    width: 100px;
    padding: 5px;
  }

  #civimobile-popup .civi-mobile-popup-block-advanced {
    box-sizing: border-box;
    width: 271px;
    display: none;
  }

  #civimobile-popup .civi-mobile-popup-body-advanced {
    box-sizing: border-box;
    padding: 15px;
    padding-bottom: 0;
    width: 100%;
    background: {/literal}{$advanced_popup_background_color}{literal};
  }

  #civimobile-popup .civi-mobile-popup-body-advanced > p {
    color: {/literal}{$description_text_color}{literal};
  }

  #civimobile-popup .civi-mobile-popup-bottom {
    overflow: auto;
  }

  #civimobile-popup .civi-mobile-popup-more-left-block {
    width: 126px;
    float: left;
    text-align: center;
  }

  #civimobile-popup .civi-mobile-popup-more-right-block {
    width: 99px;
    float: right;
  }

  #civimobile-popup .civi-mobile-popup-more-right-block img {
    width: 100%;
  }

  #civimobile-popup .civi-mobile-popup-more-left-block a img {
    width: 100%;
    margin-bottom: 8px;
  }
</style>

<script type="text/javascript">
  var isShowedPopup = {/literal}{$is_showed_popup}{literal};

  function setCookie(cname, cvalue, exdays) {
    var date = new Date();
    date.setTime(date.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + date.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }

  CRM.$(document).ready(function($) {
    if (isShowedPopup) {
      $('.civi-mobile-popup-wrap').show();
    }

    /*
     Black list to hide open qr code because crm block code in down url
     */
    var currentLocation = window.location.href;
    if (currentLocation.match('civicrm/pledge/add')
      || currentLocation.match('civicrm/member/add')
      || currentLocation.match('civicrm/grant') || currentLocation.match('civimobile')
      || currentLocation.match('civicrm/contribute/transact')
      || currentLocation.match('civicrm/event/register')
    ) {
      $('.qr_open_popup').hide();
    }

    if ($('#civimobile-popup').css('display') === 'none') {
      $('.open_btn').show();
    } else {
      $('.open_btn').hide();
    }

    $('.open_btn').click(function() {
      $('.civi-mobile-popup-wrap').show();
      $('.open_btn').hide();
      setCookie("civimobile_popup_close", true, 0);
    });

    $('.civi-mobile-popup-close').click(function() {
      setCookie("civimobile_popup_close", true, 30);
      $('.civi-mobile-popup-wrap').hide();
      $('.open_btn').show();
    });
    $('.civi-mobile-popup-more').click(function() {
      $('.civi-mobile-popup-block').hide();
      $('.civi-mobile-popup-block-advanced').show();
    });
  });
</script>
{/literal}
