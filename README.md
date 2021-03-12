Acceptance bundle
=================

The acceptance bundle helps managing software acceptance.

---

What this bundle for ?
----------------------
Use this bundle to activate environment for a given period.

Sometimes, you want your customer to have acces to acceptance environment only for 3 or 7 days.


Getting Started
---------------
```
$ composer require atournayre/acceptance-bundle
```

Configuring
----------------------
Enable the bundle
```php
# config/bundles.php
return [
    // ...
    Atournayre\AcceptanceBundle\AtournayreAcceptanceBundle::class => ['all' => true],
    // ...
];
```

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
  atournayre_acceptance.is_enabled: '%env(ACCEPTANCE_IS_ENABLED)%'
  atournayre_acceptance.start_date_time: '%env(ACCEPTANCE_START_DATETIME)%'
  atournayre_acceptance.end_date_time: '%env(ACCEPTANCE_END_DATETIME)%'
```


Production
----------
In production, simply disable the bundle.

Using parameters.yml ?
```yaml
# app/config/parameters.yml
parameters:
  atournayre_acceptance.is_enabled: false
```

Using .env ?
```php
# .env / .env.local.php
return array (
  // ...
  'ACCEPTANCE_IS_ENABLED' => false,
  // ...
);
```

```yaml
# config/services.yaml
parameters:
  atournayre_acceptance.is_enabled: '%env(ACCEPTANCE_IS_ENABLED)%'
```

Overriding templates
---------------------

Using Symfony 4.4.* ?
```
$ mkdir -p templates/bundles/AtournayreAcceptanceBundle
$ cp -r vendor/atournayre/acceptance-bundle/Resources/views/. templates/bundles/AtournayreAcceptanceBundle
```
Templates are now ready for customization!