{* HEADER *}

{* FIELD EXAMPLE: OPTION 1 (AUTOMATIC LAYOUT) *}

{foreach from=$elementNames item=elementName}
  <div class="crm-section">
    <div class="label">{$form.$elementName.label}</div>
    <div class="content">{$form.$elementName.html}{if $elementName == 'unit_id'}&nbsp;&nbsp;&nbsp;&nbsp;<span id="add-unit">OR <a target="_blank" class="crm-popup crm-add-entity add-unit-link crm-hover-button" href="{$url}"><i class="crm-i fa-plus-circle" aria-hidden="true"></i> Add Unit </a>{/if}</div>
    <div class="clear"></div>
  </div>
{/foreach}

{* FOOTER *}
<div class="crm-submit-buttons">
{include file="CRM/common/formButtons.tpl" location="bottom"}
</div>
