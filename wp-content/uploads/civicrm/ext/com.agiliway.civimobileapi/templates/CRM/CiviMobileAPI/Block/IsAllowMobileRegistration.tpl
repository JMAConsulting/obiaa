<div id="IsAllowMobileRegistration" style="display: none;">
  <fieldset id="IsAllowMobileRegistrationField" class="crm-collapsible">
    <legend class="collapsible-title">{ts}Mobile registration{/ts}</legend>
    <table class="form-layout-compressed">
      <tr class="crm-event-manage-eventinfo-form-block-is_active">
        <td>&nbsp;</td>
        <td>{$form.civi_mobile_is_event_mobile_registration.html} {$form.civi_mobile_is_event_mobile_registration.label}</td>
      </tr>
    </table>
  </fieldset>
</div>

{literal}
  <script type="text/javascript">
    (function () {
      CRM.$(document).ready(function () {
        if (CRM.$("#registration_blocks").length) {
          var civimobileBlock = CRM.$('#IsAllowMobileRegistration');
          CRM.$(civimobileBlock).appendTo('#registration_blocks');
          civimobileBlock.show();
        }
      });
    })();
  </script>
{/literal}
