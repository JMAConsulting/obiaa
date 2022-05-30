<div class="crm-block crm-form-block">
  {if $form.buttons}
  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="top"}
  </div>
  {/if}
  {if $isLoggedInContactForm}
  <table class="form-layout-compressed">
    <tbody>
    <tr class="crm-group-form-block-isReserved">
      <td class="label">{$form.civimobile_show_qr_popup.label}</td>
      <td>
        <div>
          {$form.civimobile_show_qr_popup.html}
        </div>
      </td>
    </tr>
    </tbody>
  </table>
  {/if}
</div>

{literal}
<script type="text/javascript">

  function setCookie(cname, cvalue, exdays) {
    var date = new Date();
    date.setTime(date.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + date.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }

  CRM.$(document).ready(function($) {

    $('.civi-mobile-popup-close').click(function() {
      $("input[name='civimobile_show_qr_popup']").attr("checked", false);
    });

    $("input[name='civimobile_show_qr_popup']").change(function () {
      if ($("input[name='civimobile_show_qr_popup']:checked").length == 1) {
        setCookie("civimobile_popup_close", 0, 30);
        $('.civi-mobile-popup-wrap').show();
      } else {
        setCookie("civimobile_popup_close", 1, 30);
        $('.civi-mobile-popup-wrap').hide();
      }
    });
  });
</script>
{/literal}
