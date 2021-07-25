<?php

namespace Michaelr0\StatamicMultiCacher;

use Michaelr0\StatamicMultiCacher\Cachers\MultiCacher;

class CacheSelector
{
    /**
     * @var \Michaelr0\StatamicMultiCacher\Cachers\MultiCacher
     */
    protected $multiCacher;

    /**
     * @param \Michaelr0\StatamicMultiCacher\Cachers\MultiCacher $multiCacher
     */
    public function __construct(MultiCacher $multiCacher)
    {
        $this->multiCacher = $multiCacher;
    }

    /**
     * @return \Michaelr0\StatamicMultiCacher\Cachers\MultiCacher
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
