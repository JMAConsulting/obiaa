# CiviCRM field conditions

![Screenshot](/images/screenshot.png)

Ever had a custom field whose possible values depended on the values of another field?

For example,

* if "Field A" has options "1,2,3,4",
* and "Field B" has options "x1, x2, x3, y1, y2, y3, z1, z2, z3"
* but x* can only be selected if FieldA = 1, y* can only be selected if FieldA  = 2, etc?

This extension may help.

In some ways, it is similar to how the state_province/country fields work, but for custom fields.

The extension is fairly stable, but still has a few rough edges.

Similar extensions:

* [fieldlookup](https://github.com/MegaphoneJon/fieldlookup)
* [cityselector](https://lab.civicrm.org/extensions/cityselector)
* [regionlookup](https://lab.civicrm.org/extensions/regionlookup)

## Usage

Enable the extension, then go to CiviCRM > Administer > Customize Data and Screens > Field Conditions.

* Create a new condition
* Then add each field that will be part of the condition (it can be two or more fields).
* Then go back to the condition, and enter a list of allowed values. You can to
  select one combination at the time. Rather tedious, but you can also load
  values using SQL (todo: add import support via advimport?).

Currently this has only been tested on backend CiviCRM forms (not public forms,
where currently it requires the 'access CiviCRM' permission because of limited
security validations).

The extension will automatically enable the field conditions on a form if it detects
that the fields are present.

## Known issues

* There may be some assuptions that field values are numeric. Ex: label = "Option A", value = 1. So if a custom field has text values (ex: value = option1), it might throw an error. This can be fixed, the validation code would need to check the type first.
* Avoid multi-select fields, except for these specific supported use-cases:
  * "Field A" is a single-value select, and "Field B" is a multi-value select.
  * "Field A" is multi-select, and "Field B" is multi-select. Then all FieldA options will always be available, so that multiple FieldA options can be selected (but FieldB will be filtered using FieldA selections).

# Support

Please post bug reports in the issue tracker of this project:  
https://lab.civicrm.org/extensions/fieldconditions/issues

While we do our best to provide volunteer support for this extension, please
consider financially contributing to support or development of this extension
if you can.

Commercial support via Coop SymbioTIC:  
https://www.symbiotic.coop

# License

(C) 2017-2021 Mathieu Lutfy <mathieu@symbiotic.coop>  
(C) 2017-2021 Coop SymbioTIC <mathieu@symbiotic.coop>

Distributed under the terms of the GNU Affero General public license (AGPL).
See LICENSE.txt for details.
