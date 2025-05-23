<div id="IsAllowMobileRegistration" style="display: none;">
  <fieldset id="IsAllowMobileRegistrationField" class="crm-collapsible">
    <legend class="collapsible-title">{ts domain="com.agiliway.civimobileapi"}Mobile registration{/ts}</legend>
    <table class="form-layout-compressed">
      <tr class="crm-event-manage-eventinfo-form-block-is_active">
        <td>&nbsp;</td>
        <td>{$form.civi_mobile_is_event_mobile_registration.html} {$form.civi_mobile_is_event_mobile_registration.label}</td>
      </tr>
      <tr class="crm-event-manage-eventinfo-form-block-is_active">
        <td>&nbsp;</td>
        <td>
          <div id="itemsToShowMessage" class="status">
            {ts domain="com.agiliway.civimobileapi"}
              Before enabling this option verify, that profiles you are using
              do not have the "... user account registration option?" set to "Account creation required",
              as this will make public event mobile registration impossible.
            {/ts}
          </div>
        </td>
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
