# Google Analytics integration with Laravel Nova

## Installation

You can install the package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require panchania83/laravel-nova-google-analytics-4
```

Optionally, you can publish the config file of this package with this command:

``` bash
php artisan vendor:publish --tag="analytics-config"
```

The following config file will be published in `config/analytics.php`

```php
return [

    /*
     * The property id of which you want to display data.
     */
    'property_id' => env('PROPERTY_ID'),

    /*
     * Path to the client secret json file. Take a look at the README of this package
     * to learn how to get this file. You can also pass the credentials as an array
     * instead of a file path.
     */
    'service_account_credentials_json' => storage_path('app/analytics/service-account-credentials.json'),
];

```

Also add this to the `.env` for your Nova app:

```ini
ANALYTICS_PROPERTY_ID=
```


## Usage

Next up, you must register the card with Nova. This is typically done in the `cards` method of the `NovaServiceProvider`.

```php
// in app/Providers/NovaServiceProvider.php

// ...

public function cards()
{
    return [
        // ...
        new \Panchania83\LaravelNovaGoogleAnalytics4\VisitorsMetric,
        new \Panchania83\LaravelNovaGoogleAnalytics4\GoogleAnalyticsCard,
    ];
}
```


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
