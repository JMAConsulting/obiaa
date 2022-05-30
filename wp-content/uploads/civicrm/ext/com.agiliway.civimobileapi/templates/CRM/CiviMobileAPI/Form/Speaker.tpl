<div class="crm-container">
  <div class="crm-block crm-form-block crm-civimobile-session-form-block">
    <div class="crm-submit-buttons">
      {if $action eq 4}
        {if $can_edit_speaker}
          <a href="{crmURL p='civicrm/civimobile/event/speaker' q='reset=1&action=update&pid='|cat:$speaker.participant_id|cat:'&eid='|cat:$speaker.event_id}"
             class="edit button" title="{ts domain=com.agiliway.civimobileapi}Edit{/ts}"><span><i class="crm-i fa-pencil"></i> {ts domain=com.agiliway.civimobileapi}Edit{/ts}</span></a>
        {/if}
      {/if}
      {include file="CRM/common/formButtons.tpl" location="bottom"  multiple="multiple"}
    </div>
    {if $action eq 4}
      <table class="crm-info-panel">
        <tr>
          <td class="label">{$form.first_name.label}</td>
          <td>
            {$speaker.first_name}
          </td>
        </tr>
        <tr>
          <td class="label">{$form.last_name.label}</td>
          <td>
            {$speaker.last_name}
          </td>
        </tr>
        <tr>
          <td class="label">{$form.job_title.label}</td>
          <td>
            {$speaker.job_title}
          </td>
        </tr>
        <tr>
          <td class="label">{$form.current_employer_id.label}</td>
          <td>
            {$speaker.current_employer}
          </td>
        </tr>
        <tr>
          <td class="label">{$form.image_URL.label}</td>
          <td>
            {if $speaker.image_URL}
              <br/>
              <a href="{$speaker.image_URL}" class="crm-image-popup">
                <img src="{$speaker.image_URL}" class="speaker-image">
              </a>
            {/if}
          </td>
        </tr>
        <tr>
          <td class="label">{$form.participant_bio.label}</td>
          <td>
            {$speaker.participant_bio}
          </td>
        </tr>
      </table>
    {elseif $action eq 2}
        <table class="form-layout">
          <tr>
            <td class="label">{$form.first_name.label}</td>
            <td class="view-value">{$form.first_name.html}</td>
          </tr>
          <tr>
            <td class="label">{$form.last_name.label}</td>
            <td class="view-value">{$form.last_name.html}</td>
          </tr>
          <tr>
            <td class="label">{$form.current_employer_id.label}</td>
            <td class="view-value">{$form.current_employer_id.html}</td>
          </tr>
          <tr>
            <td class="label">{$form.job_title.label}</td>
            <td class="view-value">{$form.job_title.html}</td>
          </tr>
          <tr>
            <td class="label">{$form.image_URL.label}</td>
            <td class="view-value">
              {$form.image_URL.html}
              {if $speaker.image_URL}
                <div class="speaker-image">
                  <a href="{$speaker.image_URL}" class="crm-image-popup">
                    <img src="{$speaker.image_URL}" class="speaker-image">
                  </a>
                  <br/>
                  <a class="delete-contact-file" style="color:red" href="javascript:deleteContactImage();"><i class="crm-i fa-trash"></i> Delete contact image</a>
                </div>
              {/if}
            </td>
          </tr>
          <tr>
            <td class="label">{$form.participant_bio.label}</td>
            <td class="view-value">{$form.participant_bio.html}</td>
          </tr>
        </table>
      {/if}
    <div class="crm-submit-buttons">
      {if $action eq 4}
        {if $can_edit_speaker}
          <a href="{crmURL p='civicrm/civimobile/event/speaker' q='reset=1&action=update&pid='|cat:$speaker.participant_id|cat:'&eid='|cat:$speaker.event_id}"
             class="edit button" title="{ts domain=com.agiliway.civimobileapi}Edit{/ts}"><span><i class="crm-i fa-pencil"></i> {ts domain=com.agiliway.civimobileapi}Edit{/ts}</span></a>
        {/if}
      {/if}
      {include file="CRM/common/formButtons.tpl" location="bottom"  multiple="multiple"}
    </div>
  </div>
</div>

{literal}
  <script>
    var contactId = {/literal}{$speaker.contact_id}{literal};

    function deleteContactImage() {
      if (confirm("Are you sure you want to delete contact image?")) {
        CRM.$.get(CRM.url('civicrm/contact/image', {
            'action': 'delete',
            'cid': contactId,
            'confirmed': 1
          }),
        function () {
          CRM.$(".speaker-image").remove();
        });
      }
    }
  </script>
  <style>
    .ui-dialog-content.ui-widget-content.modal-dialog {
      height: auto !important;
      max-height: 80vh !important;
    }
    .speaker-image {
      width: 100px;
      height: 100px;
      object-fit: cover;
    }
  </style>
{/literal}
