<?php

namespace Michaelr0\StatamicMultiCacher;

use Illuminate\Cache\Repository as CacheRepository;
use Michaelr0\StatamicMultiCacher\Cachers\MultiCacher;
use Statamic\Providers\AddonServiceProvider;
use Statamic\StaticCaching\StaticCacheManager;

class ServiceProvider extends AddonServiceProvider
{
    public function boot()
    {
        parent::boot();

        app(StaticCacheManager::class)->extend('multi-cacher', function ($app, $config, $name) {
            return new MultiCacher($app[CacheRepository::class], $config);
        });
    }
}
