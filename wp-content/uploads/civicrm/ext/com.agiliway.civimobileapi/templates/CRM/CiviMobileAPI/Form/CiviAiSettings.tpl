<div class="crm-block crm-form-block crm-form-civimobilesettings-block">
    <div class="crm-submit-buttons">
        {include file="CRM/common/formButtons.tpl" location="top"}
    </div>
    <div>
        <table class="form-layout-compressed">
            <tbody>
            <tr class="crm-group-form-block-isReserved">
                <td class="label">{$form.civimobile_ai_secret_key.label} {help id="ai-secret-key-help"}</td>
                <td>
                    <div>
                        {$form.civimobile_ai_secret_key.html}
                    </div>
                </td>
            </tr>
            <tr class="crm-group-form-block-isReserved">
                <td class="label">{$form.civimobile_ai_model.label} {help id="ai-model-help"}</td>
                <td>
                    <div>
                        {$form.civimobile_ai_model.html}
                    </div>
                </td>
            </tr>
            <tr class="crm-group-form-block-isReserved">
                <td class="label">{$form.civimobile_ai_api_url.label} {help id="api-url-help"}</td>
                <td>
                    <div>
                        {$form.civimobile_ai_api_url.html}
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
