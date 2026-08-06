# API Key

CiviCRM Extension that provides an interface for managing CiviCRM API user keys.

Once installed, an 'API Key' tab will be available on contact screens, if you
have to correct permissions you'll be able to edit/generate and/or view the
site and contact API Keys.

## Documentation

https://docs.civicrm.org/sysadmin/en/latest/setup/api-keys/

## Install using composer

1. Define the `installer-paths` in `composer.json` according to [this CiviCRM post](https://civicrm.org/blog/bradleyt/composer-installers-and-civicrm).

2. To `composer.json` add the repository definition:
```
"repositories": [
  {
    "type": "vcs",
    "url": "https://lab.civicrm.org/extensions/apikey.git"
  }
]
```

3. Then install using `composer require civicrm/apikey`
