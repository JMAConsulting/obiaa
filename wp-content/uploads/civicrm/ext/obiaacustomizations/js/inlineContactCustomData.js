(function($) {
  var params = JSON.parse($('#custom-set-content-4').attr('data-edit-params'));
  var categoryField = $('#custom_7_' + params['customRecId']);
  var subCategoryField = $('#custom_8_' + params['customRecId']);
  var option_group = subCategoryField.attr('data-option-edit-path').replace('civicrm/admin/options/', '');
  var initialCategoryValues = categoryField.val();
  if (initialCategoryValues !== null && initialCategoryValues.length > 0) {
    var originalSelectedSubCategories = [];
    var originalSubCategoryValues = $('#custom_8_' + params['customRecId']).val();
    console.log(originalSubCategoryValues);
    CRM.api3('OptionValue', 'get', {'option_group_id': option_group, 'description': {'IN': initialCategoryValues}, 'sequential': 1, 'options': {'limit': 0}}).then(function(result) {
      subCategoryField.select2().empty();
      for (var v = 0; v < result.values.length; v++) {
        var originalSelected = false;
        if (originalSubCategoryValues.length > 0 && originalSubCategoryValues.indexOf(result.values[v].value) !== -1) {
          originalSelected = true;
        }
	  console.log(originalSelected);
        var newOption = new Option(result.values[v].label, result.values[v].value, false, originalSelected);
        subCategoryField.append(newOption);
      }
    });
  }
  subCategoryField.trigger('change');
  categoryField.on('change', function() {
    var currentSelectedSubCategories = [];
    var subCategoryValues = $('#custom_8_' + params['customRecId']).select2('data');
    for (var s = 0; s < subCategoryValues.length; s++) {
       currentSelectedSubCategories.push(subCategoryValues[s].id);
    }
    var categoryValues = $(this).val();
    var optionValues = [];
    CRM.api3('OptionValue', 'get', {'option_group_id': option_group, 'description': {'IN': categoryValues}, 'sequential': 1, 'options': {'limit': 0}}).then(function(result) {
      subCategoryField.select2().empty();
      for (var i = 0; i < result.values.length; i++) {
        optionValues.push(result.values[i].value);
	var selected = false;
	if (currentSelectedSubCategories.length > 0 && currentSelectedSubCategories.indexOf(result.values[i].value) !== -1) {
           selected = true;
        }
        var newOption = new Option(result.values[i].label, result.values[i].value, false, selected);
        subCategoryField.append(newOption);
      }
      for (var t = 0; t < currentSelectedSubCategories.length; t++) {
        if (optionValues.indexOf(currentSelectedSubCategories[t]) === -1) {
          delete subCategoryValues[t];
        }
      }
      subCategoryField.val(currentSelectedSubCategories);
      subCategoryField.trigger('change');
    });
  });
})(CRM.$);
