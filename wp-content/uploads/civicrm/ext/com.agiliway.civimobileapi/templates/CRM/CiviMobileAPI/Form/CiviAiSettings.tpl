<div class="crm-block crm-form-block crm-form-civimobilesettings-block">
    <div class="crm-submit-buttons">
        {include file="CRM/common/formButtons.tpl" location="top"}
    </div>
    <div>
        <table class="form-layout-compressed">
            <tbody>
            <tr class="crm-group-form-block-isReserved">
                <td class="label">{$form.civimobile_openai_secret_key.label} {help id="secret-key-help"}</td>
                <td>
                    <div>
                        {$form.civimobile_openai_secret_key.html}
                        {if $form.civimobile_openai_secret_key.description}
                            <br/>
                            <span class="description">{$form.civimobile_openai_secret_key.description}</span>
                        {/if}
                    </div>
                    <div>
                        <p class="description">
                            {$civiAiMessage}
                        </p>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="crm-submit-buttons">
        {include file="CRM/common/formButtons.tpl" location="bottom"}
    </div>
</div>
