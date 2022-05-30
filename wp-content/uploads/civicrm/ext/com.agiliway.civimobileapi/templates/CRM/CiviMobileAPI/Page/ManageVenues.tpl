{include file="CRM/common/jsortable.tpl"}
<div id="manage-venues-block">
{if $location_id}
  <div class="help">
    {ts domain=com.agiliway.civimobileapi}Venues are used to group sessions by a topic or a theme and can be located in a different address than the event.{/ts}
  </div>
  <div class="crm-block crm-form-block">
    <div class="crm-submit-buttons">
      {if $can_edit_venue}<a
        href="{crmURL p='civicrm/civimobile/venue' q="action=add&reset=1&location_id="}{$location_id}"
        class="button crm-popup medium-popup venue-form-popup" style="overflow:auto;"><span><i class="crm-i fa-plus-circle"></i> Add venue</span>
        </a>{/if}
      {if $use_back_button}<a href="{crmURL p='civicrm/civimobile/event-locations'}"
                              class="cancel button manage-venues-back-button no-popup" style="overflow:auto;"><span><i
                  class="crm-i fa-times"></i> Done</span></a>{/if}
    </div>
    <table class="row-highlight">
      <thead>
      <tr>
        <th class="sorting_disabled">
          {ts domain=com.agiliway.civimobileapi}Name{/ts}
        </th>
        <th class="sorting_disabled">
          {ts domain=com.agiliway.civimobileapi}Is active ?{/ts}
        </th>
        <th class="sorting_disabled">{ts domain=com.agiliway.civimobileapi}Order{/ts}</th>
        <th></th>
      </tr>
      </thead>
      {foreach from=$venues item=venue}
        <tr class="{cycle values="odd-row,even-row"}">
          <td>
            {$venue.name}
          </td>
          <td>
            {$is_active[$venue.is_active]}
          </td>
          <td class="nowrap center">
            {$venue.weight}
          </td>
          <td class="nowrap">
            <a href="{crmURL p='civicrm/civimobile/venue' q="action=view&reset=1&id="}{$venue.id}{"&location_id="}{$location_id}"
               class="action-item crm-hover-button crm-popup medium-popup venue-form-popup">View</a>
            {if $can_edit_venue}
              <a href="{crmURL p='civicrm/civimobile/venue' q="action=update&reset=1&id="}{$venue.id}{"&location_id="}{$location_id}"
                 class="action-item crm-hover-button crm-popup medium-popup venue-form-popup">Edit</a>
            {/if}
            {if $can_delete_venue}
              <a href="{crmURL p='civicrm/civimobile/venue' q="action=delete&reset=1&id="}{$venue.id}{"&location_id="}{$location_id}"
                 class="action-item crm-hover-button crm-popup small-popup venue-form-popup">Delete</a>
            {/if}
          </td>
        </tr>
      {/foreach}
    </table>
  </div>
{else}
  <div class="status">
    {ts domain=com.agiliway.civimobileapi}If you want to create venues, you need to add the location for the event.{/ts}
  </div>
{/if}
</div>
{literal}
  <script>
    CRM.$(function ($) {
      $('#crm-main-content-wrapper')
              // Widgetize the content area
              .crmSnippet()
              // Open action links in a popup
              .off('.crmLivePage')

      $("a.venue-form-popup").on('crmPopupFormSuccess', function() {
        CRM.$('#manage-venues-block').parent('.crm-ajax-container').crmSnippet('refresh');
      });


    });
  </script>
{/literal}
