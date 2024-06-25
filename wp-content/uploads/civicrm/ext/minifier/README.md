# minifier

If enabled, this extension minifies CSS/JS files which are passed through the assetbuilder in CiviCRM.

For example if it is installed on a CiviCRM system it will minify crm-menubar.css. If Stripe is installed it will minify stripe css/js.

The extension is licensed under [AGPL-3.0](LICENSE.txt).

## Installation

See: https://docs.civicrm.org/sysadmin/en/latest/customize/extensions/#installing-a-new-extension

## Usage

The extension calls hook_civicrm_buildAsset after all other buildAsset hooks have been called.

The extension works in two ways:
1. If the `$content` variable is not empty it will minify and return that (this would normally happen when an extension has implemented hook_civicrm_buildAsset for a specific file).
1. If the `$content` variable is empty and `$params['path']` specifies a full path to a file it will be loaded and minified.

## Release Notes

### 1.3

* PHP8.2 / Smarty3 compatibility (re-run civix).

### 1.2

* Don't minify already minified assets (if they are named asset.min.js).

### 1.1

* Fix [#4](https://lab.civicrm.org/extensions/minifier/-/issues/4) Minifier conflicts with Afform.

### 1.0 (was 0.2)

* Bug fix: Don't minify angular assets

### 0.1

* Initial Release
