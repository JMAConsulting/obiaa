<div class="help">
  {ts domain=com.agiliway.civimobileapi}The list of speakers is the participants of the event who are involved to one of the sessions. By editing the speakers you can fill in the detailed information (bio, photo, ...) about the person who will lead the session.{/ts}
</div>
<div class="crm-form-block">
  <table id="event-speakers-table" class="crm-ajax-table">
    <thead>
    <tr>
      <th data-data="display_name">{ts domain=com.agiliway.civimobileapi}Display Name{/ts}</th>
      <th data-data="job_title">{ts domain=com.agiliway.civimobileapi}Position{/ts}</th>
      <th data-data="organization_name">{ts domain=com.agiliway.civimobileapi}Company{/ts}</th>
      <th data-data="links" data-orderable="false"></th>
    </tr>
    </thead>
  </table>
</div>

{literal}
<script>
  CRM.$(function ($) {
    var speakersTable = $('#event-speakers-table');

    speakersTable.data({
      "ajax": {
        "url": {/literal}'{crmURL p="civicrm/civimobile/ajax/event-speakers" h=0}'{literal},
        "data": function (d) {
          d.event_id = {/literal}{$event_id}{literal}
        }
      }
    });
  });
</script>
{/literal}
