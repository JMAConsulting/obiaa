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
                            {ts 1='href="https://platform.openai.com/account/api-keys" target="_blank"'}To use ChatGPT you must register at
                                <a %1>openai.com</a>
                                and create your own Secret Key{/ts}
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
                            {ts domain="com.agiliway.civimobileapi"}Type your model name in format like this{/ts} <strong>gpt-4o-mini</strong>
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
