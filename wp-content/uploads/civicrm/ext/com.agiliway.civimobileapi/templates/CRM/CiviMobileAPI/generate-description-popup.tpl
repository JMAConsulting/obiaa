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
            <a class="helpicon"
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
        CRM.help(ts("Regenerate option"), ts("Type additional criteria for text generation such as 'add more about location' or 'include information about the speaker'. If you are satisfied with the result, hit 'Save description'. After entering new criteria, you should press the 'Generate' button. Pressing Generate with a blank field will wipe out text."))
    }

    function handleAutogenerateHelp() {
        CRM.help(ts('Autogenerate option'), ts('Autogenerate option will give you opportunity to create description using extended AI functionality. Fill out required fields and press Autogenerate button. If you need, you can edit generated text in pop-up. If you are satisfied with result, save text and it will appear in your description field'));
    }

    CRM.$(function ($) {
        const descriptionPopup = $('#description-generation-popup');
        const resultWindow = $('#result-window');
        const userInputWindow = $('#user-input-window');
        let generateType = {};
        let requireParams = '';

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

        $(document).on('click', '.generate-text-button', function() {
            generateType = $(this).data('generate-type');
            generateText();
        });

        $('#regenerate-description').on('click', function () {
            generateText();
        });

        function generateText() {
            let aiParams = {
                generateType,
            };

            if (generateType === 'event-description') {
                requireParams = '"Title", "Type" ';

                aiParams = {
                    ...aiParams,
                    title: $('.crm-event-manage-eventinfo-form-block-title input[name="title"]').val(),
                    type:
                        $('.crm-event-manage-eventinfo-form-block-event_type_id #event_type_id').val() ?
                            $('.crm-event-manage-eventinfo-form-block-event_type_id #event_type_id :selected').text() : null,
                }
            }
            else if (generateType === 'petition-introduction') {
                requireParams = '"Title", "Campaign" is optional ';

                aiParams = {
                    ...aiParams,
                    title: $('.crm-campaign-survey-form-block-title input[name="title"]').val(),
                };

                let campaign = $('.crm-campaign-survey-form-block-campaign_id #campaign_id').val() ?
                    $('.crm-campaign-survey-form-block-campaign_id #s2id_campaign_id .select2-chosen').text() : null;

                if (campaign) {
                    aiParams.campaign = campaign;
                }
            }
            else if (generateType === 'thank-you-message') {
                requireParams = '"Title", "Thank you title", "Campaign" is optional ';

                aiParams = {
                    ...aiParams,
                    title: $('.crm-campaign-survey-form-block-title input[name="title"]').val(),
                    thank_you_title: $('.crm-campaign-survey-form-block-thankyou_title input[name="thankyou_title"]').val(),
                };

                let campaign = $('.crm-campaign-survey-form-block-campaign_id #campaign_id').val() ?
                    $('.crm-campaign-survey-form-block-campaign_id #s2id_campaign_id .select2-chosen').text() : null;

                if (campaign) {
                    aiParams.campaign = campaign;
                }
            }
            else if (generateType === 'survey-instructions') {
                requireParams = '"Title", "Activity Type", "Campaign" is optional ';

                aiParams = {
                    ...aiParams,
                    title: $('.crm-campaign-survey-main-form-block-title input[name="title"]').val(),
                    activity_type:
                        $('.crm-campaign-survey-main-form-block-activity_type_id #activity_type_id').val() ?
                            $('.crm-campaign-survey-main-form-block-activity_type_id #s2id_activity_type_id .select2-chosen').text() : null,
                };

                let campaign = $('.crm-campaign-survey-main-form-block-campaign_id #campaign_id').val() ?
                    $('.crm-campaign-survey-main-form-block-campaign_id #s2id_campaign_id .select2-chosen').text() : null;

                if (campaign) {
                    aiParams.campaign = campaign;
                }
            }
            else if (generateType === 'campaign-goals' || generateType === 'campaign-description') {
                requireParams = '"Title", "Campaign Type" ';

                aiParams = {
                    ...aiParams,
                    title: $('.crm-campaign-form-block-title input[name="title"]').val(),
                    type:
                        $('.crm-campaign-form-block-campaign_type_id #campaign_type_id').val() ?
                            $('.crm-campaign-form-block-campaign_type_id #campaign_type_id :selected').text() : null,
                };
            }
            else if (generateType === 'mailing-plain-text' || generateType === 'mailing-html') {
                requireParams = '"Subject" ';

                aiParams = {
                    ...aiParams,
                    subject: $('input[name="subject"].crm-form-text').val(),
                };
            }
            else if (generateType === 'message-template-html' || generateType === 'message-template-plain-text') {
                requireParams = '"Message Title", "Message Subject" is optional ';

                aiParams = {
                    ...aiParams,
                    title: $('input[name="msg_title"].crm-form-text').val(),
                };

                let subject = $('input[name="msg_subject"].crm-form-text').val();

                if (subject) {
                    aiParams = {...aiParams, subject};
                }
            }
            else if (generateType === 'contact-mail-html' || generateType === 'contact-mail-plain-text') {
                requireParams = '"Message Subject"';

                aiParams = {
                    ...aiParams,
                    subject: $('input[name="subject"].crm-form-text').val(),
                };
            }

            let userInputAndData = [];

            if(userInputWindow.val()){
                const userInput = userInputWindow.val();
                const generatedData = resultWindow.html();
                const storedGeneratedData = {
                    role: 'assistant',
                    content: generatedData
                };

                const userRequest = {
                    role: 'user',
                    content: userInput
                };

                userInputAndData.push(storedGeneratedData, userRequest)
            }

            showLoading();

            let isObjectHasEmptyParams = Object.values(aiParams).some((x) => x === null || x === '');

            if (!isObjectHasEmptyParams) {
                descriptionPopup.show();

                if (!isResultWindowEmpty) {
                    if (generateType === 'event-description') {
                        CKEDITOR.instances['description'].setData('');
                    }
                    else if (generateType === 'petition-introduction') {
                        CKEDITOR.instances['instructions'].setData('');
                    }
                    else if (generateType === 'thank-you-message') {
                        CKEDITOR.instances['thankyou_text'].setData('');
                    }
                    else if (generateType === 'survey-instructions') {
                        CKEDITOR.instances['instructions'].setData('');
                    }
                    else if (generateType === 'campaign-goals') {
                        CKEDITOR.instances['goal_general'].setData('');
                    }
                    else if (generateType === 'campaign-description') {
                        $('#description').val('')
                    }
                    else if (generateType === 'mailing-html') {
                        CKEDITOR.instances['crmUiId_1'].setData('');
                    }
                    else if (generateType === 'mailing-plain-text') {
                        $('#crmUiId_2').val('')
                    }
                    else if (generateType === 'message-template-html') {
                        CKEDITOR.instances['msg_html'].setData('');
                    }
                    else if (generateType === 'message-template-plain-text') {
                        $('#msg_text').val('')
                    }
                    else if (generateType === 'contact-mail-html') {
                        CKEDITOR.instances['html_message'].setData('');
                    }
                    else if (generateType === 'contact-mail-plain-text') {
                        $('#text_message').val('')
                    }
                }

                CRM.api4('CiviMobileGenerateText', 'create', {
                    checkPermissions: false,
                    values: {
                        params: aiParams,
                        userInputAndData: userInputAndData,
                    },
                }).then(function (result) {
                    let generatedData = result?.[0]?.choices?.[0]?.message?.content;
                    if (generatedData) {
                        generatedData = generatedData.replace('```html', '');
                        generatedData = generatedData.replace('```', '');
                        resultWindow.html('<p class="generated-text-description">' + generatedData + '</p>');
                        isResultWindowEmpty = false;
                    } else {
                        clearFields();
                        descriptionPopup.hide();
                        CRM.alert({/literal}'{ts escape="js"}Invalid API key. Please check your key.{/ts}', '{ts escape="js"}Error{/ts}'{literal}, 'error');
                    }
                }, function () {
                    CRM.alert(ts('Something went wrong.'), ts('Text can\'t be generated'), 'error');
                });
            } else {
                CRM.alert(ts('Please, enter the following required fields: ' + requireParams), ts('Warning'), 'alert');
            }
        }

        $('#save-description').on('click', function () {
            const description = resultWindow.html();
            if (generateType === 'event-description') {
                CKEDITOR.instances['description'].insertHtml(description);
            }
            else if (generateType === 'petition-introduction') {
                CKEDITOR.instances['instructions'].insertHtml(description);
            }
            else if (generateType === 'thank-you-message') {
                CKEDITOR.instances['thankyou_text'].insertHtml(description);
            }
            else if (generateType === 'survey-instructions') {
                CKEDITOR.instances['instructions'].insertHtml(description);
            }
            else if (generateType === 'campaign-goals') {
                CKEDITOR.instances['goal_general'].insertHtml(description);
            }
            else if (generateType === 'campaign-description') {
                $('#description').val(resultWindow.text())
            }
            else if (generateType === 'mailing-html') {
                CKEDITOR.instances['crmUiId_1'].insertHtml(description);
            }
            else if (generateType === 'mailing-plain-text') {
                $('#crmUiId_2').val(resultWindow.text())
            }
            else if (generateType === 'message-template-html') {
                CKEDITOR.instances['msg_html'].insertHtml(description);
            }
            else if (generateType === 'message-template-plain-text') {
                $('#msg_text').val(resultWindow.text())
            }
            else if (generateType === 'contact-mail-html') {
                CKEDITOR.instances['html_message'].insertHtml(description);
            }
            else if (generateType === 'contact-mail-plain-text') {
                $('#text_message').val(resultWindow.text())
            }
            clearFields();
            hidePopup();
        });

        $('#generate-description-close-popup').on('click', function () {
            hidePopup();
        });

    });

</script>
{/literal}



