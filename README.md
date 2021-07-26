[![Latest Version on Packagist](https://img.shields.io/packagist/v/michaelr0/statamic-multi-cacher.svg?style=flat-square)](https://packagist.org/packages/michaelr0/statamic-multi-cacher)
[![Total Downloads](https://img.shields.io/packagist/dt/michaelr0/statamic-multi-cacher.svg?style=flat-square)](https://packagist.org/packages/michaelr0/statamic-multi-cacher)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)
[![Run Tests](https://github.com/michaelr0/statamic-multi-cacher/actions/workflows/tests.yml/badge.svg)](https://github.com/michaelr0/statamic-multi-cacher/actions/workflows/tests.yml)
![Statamic v3.1](https://img.shields.io/badge/Statamic-3.1+-FF269E?style=flat-square)

Statamic Multi Cacher is a caching strategy "redirector" of sorts, it can be used to provide different caching strategies based on your own logic.

An example of this could be to bypass/disable the cache for super admins and serve the `half` strategy to everyone else.

## THIS IS A BETA
Please be aware that it is not recommended to use this in production just yet.

## Requirements
* Statamic 3.1 or higher

## Installation

You can install the package via composer:

```bash
composer require michaelr0/statamic-multi-cacher
```

## Usage

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

    'multi' => [
        'driver' => 'multi-cacher',
    ],
],
```

Then specify the name of the strategies that you want to be available to the `multi-cacher`
```php
'multi' => [
    'driver' => 'multi-cacher',
    'strategies' => [
        'half',
        'full',
    ],
],
```

Then update the `static_cache` strategy at the top of the configuration to:
```php
'strategy' => 'multi',
```

It is important to note, that if strategies are omitted or are empty, then the `multi-cacher` strategy will default to `null`.
The `null` strategy will always be available for selection, so you don't need to add it to your strategies section.

If you don't override the `CacheSelector` which is `\Michaelr0\StatamicMultiCacher\CacheSelector` then the first strategy will always be used, In the above example this would be `half`.

Overriding can be done by extending the `CacheSelector` class like so:
```php
<?php

namespace App\Cachers;

use Illuminate\Support\Facades\Auth;
use Michaelr0\StatamicMultiCacher\CacheSelector;

class MyMultiCacher extends CacheSelector
{
    public function selectCacher()
    {
        // Disable cache for super users.
        if(Auth::check() && Auth::user()->isSuper()){
            return $this->multiCacher()->cachers()->get('null');
        }

        // Cache everyone else with the half strategy.
        return $this->multiCacher()->cachers()->get('half');
    }
}
```

And then updating your `static_cache` configuration to be as follows:
```php
'multi' => [
    'driver' => 'multi-cacher',
    'selector' => \App\Cachers\MyMultiCacher::class,
    'strategies' => [
        'half',
    ],
],
```

## Changelog

Please see [CHANGELOG](https://github.com/michaelr0/statamic-multi-cacher/blob/main/CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/michaelr0/statamic-multi-cacher/blob/main/CONTRIBUTING.md) for details.

## Credits

- [Michael Rook](https://github.com/michaelr0)
- [All Contributors](https://github.com/michaelr0/statamic-multi-cacher/graphs/contributors)

## License

The MIT License (MIT). Please see [License File](https://github.com/michaelr0/statamic-multi-cacher/blob/main/LICENSE.md) for more information.