<div id="unitBlockId" class="crm-accordion-wrapper crm-unit-accordion">
 <div class="crm-accordion-header">
    {$title}
 </div><!-- /.crm-accordion-header -->
 <div class="crm-accordion-body" id="unitBlock">
  <div class="crm-block crm-form-block">

    {foreach from=$elements item=element}
      <div class="crm-section">
        <div class="label">{$form.$element.label}</div>
        <div class="content">{$form.$element.html}{if $element == 'unit_id'}&nbsp;&nbsp;&nbsp;&nbsp;<span id="add-unit">OR <a class="crm-popup crm-add-entity add-unit-link crm-hover-button" href="{$url}"><i class="crm-i fa-plus-circle" aria-hidden="true"></i> Add Unit </a>{/if}</div>
        <div class="clear"></div>
      </div>
    {/foreach}

  </div>
</div>
</div>
