{crmScope extensionKey='biaproperty'}
{if $action eq 8}
  {* Are you sure to delete form *}
  <h3>{ts}Delete Unit{/ts}</h3>
  <div class="crm-block crm-form-block">
    <div class="crm-section">{ts}Are you sure you wish to delete this unit?{/ts}</div>
  </div>

  <div class="crm-submit-buttons">
    {include file="CRM/common/formButtons.tpl" location="bottom"}
  </div>
{else}
  <div class="crm-block crm-form-block">
    {foreach from=$elements key=element item=label}
      <div class="crm-section">
        <div class="label">{$form.$element.label}</div>
        <div class="content">{$form.$element.html} {if $element eq 'address_id' && $unit} <a id='edit-unit-address' href="{crmURL p='civicrm/unit-address' q="reset=1&id=`$unit.address_id`"}" class="crm-popup" title="Edit Unit Address">Edit Unit Address</a>{/if}
        {if ($element eq 'unit_photo' && $imageURL)}
          {include file="CRM/Contact/Page/ContactImage.tpl"}
        {/if}
        </div>
        <div class="clear"></div>
      </div>
    {/foreach}

    <div class="crm-submit-buttons">
      {include file="CRM/common/formButtons.tpl" location="bottom"}
    </div>

  </div>
  <script type="text/javascript">
    {literal}
      (function($) {
        if ($('#unit_status').attr('type') == 'hidden') {
           $('#unit_status').parent().parent().parent().hide();
        }
        $('#address_id').on('change', function(e) {
          if ($('#edit-unit-address').length != -1) {
             $('#edit-unit-address').attr('href', CRM.url('civicrm/unit-address', 'reset=1&id=' + $(this).val()));
          }
        });
        $('#_qf_Unit_cancel-bottom').on('click', function() {
          if ($('#address_id').val() > 0) {
            CRM.api4({units: ['Unit', 'get', {
              where: [["address_id", "=", $('#address_id').val()]],
            }]}).then(function(batch) {
              if (batch.units.count === 0) {
                CRM.api4({results: ['Address', 'delete', {
                  where: [["id", "=", $('#address_id').val()]]
                }]});
              }
            });
          }
        });
        $('.crm-popup').on('crmPopupFormSuccess', function(event, dialog, formdata) {
          $('#address_id').select2('data', {id: formdata.id, label: formdata.label});
          let currentTitle = CRM.$('.crm-title').text();
          if (currentTitle.includes('Unit')) {
            CRM.api3('Address', 'get', {id: formdata.id}).then(function(addressdata) {
              $.each(addressdata.values, function(id, value) {
                var label = ('street_unit' in value ? 'Unit ' + value.street_unit + ' - ' : '') + value.street_address;
                CRM.$('#crm-main-content-wrapper').find('h3').text(label + ' ' + value.city + ' ' + value.postal_code);
              });
            });
          }
        });
      })(CRM.$);
    {/literal}
  </script>
  {if $propertyID >= 0}
    <script type="text/javascript">
      {literal}
        (function($) {
          var property_id = {/literal}{$propertyID}{literal};
          var unit_id = '{/literal}{$id}{literal}';
          unit_id = unit_id === '' ? 0 : unit_id;
          var $form = $('form.{/literal}{$form.formClass}{literal}');

          $('#_qf_Unit_upload-bottom').on('click', function(e) {
            if (unit_id > 0 && (parseInt($('#unit_status').val()) !== 1) && $('#business_id').val() != '') {
               e.preventDefault();
               CRM.confirm({title: ts('Confirm'), message: 'Do you want to Close the business occupying this unit? To move it, navigate to the business, then click Actions > Move business within BIA."'})
                .on('crmConfirm:yes', save);
            }
          });
          function save() {$form.submit();}
          if (property_id >= 0) { refreshUnitAddress(property_id); }
          $('#property_id').on('change', function() { refreshUnitAddress($(this).val()); });

          function refreshUnitAddress(pid) {
            let params = $('#address_id').data('api-params');
            if (params == null) {
              params = {params: {}};
            }
            else if (typeof params.params === "undefined") {
              params.params = {};
            }
            params.params.property_id = pid;
            $('#address_id').attr('data-api-params', JSON.stringify(params)).data('api-params', params).trigger('change');
            CRM.api4('Unit', 'get', {
              where: [
                ['property_id', '=', pid],
              ],
            }).then(function(results) {
              if (results.length == 1) {
                CRM.api3('Address', 'get', {id: results[0].address_id}).then(function(addressdata) {
                  $.each(addressdata.values, function(id, value) {
                    var unitLabel = ('street_unit' in value ? '#' + value.street_unit + ' - ' : '') + value.street_address;
                    let newOption = new Option(unitLabel, value.id, true, true);
                    $('#address_id').append(newOption).select2('data', {id: value.id, label: unitLabel}).trigger('change');
                  });
                });
              }
            });
          }
        })(CRM.$);
      {/literal}
    </script>
  {/if}
{/if}
{/crmScope}
