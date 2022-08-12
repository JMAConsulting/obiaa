{crmScope extensionKey='biaproperty'}
{if $action eq 8}
  {* Are you sure to delete form *}
  <h3>{ts}Delete Entity{/ts}</h3>
  <div class="crm-block crm-form-block">
    <div class="crm-section">{ts}Are you sure you wish to delete this property?{/ts}</div>
  </div>

  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
{else}

  <h3>{$propertyTitle}</h3>

  <div class="crm-block crm-form-block">

    {foreach from=$elements key=element item=label}
      <div class="crm-section">
        <div class="label">{$form.$element.label}</div>
        <div class="content">{$form.$element.html}</div>
        <div class="clear"></div>
      </div>
    {/foreach}

    <div class="crm-submit-buttons">
      {include file="CRM/common/formButtons.tpl" location="bottom"}
    </div>

  </div>

{/if}
{/crmScope}
