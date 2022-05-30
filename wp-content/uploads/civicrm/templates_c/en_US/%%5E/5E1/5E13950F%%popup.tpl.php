<?php /* Smarty version 2.6.31, created on 2022-05-30 04:23:45
         compiled from /var/www/obiaa.jmaconsulting.biz/htdocs/wp-content/uploads/civicrm/ext/com.agiliway.civimobileapi/templates/CRM/CiviMobileAPI/popup.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'crmScope', '/var/www/obiaa.jmaconsulting.biz/htdocs/wp-content/uploads/civicrm/ext/com.agiliway.civimobileapi/templates/CRM/CiviMobileAPI/popup.tpl', 1, false),)), $this); ?>
<?php $this->_tag_stack[] = array('crmScope', array('extensionKey' => "")); $_block_repeat=true;smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><div id="civimobile-popup" class="civi-mobile-popup-wrap">
  <div class="civi-mobile-popup-close"></div>

  <div class="civi-mobile-popup-block">
    <div class="civi-mobile-popup-body">
      <div class="civi-mobile-top">
        <img src="<?php echo $this->_tpl_vars['civimobile_logo']; ?>
" alt="CiviMobile logo" class="civi-mobile-popup-logo">
        <button class="civi-mobile-popup-more">MORE</button>
      </div>
      <div class="civi-mobile-popup-qr">
        <p>Scan QR code for login into app</p>
        <img src="<?php echo $this->_tpl_vars['qr_code_link']; ?>
" alt="qr-code">
      </div>
    </div>
  </div>

  <div class="civi-mobile-popup-block-advanced">
    <div class="civi-mobile-popup-body-advanced">
      <p><?php echo $this->_tpl_vars['description_text']; ?>
</p>
      <div class="civi-mobile-popup-bottom">
        <div class="civi-mobile-popup-more-left-block">
          <a target="_blank" href="<?php echo $this->_tpl_vars['apple_link']; ?>
"><img src="<?php echo $this->_tpl_vars['app_store_img']; ?>
" alt="app-store"></a>
          <a target="_blank" href="<?php echo $this->_tpl_vars['google_link']; ?>
"><img src="<?php echo $this->_tpl_vars['google_play_img']; ?>
" alt="google-play"></a>
          <div class="civi-mobile-popup-qr">
            <p>Scan QR code for login into app</p>
            <img src="<?php echo $this->_tpl_vars['qr_code_link']; ?>
" alt="qr-code">
          </div>
        </div>
        <div class="civi-mobile-popup-more-right-block">
          <img src="<?php echo $this->_tpl_vars['civimobile_phone_img']; ?>
" alt="civimobile-phone">
        </div>
      </div>
    </div>
  </div>

</div>

<?php echo '
<style>
  @font-face {
    font-family: \'Roboto\';
    src: url(\''; ?>
<?php echo $this->_tpl_vars['font_directory']; ?>
<?php echo '/Roboto-Regular.ttf\') format(\'truetype\');
    font-weight: normal;
    font-style: normal;
  }

  @font-face {
    font-family: \'Roboto\';
    src: url(\''; ?>
<?php echo $this->_tpl_vars['font_directory']; ?>
<?php echo '/Roboto-Bold.ttf\') format(\'truetype\');
    font-weight: bold;
    font-style: normal;
  }

  #civimobile-popup.civi-mobile-popup-wrap {
    font-family: \'Roboto\', sans-serif;
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
    content: \' \';
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
    background: '; ?>
<?php echo $this->_tpl_vars['small_popup_background_color']; ?>
<?php echo ';
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
    background-color: '; ?>
<?php echo $this->_tpl_vars['button_background_color']; ?>
<?php echo ';
    margin: 14px 0;
    font-size: 12px;
    color: '; ?>
<?php echo $this->_tpl_vars['button_text_color']; ?>
<?php echo ';
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
    background: '; ?>
<?php echo $this->_tpl_vars['advanced_popup_background_color']; ?>
<?php echo ';
  }

  #civimobile-popup .civi-mobile-popup-body-advanced > p {
    color: '; ?>
<?php echo $this->_tpl_vars['description_text_color']; ?>
<?php echo ';
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
  var isShowedPopup = '; ?>
<?php echo $this->_tpl_vars['is_showed_popup']; ?>
<?php echo ';

  function setCookie(cname, cvalue, exdays) {
    var date = new Date();
    date.setTime(date.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + date.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }

  CRM.$(document).ready(function($) {
    if (isShowedPopup) {
      $(\'.civi-mobile-popup-wrap\').show();
    }

    $(\'.civi-mobile-popup-close\').click(function() {
      setCookie("civimobile_popup_close", true, 30);
      $(\'.civi-mobile-popup-wrap\').hide();
    });
    $(\'.civi-mobile-popup-more\').click(function() {
      $(\'.civi-mobile-popup-block\').hide();
      $(\'.civi-mobile-popup-block-advanced\').show();
    });
  });
</script>
'; ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo smarty_block_crmScope($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>