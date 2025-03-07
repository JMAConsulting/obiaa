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
                    </div>
                    <div>
                        <p class="description">
                            {ts}To use ChatGPT you must register at
                                <a href="https://platform.openai.com/account/api-keys" target="_blank">openai.com</a>
                                and create your own Secret Key'{/ts}
                        </p>
                    </div>
                </td>
            </tr>
            <tr class="crm-group-form-block-isReserved">
                <td class="label">{$form.civimobile_openai_model.label}</td>
                <td>
                    <div>
                        {$form.civimobile_openai_model.html}
                    </div>
                    <div>
                        <p class="description">
                            {ts}Type your model name in format like this <strong>gpt-4o-mini</strong>{/ts}
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
