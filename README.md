Acceptance bundle
=================

The acceptance bundle helps managing software acceptance.

Getting Started
---------------
```
$ composer require atournayre/acceptance-bundle
```

Configuring parameters
----------------------
Using parameters.yml ?
```yaml
# app/config/parameters.yml
parameters:
  atournayre_acceptance.is_enabled: true # true to enable / false to disable
  atournayre_acceptance.start_date_time: "2021-01-01 00:00:00" # 2021-01-01 is also valid
  atournayre_acceptance.end_date_time: "2021-01-02 00:00:00" # 2021-01-02 is also valid
```

Using .env ?
```php
# .env / .env.local.php
return array (
  // ...
  // ACCEPTANCE_IS_ENABLED : true / false
  'ACCEPTANCE_IS_ENABLED' => true,
  // ACCEPTANCE_START_DATETIME : "2021-03-25 00:00:00" / "2021-03-25"
  'ACCEPTANCE_START_DATETIME' => "2021-03-25 00:00:00",
  // ACCEPTANCE_END_DATETIME : "2021-03-25 00:00:00" / "2021-03-25"
  'ACCEPTANCE_END_DATETIME' => "2021-02-29 23:59:59",
  // ...
);
```

```yaml
# config/services.yaml
parameters:
  # Using .env
  atournayre_acceptance.is_enabled: '%env(ACCEPTANCE_IS_ENABLED)%'
  atournayre_acceptance.start_date_time: '%env(ACCEPTANCE_START_DATETIME)%'
  atournayre_acceptance.end_date_time: '%env(ACCEPTANCE_END_DATETIME)%'
```


Overriding templates
---------------------

Using Symfony 4.4.* ?
```
$ mkdir -p templates/bundles/AtournayreAcceptanceBundle
$ cp -r vendor/atournayre/acceptance-bundle/Resources/views/. templates/bundles/AtournayreAcceptanceBundle
```
