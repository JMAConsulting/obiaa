jQuery(document).ready(function ($) {
  const paymentBtns = document.querySelectorAll(".form-layout-compressed td");
  paymentBtns.forEach((btn) => {
    btn.style.display = "none";
  });
});
