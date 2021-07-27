<?php

namespace Michaelr0\StatamicMultiCacher;

use Illuminate\Contracts\Cache\Repository;
use Illuminate\Http\Request;
use Statamic\StaticCaching\Cachers\AbstractCacher;
use Statamic\StaticCaching\StaticCacheManager;

class MultiCacher extends AbstractCacher
{
    /**
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    /**
     * @var \Statamic\StaticCaching\Cachers\AbstractCacher|Statamic\StaticCaching\Cacher
     */
    protected $cacher;

    /**
     * @var \Illuminate\Support\Collection
     */
    protected $cachers;

    /**
     * @param \Illuminate\Contracts\Cache\Repository $cache
     */
    public function __construct(Repository $cache, $config)
    {
        parent::__construct($cache, $config);

        $cachers = [];

        $strategies = collect($this->config('strategies', []));

        if ($strategies->isEmpty()) {
            $strategies->push('null');
        }

        $strategies = $strategies->unique()->toArray();

        foreach ($strategies as $driver) {
            $cachers[$driver] = app(StaticCacheManager::class)->driver($driver);
        }

        $this->cachers = collect($cachers);

        $this->cacher = $this->cachers()->first();
    }

    /**
     * @return \Statamic\StaticCaching\Cachers\AbstractCacher|Statamic\StaticCaching\Cacher
     */
    public function cacher()
    {
        return $this->cacher;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function cachers()
    {
        return $this->cachers;
    }

    // AbstractCacher

    /**
     * Get the base URL (domain).
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->cacher()->getBaseUrl();
    }

    /**
     * @return int
     */
    public function getDefaultExpiration()
    {
        return $this->cacher()->getDefaultExpiration();
    }

    /**
     * Get the domains that have been cached.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getDomains()
    {
        return $this->cacher()->getDomains();
    }

    /**
     * Cache the current domain.
     *
     * @return void
     */
    public function cacheDomain()
    {
        $this->cacher()->cacheDomain();
    }

    /**
     * Get the URL from a request.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    public function getUrl(Request $request)
    {
        return $this->cacher()->getUrl($request);
    }

    /**
     * Flush all the cached URLs.
     *
     * @return void
     */
    public function flushUrls()
    {
        $this->cachers()->each->flushUrls();
    }

    /**
     * Save a URL to the cache.
     *
     * @param string $key
     * @param string $url
     * @return void
     */
    public function cacheUrl($key, $url)
    {
        $this->cachers()->each->cacheUrl($key, $url);
    }

    /**
     * Forget / remove a URL from the cache by its key.
     *
     * @param string $key
     * @return void
     */
    public function forgetUrl($key)
    {
        $this->cachers()->each->forgetUrl($key);
    }

    /**
     * Determine if a given URL should be excluded from caching.
     *
     * @param string $url
     * @return bool
     */
    public function isExcluded($url)
    {
        return $this->cacher()->isExcluded($url);
    }

    // Cacher

    /**
     * Cache a page.
     *
     * @param \Illuminate\Http\Request $request     Request associated with the page to be cached
     * @param string                   $content     The response content to be cached
     */
    public function cachePage(Request $request, $content)
    {
        return $this->cachers()->map->cachePage($request, $content)->first();
    }

    /**
     * Check if a page has been cached.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    public function hasCachedPage(Request $request)
    {
        return $this->cacher()->hasCachedPage($request);
    }

    /**
     * Get a cached page.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    public function getCachedPage(Request $request)
    {
        return $this->cacher()->getCachedPage($request);
    }

    /**
     * Flush out the entire static cache.
     *
     * @return void
     */
    public function flush()
    {
        $this->cachers()->each->flush();
    }

    /**
     * Invalidate a URL.
     *
     * @param string $url
     * @return void
     */
    public function invalidateUrl($url)
    {
        $this->cachers()->each->invalidateUrl($url);
    }

    /**
     * Invalidate multiple URLs.
     *
     * @param array $urls
     * @return void
     */
    public function invalidateUrls($urls)
    {
        $this->cachers()->each->invalidateUrls($urls);
    }

    /**
     * Get all the URLs that have been cached.
     *
     * @param string|null $domain
     * @return \Illuminate\Support\Collection
     */
    public function getUrls($domain = null)
    {
        return $this->cacher()->getUrls($domain);
    }
}
