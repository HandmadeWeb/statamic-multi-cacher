<?php

namespace HandmadeWeb\StatamicMultiCacher;

use HandmadeWeb\StatamicMultiCacher\Cachers\MultiCacher;

class CacheSelector
{
    /**
     * @var \HandmadeWeb\StatamicMultiCacher\Cachers\MultiCacher
     */
    protected $multiCacher;

    /**
     * @param \HandmadeWeb\StatamicMultiCacher\Cachers\MultiCacher $multiCacher
     */
    public function __construct(MultiCacher $multiCacher)
    {
        $this->multiCacher = $multiCacher;
    }

    /**
     * @return \HandmadeWeb\StatamicMultiCacher\Cachers\MultiCacher
     */
    public function multiCacher()
    {
        return $this->multiCacher;
    }

    /**
     * Process logic and return the desired cacher.
     *
     * @return \Statamic\StaticCaching\Cachers\AbstractCacher|Statamic\StaticCaching\Cacher
     */
    public function selectCacher()
    {
        return $this->multiCacher()->cachers()->first();
    }
}
