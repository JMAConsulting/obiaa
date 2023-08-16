{literal}
  <style>
    #event-sessions-table .error-session.odd {
      background: #ffb3ad;
    }

    #event-sessions-table .error-session.even {
      background: #ffc6c2;
    }

    #event-sessions-table tr td:nth-child(6) {
      color: green;
    }

    #event-sessions-table .error-session td:nth-child(6) {
      color: red;
    }

    .dataTables_wrapper {
      margin-top: 20px;
    }
  </style>
{/literal}
<div class="agenda-block crm-container">
  <div class="help">
    {ts domain=com.agiliway.civimobileapi}Agenda details the event schedule. It displays the schedule of a single- or multi-day upcoming event with detailed session information, like session start/end time, topic, speaker profiles and venue.{/ts}
    <a href="https://civimobile.org/docs/#agenda" target="_blank">{ts domain=com.agiliway.civimobileapi}Read more{/ts}</a>
  </div>
  {if $notice}
    <div class="status">
      {$notice}
    </div>
  {else}
    <div class="crm-form-block">
      {if $can_change_agenda_config}
        <table class="form-layout">
          <tbody>
          <tr>
            <td class="label"><label for="is_use_agenda">{ts domain=com.agiliway.civimobileapi}Show Event Agenda:{/ts}</label></td>
            <td>
              <input id="is_use_agenda" type="checkbox" {if $is_use_agenda}checked="checked"{/if}>
              <span class="description">{ts domain=com.agiliway.civimobileapi}Event Agenda enabled?{/ts}</span>
              <br/><span class="description">{ts domain=com.agiliway.civimobileapi}You can fill the Agenda and when you finish it, just turn it on.{/ts}</span>
            </td>
          </tr>
          </tbody>
        </table>
      {/if}
      <div class="civimobile-agenda-block">
        <div id="secondaryTabContainer" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
          {include file="CRM/common/TabSelected.tpl" defaultTab="contributions" tabContainer="#secondaryTabContainer"}

          <ul class="ui-tabs-nav ui-corner-all ui-helper-reset ui-helper-clearfix ui-widget-header">
            <li id="tab_event_sessions"
                class="crm-tab-button ui-corner-all ui-tabs-tab ui-corner-top ui-state-default ui-tab ui-tabs-active ui-state-active">
              <a href="#event-sessions-subtab" title="{ts domain=com.agiliway.civimobileapi}Sessions{/ts}">
                {ts domain=com.agiliway.civimobileapi}Sessions{/ts}
              </a>
            </li>
            <li id="tab_venues" class="crm-tab-button ui-corner-all ui-tabs-tab ui-corner-top ui-state-default ui-tab">
              <a href="{crmURL p='civicrm/civimobile/event-speakers' q="reset=1&event_id="}{$event_id}"
                 title="{ts domain=com.agiliway.civimobileapi}Speakers{/ts}">
                {ts domain=com.agiliway.civimobileapi}Speakers{/ts}
              </a>
            </li>
            <li id="tab_venues" class="crm-tab-button ui-corner-all ui-tabs-tab ui-corner-top ui-state-default ui-tab">
              <a href="{crmURL p='civicrm/civimobile/manage-venues' q="reset=1&use_back_button=0&location_id="}{$location_id}"
                 title="{ts domain=com.agiliway.civimobileapi}Venues{/ts}">
                {ts domain=com.agiliway.civimobileapi}Venues{/ts}
              </a>
            </li>
          </ul>

          <div id="event-sessions-subtab" class="ui-tabs-panel ui-widget-content ui-corner-bottom">
            <div id="event-sessions-block">
              <div class="help">
                {ts domain=com.agiliway.civimobileapi}The agenda consists of the sessions, which are the basic building blocks for structuring your events schedule. A typical session is usually something like a keynote or a workshop. Every session includes a location and speakers.{/ts}
              </div>
              <div class="crm-form-block">
                {if $can_create_event_session}
                  <div style="width:100%;margin-bottom:10px;overflow:auto;">
                    <a href="{crmURL p='civicrm/civimobile/event/session' q="action=add&event_id=`$event_id`"}"
                       class="button crm-popup event-session-popup"><span><i class="crm-i fa-plus-circle"></i> {ts domain=com.agiliway.civimobileapi}Add session{/ts}</span></a>
                  </div>
                {/if}
                <div style="overflow:auto; width:100%;">
                  <div class="crm-accordion-wrapper crm-event-sessions-accordion collapsed">
                    <div class="crm-accordion-header">
                      {ts domain=com.agiliway.civimobileapi}Filter by Session{/ts}
                    </div>
                    <div class="crm-accordion-body" id="eventSessionFilter">
                      <table class="no-border form-layout-compressed">
                        <tr>
                          <td class="crm-inline-edit-field">
                            {$form.venue.label}
                            <br/>
                            {$form.venue.html}
                          </td>
                          <td class="crm-inline-edit-field">
                            {$form.speaker.label}
                            <br/>
                            {$form.speaker.html}
                          </td>
                          <td class="crm-inline-edit-field">
                            {$form.name_include.label}
                            <br/>
                            {$form.name_include.html}
                          </td>
                        </tr>
                      </table>
                    </div>
                  </div>
                </div>
                <table id="event-sessions-table" class="crm-ajax-table" data-order='[[1,"desc"]]'>
                  <thead>
                  <tr>
                    <th data-data="title">{ts domain=com.agiliway.civimobileapi}Session{/ts}</th>
                    <th data-data="date">{ts domain=com.agiliway.civimobileapi}Date{/ts}</th>
                    <th data-data="time" data-orderable="false">{ts domain=com.agiliway.civimobileapi}Time{/ts}</th>
                    <th data-data="venue_name">{ts domain=com.agiliway.civimobileapi}Venue{/ts}</th>
                    <th data-data="speakers" data-orderable="false">{ts domain=com.agiliway.civimobileapi}Speakers{/ts}</th>
                    <th data-data="status" data-orderable="false">{ts domain=com.agiliway.civimobileapi}Status{/ts}</th>
                    <th data-data="links" data-orderable="false"></th>
                  </tr>
                  </thead>
                </table>
              </div>
            </div>
            {literal}
            <script>
              CRM.$(function ($) {
                $("a.event-session-popup").on('crmPopupFormSuccess', function () {
                  CRM.$('#event-sessions-block').parent('.crm-ajax-container').crmSnippet('refresh');
                });
              });
            </script>
            {/literal}
          </div>
          <div class="clear"></div>
        </div>
      </div>
    </div>
  {/if}
</div>
{literal}
<script>
  CRM.$(function ($) {
    $('#Agenda').attr('data-warn-changes', 'false');
    var sessionTable = $('#event-sessions-table');

    sessionTable.data({
      "ajax": {
        "url": {/literal}'{crmURL p="civicrm/civimobile/ajax/event-sessions" h=0}'{literal},
        "data": function (d) {
          d.venue_id = $('#venue').val(),
                  d.speaker = $('#speaker').val(),
                  d.title = $('input[name="name_include"]').val(),
                  d.event_id = {/literal}{$event_id}{literal}
        }
      }
    });
    $(function ($) {
      $('#venue, #speaker, input[name="name_include"]').change(function () {
        sessionTable.DataTable().draw();
      });
    });

    var isUseAgendaCheckbox = CRM.$("#is_use_agenda");

    isUseAgendaCheckbox.change(function () {
      var isUseAgenda = isUseAgendaCheckbox.prop('checked') ? 1 : 0;
      isUseAgendaCheckbox.attr("disabled", true);
      CRM.api3('CiviMobileAgendaConfig', 'create', {
        "event_id": {/literal}{$event_id}{literal},
        "is_active": isUseAgenda
      }).then(function (result) {
        isUseAgendaCheckbox.attr("disabled", false);
        if (isUseAgenda) {
          CRM.alert(ts('Agenda has been enabled!'), ts("Agenda"), "success");
          $('#tab_agenda').removeClass('disabled');
        } else {
          CRM.alert(ts('Agenda has been disabled!'), ts("Agenda"), "success");
          $('#tab_agenda').addClass('disabled');
        }
      }, function (error) {
        CRM.alert(ts('Something went wrong!'), ts("Agenda"), "success");
      });
    });

    if (CRM.$('.ui-dialog').length == 1) {
      var active = 'a.crm-popup';
      CRM.$('#crm-main-content-wrapper').on('crmPopupFormSuccess.crmLivePage', active, CRM.refreshParent, CRM.$('.ui-dialog-titlebar-close').trigger('click'));
    }

    var active = 'a.crm-popup';
    $('#crm-main-content-wrapper')
            // Widgetize the content area
            .crmSnippet()
            // Open action links in a popup
            .off('.crmLivePage')
            .on('click.crmLivePage', active, CRM.popup)
            .on('crmPopupFormSuccess.crmLivePage', active, CRM.refreshParent);

  });
</script>
{/literal}
