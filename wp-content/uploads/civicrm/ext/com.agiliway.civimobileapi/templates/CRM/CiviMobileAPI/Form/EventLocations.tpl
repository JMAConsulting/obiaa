{include file="CRM/common/jsortable.tpl"}
<div class="crm-block crm-form-block">
  <table  id="options" class="display">
    <thead>
    <tr>
      <th>
        {ts domain=com.agiliway.civimobileapi}Locations{/ts}
      </th>
      <th>
        {ts domain=com.agiliway.civimobileapi}Manage Venues{/ts}
      </th>
    </tr>
    </thead>
    {foreach from=$locations item=location name=foo }
      <tr>
        <td>
          {$location}
        </td>
        <td>
          {assign var="count" value= $smarty.foreach.foo.iteration-1}
          <a href="{crmURL p='civicrm/civimobile/manage-venues' q="reset=1&location_id="}{$locationsId[$count]}" class="action-item crm-hover-button"> Manage
            Venues </a>
        </td>
      </tr>
    {/foreach}
  </table>
  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
</div>

