<div class="crm-container">
  <div class="crm-block crm-form-block crm-civimobile-session-form-block">
    <div class="crm-submit-buttons">
      {if $action eq 4}
        {if $can_edit_session}
          <a href="{crmURL p='civicrm/civimobile/event/session' q='reset=1&action=update&id='}{$eventSession.id}"
             class="edit button" title="{ts domain=com.agiliway.civimobileapi}Edit{/ts}"><span><i class="crm-i fa-pencil"></i> {ts domain=com.agiliway.civimobileapi}Edit{/ts}</span></a>
        {/if}
        {if $can_delete_session}
          <a href="{crmURL p='civicrm/civimobile/event/session' q='reset=1&action=delete&id='}{$eventSession.id}"
             class="delete button" title="{ts domain=com.agiliway.civimobileapi}Delete{/ts}"><span><i class="crm-i fa-trash"></i> {ts domain=com.agiliway.civimobileapi}Delete{/ts}</span></a>
        {/if}
      {/if}
      {include file="CRM/common/formButtons.tpl" location="top"  multiple="multiple"}
    </div>
    {if $action eq 4}
      <table class="crm-info-panel">
        <tr>
          <td class="label">Title</td>
          <td>
            {$eventSession.title}
          </td>
        </tr>
        <tr>
          <td class="label">Date</td>
          <td>
            {$eventSession.date_formatted}
          </td>
        </tr>
        <tr>
          <td class="label">Start Time</td>
          <td>
            {$eventSession.start_time_formatted}
          </td>
        </tr>
        <tr>
          <td class="label">End Time</td>
          <td>
            {$eventSession.end_time_formatted}
          </td>
        </tr>
        <tr>
          <td class="label">Speakers</td>
          <td>{$eventSession.speakers_with_links}</td>
        </tr>
        <tr>
          <td class="label">Venue</td>
          <td>
            {$eventSession.venue_link}
          </td>
        </tr>
        <tr>
          <td class="label">Description</td>
          <td>
            {$eventSession.description}
          </td>
        </tr>
       {if $eventSession.participant_with_links}
        <tr>
          <td class="label">Participant</td>
          <td>
              {$eventSession.participant_with_links}
          </td>
        </tr>
       {/if}
      </table>
    {else}
      {if $action eq 8}
        <p class="status">Are you really want to delete the Session?</p>
      {else}
        <table class="form-layout">
          <tr>
            <td class="label">{$form.title.label}</td>
            <td class="view-value">{$form.title.html}</td>
          </tr>
          <tr>
            <td class="label">{$form.date.label}</td>
            <td class="view-value">{$form.date.html}</td>
          </tr>
          <tr>
            <td class="label">{$form.start_time.label}</td>
            <td class="view-value">{$form.start_time.html}</td>
          </tr>
          <tr>
            <td class="label">{$form.end_time.label}</td>
            <td class="view-value">{$form.end_time.html}</td>
          </tr>
          <tr>
            <td class="label">{$form.speakers.label} {help id="speakers-help"}</td>
            <td class="view-value">
              {$form.speakers.html}
              <a href="{crmURL p='civicrm/participant/add' q='reset=1&action=add&context=standalone&eid='}{$event_id}"
                 class="crm-option-edit-link medium-popup crm-hover-button" target="_blank"
                 title="{ts domain=com.agiliway.civimobileapi}New Speaker{/ts}"><i class="crm-i fa-plus-circle"></i></a>
              <br/>
              <span class="description">{ts domain=com.agiliway.civimobileapi}If you can’t find the participant you want in the list, you can always create a new one by tapping "+".{/ts}</span>
            </td>
          </tr>
          <tr>
            <td class="label">{$form.venue_id.label} {if $venueNotice}
                <span class="crm-marker" title="This field is required.">*</span>
              {/if} {help id="venue-help"}</td>
            <td class="view-value">{$form.venue_id.html}
              {if $venueNotice}
                <span class="crm-error">
                  {$venueNotice}
                </span>
              {/if}
              {if !$venueNotice}
                <a href="{crmURL p='civicrm/civimobile/manage-venues?reset=1&location_id='}{$location}"
                   class="crm-option-edit-link medium-popup crm-hover-button" target="_blank"
                   title="{ts domain=com.agiliway.civimobileapi}Manage venues{/ts}"><i class="crm-i fa-wrench"></i></a>
              {/if}
            </td>
          </tr>
          <tr>
            <td class="label">{$form.description.label}</td>
            <td class="view-value">{$form.description.html}</td>
          </tr>
        </table>
      {/if}
    {/if}
    <div class="crm-submit-buttons">
      {if $action eq 4}
        {if $can_edit_session}
          <a href="{crmURL p='civicrm/civimobile/event/session' q='reset=1&action=update&id='}{$eventSession.id}"
             class="edit button" title="{ts domain=com.agiliway.civimobileapi}Edit{/ts}"><span><i class="crm-i fa-pencil"></i> {ts domain=com.agiliway.civimobileapi}Edit{/ts}</span></a>
        {/if}
        {if $can_delete_session}
        <a href="{crmURL p='civicrm/civimobile/event/session' q='reset=1&action=delete&id='}{$eventSession.id}"
           class="delete button" title="{ts domain=com.agiliway.civimobileapi}Delete{/ts}"><span><i class="crm-i fa-trash"></i> {ts domain=com.agiliway.civimobileapi}Delete{/ts}</span></a>
        {/if}
      {/if}
      {include file="CRM/common/formButtons.tpl" location="bottom"  multiple="multiple"}
    </div>
  </div>
</div>

{literal}
  <script>
    function getCookie(cname) {
      var name = cname + "=";
      var decodedCookie = decodeURIComponent(document.cookie);
      var ca = decodedCookie.split(';');
      for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
          c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
          return c.substring(name.length, c.length);
        }
      }
      return "";
    }

    function setCookie(cname, cvalue, exdays) {
      var d = new Date();
      d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
      var expires = "expires="+d.toUTCString();
      document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    setInterval(function() {
      let speaker_id = getCookie('civimobile_speaker_id');
      if (speaker_id) {
        CRM.$('#speakers').val(CRM.$('#speakers').val() + (CRM.$('#speakers').val().length ? ',' : '') + speaker_id).trigger('change');
        setCookie('civimobile_speaker_id', null, -1);
      }
    }, 500);
  </script>
  <style>
    input.crm-form-text.required.crm-form-time.hasTimeEntry {
      margin-left: 0;
    }

    .ui-dialog-content.ui-widget-content.modal-dialog {
      height: auto !important;
      max-height: 80vh !important;
    }

    .crm-container.crm-public .select2-container .select2-choice {
      padding: 0 0 0 8px;
      font-size: 11px;
    }

    .crm-container.crm-public .select2-container-multi .select2-choices {
      padding: 0 5px 0 0;
    }

    .crm-container.crm-public .select2-container-multi .select2-choices .select2-search-choice {
      padding: 2px 5px 2px 18px;
      font-size: 11px;
    }
  </style>
{/literal}
