{include file="CRM/common/jsortable.tpl"}
{literal}
  <style>
    .ui-dialog-content.ui-widget-content.modal-dialog {
      height: auto !important;
      max-height: 80vh !important;
    }
    .venue-attached-file {
      width: 100px;
      height: 100px;
      object-fit: cover;
    }
    .venue-color-picker {
      width: 100px;
      height: 20px;
      border-radius: 3px;
      border: 2px solid white;
      position: relative;
    }
    .venue-color-picker .venue-color-list {
      display: none;
      list-style-type: none;
      padding: 15px;
      margin: 0;
      width: 300px;
      position: absolute;
      background: white;
      z-index: 20000;
      top: -3px;
      left: -3px;
      flex-wrap: wrap;
      box-shadow: 0 0 3px 0 rgba(0, 0, 0, 0.3);
      cursor: auto;
      height: 150px;
      overflow-y: auto;
    }
    .venue-color-picker .venue-color-list li{
      display: block;
      width: calc(50% - 24px);
      margin: 10px;
      height: 30px;
      box-sizing: border-box;
      border: 2px solid white;
    }
    .venue-color-picker .venue-color-list li i{
      display: block;
      width: 100%;
      height: 100%;
      cursor: pointer;
      box-sizing: border-box;
      border: 2px solid white;
    }
    .venue-color-picker .venue-color-list li.selected{
      border: 2px solid black;
    }
    .clickable {
      cursor: pointer;
    }
  </style>
{/literal}
<div class="crm-container">
  <div class="crm-block crm-form-block crm-civimobile-venue-form-block">
    <div class="crm-submit-buttons">
      {if $action eq 4}
        {if $can_edit_venue}
          <a href="{crmURL p='civicrm/civimobile/venue' q='reset=1&action=update&id='}{$venue.id}{"&location_id="}{$location_id}" class="edit button" title="{ts domain=com.agiliway.civimobileapi}Edit{/ts}"><span><i class="crm-i fa-pencil"></i> {ts domain=com.agiliway.civimobileapi}Edit{/ts}</span></a>
        {/if}
        {if $can_delete_venue}
          <a href="{crmURL p='civicrm/civimobile/venue' q='reset=1&action=delete&id='}{$venue.id}{"&location_id="}{$location_id}" class="delete button" title="{ts domain=com.agiliway.civimobileapi}Delete{/ts}"><span><i class="crm-i fa-trash"></i> {ts domain=com.agiliway.civimobileapi}Delete{/ts}</span></a>
        {/if}
      {/if}
      {include file="CRM/common/formButtons.tpl" location="top"  multiple="multiple"}
    </div>
    {if $action eq 1 or $action eq 2} {*ADD and EDIT*}
      <table class="form-layout-compressed">
        <tr role="row">
          <td class="label">{$form.venue_name.label}</td>
          <td class="view-value">{$form.venue_name.html}</td>
        </tr>
        <tr role="row">
          <td class="label">{$form.description.label}</td>
          <td class="view-value">{$form.description.html}</td>
        </tr>
        <tr role="row">
          <td class="label">{$form.is_active.label}</td>
          <td class="view-value">{$form.is_active.html}
          </td>
        </tr>
        <tr role="row">
          <td class="label">{$form.weight.label}</td>
          <td class="view-value">{$form.weight.html}
          </td>
        </tr>
        <tr role="row">
          <td class="label"><label>{ts domain=com.agiliway.civimobileapi}Color{/ts}</label></td>
          <td class="view-value">{$form.color.html}
            <div class="venue-color-picker clickable" onclick="CRM.$('.venue-color-list').css('display', 'flex')">
              <ul class="venue-color-list">
                {foreach from=$colors item=color}
                  <li {if $color eq $selectedColor}class="selected"{/if}>
                    <i style="background-color: {$color.background};border-color: {$color.border}" data-color='{$color|@json_encode}' onclick="selectColor(this)"></i>
                  </li>
                {/foreach}
              </ul>
            </div>
          </td>
        </tr>
      </table>
      <fieldset id="location_g" class="crm-collapsible">
        <legend class="collapsible-title">{ts domain=com.agiliway.civimobileapi}Location{/ts}</legend>
        <div id="location_screen">
          <table class="form-layout-compressed">
            <tr role="row">
              <td class="label">{$form.address.label} {help id="address-help"}</td>
              <td class="view-value">{$form.address.html}</td>
            </tr>
            <tr role="row">
              <td class="label">{$form.address_description.label} {help id="address-description-help"}</td>
              <td class="view-value">{$form.address_description.html}
              </td>
            </tr>
          </table>
        </div>
      </fieldset>
      {include file="CRM/Form/attachment.tpl"}
    {/if}

    {if $action eq 4 } {*view*}
      <table class="crm-info-panel">
        <tr>
          <td class="label">{$form.venue_name.label}</td>
          <td>{$venue.name}</td>
        </tr>
        <tr class="odd" role="row">
          <td class="label">{$form.description.label}</td>
          <td>{$venue.description}</td>
        </tr>
        <tr class="odd" role="row">
          <td class="label">{$form.is_active.label}</td>
          <td>{if $venue.is_active eq 1}Yes{else}No{/if}</td>
        </tr>
        <tr class="odd" role="row">
          <td class="label"><label>Color</label></td>
          <td>
            <div class="venue-color-picker" style="background:{$venue.background_color};border-color:{$venue.border_color}"></div>
          </td>
        </tr>
      </table>
      <fieldset id="location" class="crm-collapsible">
        <legend class="collapsible-title">{ts domain=com.agiliway.civimobileapi}Location{/ts}</legend>
        <div id="location_screen">
          <table class="crm-info-panel">
            <tr class="odd" role="row">
              <td class="label">{$form.address.label}</td>
              <td>{$venue.address}</td>
            </tr>
            <tr class="odd" role="row">
              <td class="label">{$form.address_description.label}</td>
              <td>{$venue.address_description}</td>
            </tr>
          </table>
        </div>
      </fieldset>
      <table class="crm-info-panel">
        {include file="CRM/Form/attachment.tpl"}
      </table>
    {/if}

    {if $action eq 8 } {*delete*}
      <div class="status">Are you sure ?</div>
    {/if}
    <div class="crm-submit-buttons">
      {if $action eq 4}
        {if $can_edit_venue}
          <a href="{crmURL p='civicrm/civimobile/venue' q='reset=1&action=update&id='}{$venue.id}{"&location_id="}{$location_id}" class="edit button" title="{ts domain=com.agiliway.civimobileapi}Edit{/ts}"><span><i class="crm-i fa-pencil"></i> {ts domain=com.agiliway.civimobileapi}Edit{/ts}</span></a>
        {/if}
        {if $can_delete_venue}
          <a href="{crmURL p='civicrm/civimobile/venue' q='reset=1&action=delete&id='}{$venue.id}{"&location_id="}{$location_id}" class="delete button" title="{ts domain=com.agiliway.civimobileapi}Delete{/ts}"><span><i class="crm-i fa-trash"></i> {ts domain=com.agiliway.civimobileapi}Delete{/ts}</span></a>
        {/if}
      {/if}
      {include file="CRM/common/formButtons.tpl" location="bottom"  multiple="multiple"}
    </div>
  </div>
</div>

{if $action eq 1 or $action eq 2}
{literal}
  <script>
    CRM.$(function($) {
      renewColor();
    });

    function renewColor() {
      const selectedColor = JSON.parse(CRM.$('.CRM_CiviMobileAPI_Form_Venue input[name="color"]').val());
      CRM.$('.venue-color-picker').css({
        borderColor: selectedColor.border,
        background: selectedColor.background
      });
    }

    function selectColor(el) {
      CRM.$('.venue-color-picker li.selected').removeClass('selected');
      CRM.$(el).parent('li').addClass('selected');
      CRM.$('.CRM_CiviMobileAPI_Form_Venue input[name="color"]').val(JSON.stringify(CRM.$(el).data('color')));
      renewColor();
    }

    CRM.$(document).mouseup(function(e) {
      var container = CRM.$(".venue-color-list");

      if (!container.is(e.target) && container.has(e.target).length === 0) {
        container.hide();
      }
    });
  </script>
{/literal}
{/if}
