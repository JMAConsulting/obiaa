<div tabindex="-1" role="dialog" id="description-generation-popup"
     class="ui-dialog ui-corner-all ui-widget ui-widget-content ui-front ui-dialog-buttons crm-container">
    <div class="ui-dialog-titlebar ui-corner-all ui-widget-header ui-helper-clearfix"
         id="description-generation-header">
        <span class="ui-dialog-title">{ts domain="com.agiliway.civimobileapi"}Generate description{/ts}</span>
        <button type="button" class="ui-button ui-corner-all ui-widget ui-button-icon-only ui-dialog-titlebar-close"
                id="generate-description-close-popup">
            <span class="ui-button-icon ui-icon fa-times"></span>
            <span class="ui-button-icon-space"> </span>{ts domain="com.agiliway.civimobileapi"}Close{/ts}
        </button>
    </div>
    <div class="crm-container crm-form-block">
        <div id="result-window"></div>
        <div>
            <label for="user-input-window"></label>
            <textarea rows="4" cols="60" id="user-input-window" class="crm-form-textarea"
                      placeholder="{ts domain="com.agiliway.civimobileapi"}Here you can add some editions. E.g: 'Add new date to the generated text'{/ts}"></textarea>
        </div>
        <div>
            <button type="button" class="ui-button ui-corner-all ui-widget" id="regenerate-description">
                <span class="ui-button-icon-space"> </span> {ts domain="com.agiliway.civimobileapi"}Generate{/ts}
            </button>
            <a class="helpicon" href="#"
               onclick="handleHelpOnClick()"></a>
        </div>
    </div>
    <div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
        <div class="ui-dialog-buttonset">
            <button type="button" class="ui-button ui-corner-all ui-widget" id="save-description">
                <span class="ui-button-icon ui-icon crm-i fa-check"></span><span class="ui-button-icon-space"> </span>
                {ts domain="com.agiliway.civimobileapi"}Save description{/ts}
            </button>
            <button type="button" class="ui-button ui-corner-all ui-widget" id="cancel-button">
                <span class="ui-button-icon ui-icon crm-i fa-times"></span><span class="ui-button-icon-space"> </span>
                {ts domain="com.agiliway.civimobileapi"}Cancel{/ts}
            </button>
        </div>
    </div>
</div>


{literal}
    <style>
        #description-generation-popup {
            position: absolute;
            height: auto;
            width: 65%;
            top: 41px;
            left: 290px;
            display: none;
            z-index: 101;
            zoom: 1;
        }

        .crm-container .generated-text-description {
            padding: 4px 6px;
            margin: 0 0 0.3em;
        }

        #user-input-window.crm-form-textarea {
            max-width: 100%;
            margin: 0;
        }
    </style>
{/literal}


{literal}
<script>

    function handleHelpOnClick() {
        CRM.help("Regenerate option", "Type additional criteria for text generation such as 'add more about location' or 'include information about the speaker'. If you are satisfied with the result, hit 'Save description'. After entering new criteria, you should press the 'Generate' button. Pressing Generate with a blank field will wipe out text.")
    }

    CRM.$(function ($) {

        const descriptionPopup = $('#description-generation-popup');
        const resultWindow = $('#result-window');
        const userInputWindow = $('#user-input-window');

        let isResultWindowEmpty = true;

        function clearFields() {
            resultWindow.html('');
            userInputWindow.val('');
        }

        function showLoading() {
            resultWindow.html('<div class="crm-loading-element"><span class="loading-text">{/literal}{ts escape='js'}Please wait while your description is being generated{/ts}{literal}...</span></div>');
        }

        function hidePopup() {
            descriptionPopup.hide();
        }

        descriptionPopup.draggable({handle: '#description-generation-header'});

        $('#cancel-button').on('click', function () {
            hidePopup();
        });

        $('#generate-description').on('click', function () {
            const titleValue = $('.crm-event-manage-eventinfo-form-block-title input[name="title"]').val();
            const typeValueId = $('.crm-event-manage-eventinfo-form-block-event_type_id #event_type_id').val();

            showLoading();

            if (typeValueId && titleValue) {
                const typeValue = $('.crm-event-manage-eventinfo-form-block-event_type_id #event_type_id :selected').text();
                descriptionPopup.show();

                if (!isResultWindowEmpty) {
                    CKEDITOR.instances['description'].setData('');
                }

                CRM.api4('CiviMobileGenerateEventDescription', 'create', {
                    checkPermissions: false,
                    values: {
                        'title': titleValue,
                        'type': typeValue
                    },
                }).then(function (result) {
                    if (result && result[0] && result[0]['choices'] && result[0]['choices'][0] && result[0]['choices'][0]['message'] && result[0]['choices'][0]['message']['content']) {
                        const generatedData = result[0]['choices'][0]['message']['content'];
                        resultWindow.html('<h3 class="title">{/literal}{ts escape='js'}Generated description{/ts}{literal}:</h3>' + '<p class="generated-text-description">' + generatedData + '</p>');
                        isResultWindowEmpty = false;
                    } else {
                        descriptionPopup.hide();
                        CRM.alert({/literal}'{ts escape="js"}Invalid API key. Please check your key.{/ts}', '{ts escape="js"}Error{/ts}'{literal}, 'error');
                    }
                }, function () {
                    CRM.alert(ts('Something went wrong.'), ts('Description can\'t be generated'), 'error');
                });
            } else {
                CRM.alert({/literal}'{ts escape="js"}Please, enter title and type for the event{/ts}', '{ts escape="js"}Warning{/ts}'{literal}, 'alert');
            }
        });

        $('#regenerate-description').on('click', function () {
            const userInputAndData = [];

            const userInput = userInputWindow.val();
            const generatedData = resultWindow.text();
            const storedGeneratedData = {
                role: 'assistant',
                content: generatedData
            };

            const userRequest = {
                role: 'user',
                content: userInput
            };

            showLoading();

            userInputAndData.push(storedGeneratedData, userRequest)

            CRM.api4('CiviMobileGenerateEventDescription', 'create', {
                checkPermissions: false,
                values: {
                    'userInputAndData': userInputAndData
                }
            }).then(function (result) {
                clearFields();
                resultWindow.html('<h3 class="title">{/literal}{ts escape='js'}Generated description{/ts}{literal}:</h3>' + '<p class="generated-text-description">' + result[0]['choices'][0]['message']['content'] + '</p>');
            }, function () {
                CRM.alert(ts('Something went wrong.'), ts('Description can\'t be generated'), 'error');
            });
        });

        $('#save-description').on('click', function () {
            const description = resultWindow.text();
            CKEDITOR.instances['description'].insertText(description);
            clearFields();
            hidePopup();
        });

        $('#generate-description-close-popup').on('click', function () {
            hidePopup();
        });

    });

</script>
{/literal}



