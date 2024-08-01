(function ($) {
  $(document).ready(function () {
    // If user clicks the new property link, show hidden fields
    $(document).on("click", "#new-property", function (event) {
      event.preventDefault();

      // Find the closest parent row with the class "acf-row"
      var $row = $(this).closest(".acf-row");

      // Show hidden fields in the property section
      $row
        .find(
          ".acf-field-669679ed1b1af, .acf-field-66967a0b1b1b2, .acf-field-66967a011b1b1"
        )
        .show();

      // Loop through the hidden fields, removing "acf-hidden" class, "hidden" attribute, and show the field
      var hiddenFields = $row.find(".acf-field.acf-hidden");

      hiddenFields.each(function () {
        $(this).removeClass("acf-hidden").removeAttr("hidden").show();

        // Re-enable the input and select fields within the hidden field
        $(this).find("input, select").prop("disabled", false);
      });

      // Hide original address select field
      $row.find(".acf-field-669679f71b1b0").hide();

      // Reset property fields to be blank
      $row.find("[name*='field_669679ed1b1af']").val("");
      $row.find("[name*='field_66967a011b1b1']").val("");
      $row.find("[name*='field_66967a0b1b1b2']").val("");

      // Find and check is_new_property checkbox within the same row
      var $checkbox = $row.find(
        "input[type='checkbox'][name*='field_66a7cf3944bf8']"
      );
      $checkbox.prop("checked", true);

      var unitDetailsRow = $row.find('[data-name="unit_details"]');
      if (unitDetailsRow.length === 0) {
        return;
      }

      // Change all existing units to new units
      unitDetailsRow.find(".acf-row").each(function () {
        var $row = $(this);
        var hiddenFields = $row.find(".acf-field.acf-hidden");

        // Loop through the hidden fields, remove the "acf-hidden" class, remove the "hidden" attribute, and show the field
        hiddenFields.each(function () {
          $(this).removeClass("acf-hidden").removeAttr("hidden").show();

          // Re-enable the input and select fields within the hidden field
          $(this).find("input, select").prop("disabled", false);
        });

        $row.find(".acf-field-66968109025e6").hide();
        $row.find(".acf-field-669678b28537a").hide();

        $row.find("[name*='field_66a4007826665']").val("");
        $row.find("[name*='field_66968111025e7']").val("");
        $row.find("[name*='field_66968111025e7']").val("");
        $row.find("[name*='field_6696811e025e8']").val("");
        $row.find("[name*='field_6696812c025e9']").val("");
        $row.find("[name*='field_66968138025ea']").val("");
        $row.find("[name*='field_66968146025eb']").val("");

        // Find and check the is_new_unit checkbox within the same row
        var $checkbox = $row.find(
          "input[type='checkbox'][name*='field_66a7cb3396664']"
        );
        $checkbox.prop("checked", true);
      });
    });

    // If user clicks the new unit link, show hidden fields
    $(document).on("click", "#new-unit", function (event) {
      event.preventDefault();

      // Find the closest parent row with the class "acf-row"
      var $row = $(this).closest(".acf-row");

      // Loop through the hidden fields, remove the "acf-hidden" class, remove the "hidden" attribute, and show the field
      var hiddenFields = $row.find(".acf-field.acf-hidden");

      hiddenFields.each(function () {
        $(this).removeClass("acf-hidden").removeAttr("hidden").show();

        // Re-enable the input and select fields within the hidden field
        $(this).find("input, select").prop("disabled", false);
      });

      $row.find(".acf-field-66968109025e6").hide();
      $row.find(".acf-field-669678b28537a").hide();

      $row.find("[name*='field_66968111025e7']").val("");
      $row.find("[name*='field_66968111025e7']").val("");
      $row.find("[name*='field_6696811e025e8']").val("");
      $row.find("[name*='field_6696812c025e9']").val("");
      $row.find("[name*='field_66968138025ea']").val("");
      $row.find("[name*='field_66968146025eb']").val("");

      // Find and check the checkbox within the same row
      var $checkbox = $row.find(
        "input[type='checkbox'][name*='field_66a7cb3396664']"
      );
      $checkbox.prop("checked", true);
    });

    // Listen for changes on property address fields
    $(document).on(
      "change",
      'select[name^="acf[field_669674ee2ea21]"][name*="[field_66967535e6284][field_669679f71b1b0]"]',
      function () {
        var propertyField = $(this);
        // Find the corresponding unit address field
        var namePrefix = propertyField
          .attr("name")
          .match(/acf\[field_669674ee2ea21\]\[(.*?)\]/)[0];

        var propertyFieldNames = [
          '[name^="' + namePrefix + '"][name*="[field_669679ed1b1af]"]',
          '[name^="' + namePrefix + '"][name*="[field_66967a011b1b1]"]',
          '[name^="' + namePrefix + '"][name*="[field_66967a0b1b1b2]"]',
        ];

        populatePropertyFields(propertyFieldNames, propertyField);

        var unitFieldName =
          '[name^="' + namePrefix + '"][name*="[field_66967511a2d57]"]';
        getUnitsByProperty(propertyField, unitFieldName);
      }
    );

    // Function to get unit options based on the selected property
    function populatePropertyFields(propertyFieldName, propertyField) {
      var property_id = propertyField.val();

      $.ajax({
        url: acf_ajax_object.ajax_url,
        type: "post",
        data: {
          action: "get_property_fields",
          property_id: property_id,
          security: acf_ajax_object.security,
        },
        success: function (response) {
          if (response.success) {
            $(propertyFieldName[0]).val(response.data.roll_no);
            $(propertyFieldName[1]).val(response.data.city);
            $(propertyFieldName[2]).val(response.data.postal_code);
          } else {
            console.error(response.data);
          }
        },
        error: function (xhr, status, error) {
          console.error(xhr.responseText);
        },
      });
    }

    // Function to get unit options based on the selected property
    function getUnitsByProperty(propertyField, unitFieldName) {
      var property_id = propertyField.val();

      $.ajax({
        url: acf_ajax_object.ajax_url,
        type: "post",
        data: {
          action: "get_units_by_property",
          property_id: property_id,
          security: acf_ajax_object.security,
        },
        success: function (response) {
          if (response.success) {
            unitField = $(unitFieldName + '[name*="[field_66968109025e6]"]');
            unitField.empty();

            newAddress = $(unitFieldName + '[name*="[field_66a4007826665]"]');

            var firstKey = Object.keys(response.data)[0];
            var defaultAddress = response.data[firstKey].default_address;

            newAddress.val(defaultAddress);

            var unitData = {};

            // Add a null default option
            unitField.append(
              $("<option>", {
                value: "",
                text: "Enter unit/suite number",
                selected: true,
              })
            );

            unitData[""] = {
              unit_size: "",
              unit_price: "",
              unit_location: "",
              unit_suite: "",
              mls_listing_link: "",
              unit_status: "4",
            };

            $.each(response.data, function (id, unit) {
              unitField.append(
                $("<option>", {
                  value: id,
                  text: unit.label,
                })
              );
              unitData[id] = unit;
            });

            // If unit_address changes
            $(document).off(
              "change",
              unitFieldName + '[name*="[field_66968109025e6]"]'
            );
            $(document).on(
              "change",
              unitFieldName + '[name*="[field_66968109025e6]"]',
              function () {
                var selectedUnitId = $(this).val();

                if (/^\d+$/.test(selectedUnitId)) {
                  var selectedUnit = unitData[selectedUnitId];
                  var nameAttr = $(this).attr("name");

                  var uniqueIdPartMatch = nameAttr.match(
                    /\[([\w-]+)\]\[field_66968109025e6\]$/
                  );

                  // Check if the uniqueIdPartMatch is not null
                  if (uniqueIdPartMatch) {
                    var uniqueIdPart = uniqueIdPartMatch[1];

                    // Prepopulate other fields
                    $(
                      unitFieldName +
                        '[name*="[' +
                        uniqueIdPart +
                        ']"]' +
                        '[name*="[field_6696811e025e8]"]'
                    ).val(selectedUnit.unit_size || "");
                    $(
                      unitFieldName +
                        '[name*="[' +
                        uniqueIdPartMatch[1] +
                        ']"]' +
                        '[name*="[field_6696812c025e9]"]'
                    ).val(selectedUnit.unit_price || "");
                    $(
                      unitFieldName +
                        '[name*="[' +
                        uniqueIdPartMatch[1] +
                        ']"]' +
                        '[name*="[field_669678b28537a]"]'
                    )
                      .val(selectedUnit.unit_status || "4")
                      .trigger("change");
                    $(
                      unitFieldName +
                        '[name*="[' +
                        uniqueIdPartMatch[1] +
                        ']"]' +
                        '[name*="[field_66968138025ea]"]'
                    ).val(selectedUnit.mls_listing_link || "");
                    $(
                      unitFieldName +
                        '[name*="[' +
                        uniqueIdPartMatch[1] +
                        ']"]' +
                        '[name*="[field_66968146025eb]"]'
                    ).val(selectedUnit.unit_location || "");
                    $(
                      unitFieldName +
                        '[name*="[' +
                        uniqueIdPartMatch[1] +
                        ']"]' +
                        '[name*="[field_66968111025e7]"]'
                    ).val(selectedUnit.unit_suite || "");
                  }
                }
              }
            );
          } else {
            console.error("Error in response:", response.data);
          }
        },
        error: function (xhr, status, error) {
          console.error(xhr.responseText);
        },
      });
    }

    // Listen to changes to category field
    $("#acf-field_6695732eea221-field_6695739bea224").on("change", function () {
      var selectedValues = $(this).val();

      // Update the subcategory options
      populateSubCategories(selectedValues);
    });

    function populateSubCategories(selectedValues) {
      // Make the AJAX request
      $.ajax({
        url: acf_ajax_object.ajax_url,
        type: "post",
        data: {
          action: "get_sub_categories",
          categories: selectedValues,
          security: acf_ajax_object.security,
        },
        success: function (response) {
          // Check if response is successful
          if (response.success) {
            var subCategories = response.data;

            var selectElement = $(
              "#acf-field_6695732eea221-field_669573c0ea225"
            );

            // Ensure all options are initially visible
            selectElement.find("option").show();

            // Loop through the options and hide those not in subCategories
            selectElement.find("option").each(function () {
              var optionValue = $(this).val();

              if (subCategories.includes(optionValue)) {
                $(this).show();
              } else {
                $(this).hide();
              }
            });

            // Handle empty results (optional)
            if (selectElement.find("option:visible").length === 0) {
              console.log("No options are visible.");
            }
          } else {
            console.log("Error fetching subcategories:", response.data);
          }
        },
        error: function (xhr, status, error) {
          console.log("AJAX Error:", status, error);
        },
      });
    }
    // Function to hide elements with the data-name "is_new_property"
    function hideNewPropertyFields() {
      $('[data-name="is_new_property"]').hide();
      $('[data-name="is_new_unit"]').hide();
    }

    const observer = new MutationObserver((mutationsList) => {
      for (const mutation of mutationsList) {
        if (mutation.type === "childList") {
          // Hide new elements with the data-name "is_new_property" that were added to the DOM
          hideNewPropertyFields();
        }
      }
    });

    observer.observe(document.body, { childList: true, subtree: true });

    // Initial call to hide any elements that are already in the DOM
    hideNewPropertyFields();

    $('input[type="url"]').on("focus", function () {
      if ($(this).val() === "" || $(this).val() === "https://") {
        $(this).val("https://");
      }
    });

    $('input[type="url"]').on("blur", function () {
      if ($(this).val() === "https://") {
        $(this).val("");
      }
    });

    $(document).on(
      "click",
      ".acf-button.acf-repeater-add-row.button",
      function (event) {
        event.preventDefault();

        if ($(this).text() !== "+ Add Unit for Property") {
          return;
        }

        // Find the property details row
        var propertyDetailsRow = $(this)
          .closest(".acf-fields")
          .find('[data-name="property_details"]');

        if (propertyDetailsRow.length === 0) {
          return;
        }

        // Get the checkbox field value
        var checkboxField = propertyDetailsRow.find(
          '[data-name="is_new_property"] input[type="checkbox"]'
        );

        // Get the checkbox field value
        var defaultAddress = propertyDetailsRow
          .find("[name*='field_66a3f9f05f9bb']")
          .val();

        if (checkboxField.is(":checked")) {
          var unitDetailsRow = propertyDetailsRow
            .closest(".acf-fields")
            .find('[data-name="unit_details"]'); // Adjust the selector if needed

          if (unitDetailsRow.length === 0) {
            return;
          }

          unitDetailsRow.find(".acf-row").each(function () {
            var $row = $(this);
            // Find the hidden input and select fields within the same row
            var hiddenFields = $row.find(".acf-field.acf-hidden");

            // Loop through the hidden fields, remove the "acf-hidden" class, remove the "hidden" attribute, and show the field
            hiddenFields.each(function () {
              $(this).removeClass("acf-hidden").removeAttr("hidden").show();

              // Re-enable the input and select fields within the hidden field
              $(this).find("input, select").prop("disabled", false);
            });

            $row.find(".acf-field-66968109025e6").hide();
            $row.find(".acf-field-669678b28537a").hide();

            // Find and check the checkbox within the same row
            var $checkbox = $row.find(
              "input[type='checkbox'][name*='field_66a7cb3396664']"
            );
            $checkbox.prop("checked", true);

            $row.find("[name*='field_66a4007826665']").each(function () {
              if ($(this).val().trim() === "") {
                $(this).val(defaultAddress);
              }
            });
          });
        }
      }
    );
  });
})(jQuery);
