// TODO: remove console.log()s and clean up code a bit
(function($) {
  $(document).ready(function() {
    // If user clicks the new property link, show hidden fields
    $(document).on("click", "#new-property", function(event) {
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

      hiddenFields.each(function() {
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
      unitDetailsRow.find(".acf-row").each(function() {
        var $row = $(this);
        var hiddenFields = $row.find(".acf-field.acf-hidden");

        // Loop through the hidden fields, remove the "acf-hidden" class, remove the "hidden" attribute, and show the field
        hiddenFields.each(function() {
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
    $(document).on("click", "#new-unit", function(event) {
      event.preventDefault();

      // Find the closest parent row with the class "acf-row"
      var $row = $(this).closest(".acf-row");

      // Loop through the hidden fields, remove the "acf-hidden" class, remove the "hidden" attribute, and show the field
      var hiddenFields = $row.find(".acf-field.acf-hidden");

      hiddenFields.each(function() {
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

    // Prepopulate the property/unit data once
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get("bid") != null) {
      console.log("Found bid in url - filling default values");
      handlePropertyChanges($('select[name^="acf[field_669674ee2ea21]"][name*="[field_66967535e6284][field_669679f71b1b0]"]'));
    }
    else {
      console.log("No bid found - setting empty unit field");
      var unitFieldName =
        '[name*="[field_66967511a2d57]"]';
      var unitField = $("select" + unitFieldName + '[name*="[field_66968109025e6]"]');
      unitField.empty();
      unitField.append(
        $("<option>", {
          value: "",
          text: "Enter unit/suite number",
          selected: true,
        })
      );
    }

    // Listen for changes on property address fields
    $(document).on(
      "change",
      'select[name^="acf[field_669674ee2ea21]"][name*="[field_66967535e6284][field_669679f71b1b0]"]',
      function() {
        handlePropertyChanges($(this));
      }
    );

    function handlePropertyChanges(propertyField) {
      console.log("Handling property changes...");
      // Find the corresponding unit address field
      console.log(propertyField.attr("name"));
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
    // Function to get unit options based on the selected property
    function populatePropertyFields(propertyFieldName, propertyField) {
      console.log("Populating property fields...");
      var property_id = propertyField.val();

      $.ajax({
        url: acf_ajax_object.ajax_url,
        type: "post",
        data: {
          action: "get_property_fields",
          property_id: property_id,
          security: acf_ajax_object.security,
        },
        success: function(response) {
          if (response.success) {
            $(propertyFieldName[0]).val(response.data.roll_no);
            $(propertyFieldName[1]).val(response.data.city);
            $(propertyFieldName[2]).val(response.data.postal_code);
          } else {
            console.error(response.data);
          }
        },
        error: function(xhr, status, error) {
          console.error(xhr.responseText);
        },
      });
    }

    // Function to get unit options based on the selected property and set values based on selected unit
    function getUnitsByProperty(propertyField, unitFieldName) {
      console.log("Getting units by property...");
      var property_id = propertyField.val();
      console.log("Property ID: " + property_id);

      $.ajax({
        url: acf_ajax_object.ajax_url,
        type: "post",
        data: {
          action: "get_units_by_property",
          property_id: property_id,
          security: acf_ajax_object.security,
        },
        success: function(response) {
          if (response.success) {
            console.log("Got response containing units");
            var unitFields = $("select" + unitFieldName + '[name*="[field_66968109025e6]"]');
            console.log("Number of units to add: ");
            console.log(unitFields.length);
            var selectedUnitIds = [];
            unitFields.each(function(i) {
              selectedUnitIds.push($(this).val());
            });
            var selectedUnitId = unitFields.val();
            console.log(selectedUnitIds);
            unitFields.empty();

            newAddress = $(unitFieldName + '[name*="[field_66a4007826665]"]');

            var firstKey = Object.keys(response.data)[0];
            var defaultAddress = response.data[firstKey].default_address;

            newAddress.val(defaultAddress);

            var unitData = {};

            // Add a null default option
            unitFields.append(
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

            $.each(response.data, function(id, unit) {
              unitFields.append(
                $("<option>", {
                  value: id,
                  text: unit.label,
                })
              );
              unitData[id] = unit;
            });
            console.log("Filling unit with default value");
            if (urlParams.get('bid') != null) {
              unitFields.each(function(i) {
                $(this).val(selectedUnitIds[i]);
                prefillUnit(selectedUnitIds[i], unitFieldName, unitData, $(this));
              });
            }

            // If unit_address changes
            unitFields.off(
              "change",
              unitFieldName + '[name*="[field_66968109025e6]"]'
            );
            unitFields.on(
              "change",
              // unitFieldName + '[name*="[field_66968109025e6]"]',
              function() {
                console.log("Selected unit changed!");
                prefillUnit($(this).val(), unitFieldName, unitData, $(this));
              }
            );
          } else {
            console.error("Error in response:", response.data);
          }
        },
        error: function(xhr, status, error) {
          console.error(xhr.responseText);
        },
      });
    }

    // Function to fill in the details about a unit
    function prefillUnit(selectedUnitId, unitFieldName, unitData, unitField) {
      // var selectedUnitId = unitField.val();
      if (unitData[selectedUnitId] === undefined) {
        // selected unit does not correspond with selected property
        selectedUnitId = "";
      }
      console.log("Selected unit ID: " + selectedUnitId);

      if (/^\d*$/.test(selectedUnitId)) {
        var selectedUnit = unitData[selectedUnitId];
        // unitField.val(selectedUnitId);
        console.log(unitData);
        var nameAttr = unitField.attr("name");
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

    // Fill in the subcategories once
    if (urlParams.get("bid") != null) {
      console.log("Found bid in url - filling default categories");
      var selectedCategories = $("#acf-field_6695732eea221-field_6695739bea224").val();
      populateSubCategories(selectedCategories);
    }

    // Listen to changes to category field
    $("#acf-field_6695732eea221-field_6695739bea224").on("change", function() {
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
        success: function(response) {
          // Check if response is successful
          if (response.success) {
            var subCategories = response.data;

            var selectElement = $(
              "#acf-field_6695732eea221-field_669573c0ea225"
            );

            // Ensure all options are initially visible
            selectElement.find("option").show();

            // Loop through the options and hide those not in subCategories
            selectElement.find("option").each(function() {
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
        error: function(xhr, status, error) {
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

    $(document).on("focus", 'input[type="url"]', function() {
      if ($(this).val() === "" || $(this).val() === "https://") {
        $(this).val("https://");
      }
    });

    $(document).on("blur", 'input[type="url"]', function() {
      if ($(this).val() === "https://") {
        $(this).val("");
      }
    });

    $(document).on(
      "click",
      ".acf-button.acf-repeater-add-row.button",
      function(event) {
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

          unitDetailsRow.find(".acf-row").each(function() {
            var $row = $(this);
            // Find the hidden input and select fields within the same row
            var hiddenFields = $row.find(".acf-field.acf-hidden");

            // Loop through the hidden fields, remove the "acf-hidden" class, remove the "hidden" attribute, and show the field
            hiddenFields.each(function() {
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

            $row.find("[name*='field_66a4007826665']").each(function() {
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

