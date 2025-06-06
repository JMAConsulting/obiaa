<h3>{$ruleActionHeader}</h3>
<div class="crm-block crm-form-block crm-civirule-rule_action-block-contribution-thank-you-date">
  <div class="help">{$ruleActionHelp}</div>
  <div id="thank-you-radio-block" class="crm-section">
    <div class="label">{$form.thank_you_date_radio.label}</div>
    <div class="content">{$form.thank_you_date_radio.html}</div>
    <div class="clear"></div>
  </div>
  <div id="number_of_days-block" class="crm-section">
    <div class="label">{$form.number_of_days.label}</div>
    <div class="content">{$form.number_of_days.html}</div>
    <div class="clear"></div>
  </div>
  <div id ="thank_you_date-block" class="crm-section">
    <div class="label">{$form.thank_you_date.label}</div>
    <div class="content">{$form.thank_you_date.html}</div>
    <div class="clear"></div>
  </div>
  <div id="thank-you-time-radio-block" class="crm-section">
    <div class="label">{$form.thank_you_time_radio.label}</div>
    <div class="content">{$form.thank_you_time_radio.html}</div>
    <div class="clear"></div>
  </div>
  <div id ="thank_you_time-block" class="crm-section">
    <div class="label">{$form.thank_you_time.label}</div>
    <div class="content">{$form.thank_you_time.html}</div>
    <div class="clear"></div>
  </div>
</div>
<div class="crm-submit-buttons">
  {include file="CRM/common/formButtons.tpl" location="bottom"}
</div>
{literal}
  <script type="text/javascript">
    cj(function($) {
      $("input[type=radio][checked]").each(function() {
        if ($('#CIVICRM_QFID_0_thank_you_date_radio').prop('checked')) {
          $('#number_of_days-block').hide();
          $('#thank_you_date-block').hide();
        }
        if ($('#CIVICRM_QFID_1_thank_you_date_radio').prop('checked')) {
          $('#number_of_days-block').show();
          $('#thank_you_date-block').hide();
        }
        if ($('#CIVICRM_QFID_2_thank_you_date_radio').prop('checked')) {
          $('#number_of_days-block').hide();
          $('#thank_you_date-block').show();
        }
        if ($('#CIVICRM_QFID_0_thank_you_time_radio').prop('checked')) {
          $('#thank_you_time-block').show();
        }
        if ($('#CIVICRM_QFID_1_thank_you_time_radio').prop('checked')) {
          $('#thank_you_time-block').hide();
        }
      });
      $('#CIVICRM_QFID_0_thank_you_date_radio').click(function() {
        $('#number_of_days-block').hide();
        $('#thank_you_date-block').hide();
      })
      $('#CIVICRM_QFID_1_thank_you_date_radio').click(function() {
        $('#number_of_days-block').show();
        $('#thank_you_date-block').hide();
      })
      $('#CIVICRM_QFID_2_thank_you_date_radio').click(function() {
        $('#number_of_days-block').hide();
        $('#thank_you_date-block').show();
      })
      $('#CIVICRM_QFID_0_thank_you_time_radio').click(function() {
        $('#thank_you_time-block').show();
      })
      $('#CIVICRM_QFID_1_thank_you_time_radio').click(function() {
        $('#thank_you_time-block').hide();
      })
    });
  </script>
{/literal}
