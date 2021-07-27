[![Latest Version on Packagist](https://img.shields.io/packagist/v/michaelr0/statamic-multi-cacher.svg?style=flat-square)](https://packagist.org/packages/michaelr0/statamic-multi-cacher)
[![Total Downloads](https://img.shields.io/packagist/dt/michaelr0/statamic-multi-cacher.svg?style=flat-square)](https://packagist.org/packages/michaelr0/statamic-multi-cacher)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)
[![Run Tests](https://github.com/michaelr0/statamic-multi-cacher/actions/workflows/tests.yml/badge.svg)](https://github.com/michaelr0/statamic-multi-cacher/actions/workflows/tests.yml)
![Statamic v3.1](https://img.shields.io/badge/Statamic-3.1+-FF269E?style=flat-square)

Statamic Multi Cacher is a caching strategy that allows you to specify multiple caching strategies.

## Requirements
* Statamic 3.1 or higher

## Installation

You can install the package via composer:

```bash
composer require michaelr0/statamic-multi-cacher:dev-run-everything
```

## Usage

In this example we are going to pair statamic's [half measure](https://statamic.dev/static-caching#application-driver) and [Daynnnnn/statamic-cloudfront](https://github.com/Daynnnnn/statamic-cloudfront) together with the `half measure` serving as the primary application cacher and `cloudfront` as assistance.

First add the strategy to your `static_cache` config

```php
'strategies' => [

    'half' => [
        'driver' => 'application',
        'expiry' => null,
    ],

    'full' => [
        'driver' => 'file',
        'path' => public_path('static'),
        'lock_hold_length' => 0,
    ],

    /**
     * Cloudfront configuration might not always be accurate.
     * Check https://github.com/Daynnnnn/statamic-cloudfront
     */
    'cloudfront' => [
        'driver' => 'cloudfront',
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION'),
        'distribution' => env('CLOUDFRONT_DISTRIBUTION_ID'),
    ],

    'multi' => [
        'driver' => 'multi-cacher',
        'strategies' => [
            'half',
            'cloudfront',
        ],
    ],
],
```

If no strategies have been provided to the `multi-cacher` configuration, then the `multi-cacher` strategy will default to `null`.

**An important note is that the first strategy is the one that will be used by the following function calls**
* getBaseUrl
* getDefaultExpiration
* getDomains
* cacheDomain
* getUrl
* isExcluded
* hasCachedPage
* getCachedPage
* getUrls

**While the below functions will executed against every strategy**
* cachePage (The results of the first strategy will be returned)
* cacheUrl
* forgetUrl
* flush
* flushUrls
* invalidateUrl
* invalidateUrls

And finally update the `static_cache` strategy at the top of the configuration to:
```php
'strategy' => 'multi',
```

## Cachers for Statamic

- [Statamic half measure](https://statamic.dev/static-caching#application-driver)
- [Statamic full measure](https://statamic.dev/static-caching#file-driver)
- [Daynnnnn/statamic-cloudfront](https://github.com/Daynnnnn/statamic-cloudfront)

## Changelog

Please see [CHANGELOG](https://github.com/michaelr0/statamic-multi-cacher/blob/main/CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/michaelr0/statamic-multi-cacher/blob/main/CONTRIBUTING.md) for details.

## Credits

- [Michael Rook](https://github.com/michaelr0)
- [All Contributors](https://github.com/michaelr0/statamic-multi-cacher/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/michaelr0/statamic-multi-cacher/blob/main/LICENSE.md) for more information.