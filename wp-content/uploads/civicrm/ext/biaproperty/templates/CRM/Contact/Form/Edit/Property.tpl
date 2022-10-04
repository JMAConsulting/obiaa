<div id="propertyID" class="crm-accordion-wrapper crm-address-accordion">
 <div class="crm-accordion-header">
    {$title}
 </div><!-- /.crm-accordion-header -->
 <div class="crm-accordion-body" id="addressBlock">
  <div class="crm-block crm-form-block">

    {foreach from=$elements key=element item=label}
      <div class="crm-section">
        <div class="label">{$form.$element.label}</div>
        <div class="content">{$form.$element.html}</div>
        <div class="clear"></div>
      </div>
    {/foreach}

  </div>
</div>
</div>
{literal}
<script type="text/javascript">
  CRM.$(function($) {
   $('#_qf_Contact_cancel-bottom, #_qf_Contact_cancel-top').on('click', function(){
     if ($('#property_id').val() > 0) {
       CRM.api4({propertyOwners: ['PropertyOwner', 'get', {
         where: [["property_id", "=", $('#property_id').val()]],
       }]}).then(function(batch) {
         if (batch.propertyOwners.count === 0) {
           CRM.api4({results: ['Property', 'delete', {
             where: [["id", "=", $('#property_id').val()]]
           }]});
           CRM.api4({results: ['Unit', 'delete', {
             where: [["property_id", "=", $('#property_id').val()]]
           }]});
         }
       });
     }
   });
  });
</script>
{/literal}
