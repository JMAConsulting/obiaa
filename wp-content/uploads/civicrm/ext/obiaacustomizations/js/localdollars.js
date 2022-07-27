// Customize Button Text
const paymentBtn = document.querySelector("#_qf_Main_upload-bottom");

paymentBtn.innerHTML = `<i aria-hidden="true" class="crm-i fa-chevron-right"></i> Review your purchase`;

CRM.$(function($) {
    $( document ).ajaxComplete(function() {
        $('label[for="billing_state_province_id-5"]').text('Province/State');
    });
});
