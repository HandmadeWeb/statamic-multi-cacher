<?php

namespace HandmadeWeb\StatamicMultiCacher;

use HandmadeWeb\StatamicMultiCacher\Cachers\MultiCacher;
use Illuminate\Cache\Repository as CacheRepository;
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
