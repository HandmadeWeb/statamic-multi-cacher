![Statamic v3.1](https://img.shields.io/badge/Statamic-3.1+-FF269E?style=flat-square)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE.md)

Statamic Multi Cacher is a caching strategy "redirector" of sorts, it can be used to provide different caching strategies based on your own logic.

An example of this could be to bypass/disable the cache for super admins and serve the `half` strategy to everyone else.

# Requirements
* PHP 8.0 or higher
* Statamic 3.1 or higher
* Laravel 8.0 or higher

# Installation

You can install the package via composer:

```bash
composer require michaelr0/statamic-multi-cacher
```

# Usage

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
        'null',
        'half',
    ],
],
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

- [Michael Rook](https://github.com/michaelr0)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.