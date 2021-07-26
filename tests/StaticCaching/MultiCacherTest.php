<?php

namespace Michaelr0\StatamicMultiCacher\Tests\StaticCaching;

use Illuminate\Cache\Repository;
use Michaelr0\StatamicMultiCacher\Cachers\MultiCacher;
use Michaelr0\StatamicMultiCacher\Tests\TestCase;
use Statamic\StaticCaching\Cachers\ApplicationCacher;
use Statamic\StaticCaching\Cachers\FileCacher;
use Statamic\StaticCaching\Cachers\NullCacher;

class MultiCacherTest extends TestCase
{
    private function multiCacher($config = [])
    {
        return new MultiCacher(app(Repository::class), $config);
    }

    /** @test */
    public function it_is_instanceof_multi_cacher()
    {
        $multiCacher = $this->multiCacher();

        $this->assertTrue($multiCacher instanceof MultiCacher);
    }

    /** @test */
    public function it_has_instanceof_null_cacher()
    {
        $multiCacher = $this->multiCacher();

        $this->assertTrue($multiCacher->cachers()->has('null'));
        $this->assertTrue($multiCacher->cachers()->get('null') instanceof NullCacher);
    }

    /** @test */
    public function it_has_instanceof_application_cacher()
    {
        $multiCacher = $this->multiCacher([
            'strategies' => [
                'half',
            ],
        ]);

        $this->assertTrue($multiCacher->cachers()->has('half'));
        $this->assertTrue($multiCacher->cachers()->get('half') instanceof ApplicationCacher);
    }

    /** @test */
    public function it_has_instanceof_file_cacher()
    {
        $multiCacher = $this->multiCacher([
            'strategies' => [
                'full',
            ],
        ]);

        $this->assertTrue($multiCacher->cachers()->has('full'));
        $this->assertTrue($multiCacher->cachers()->get('full') instanceof FileCacher);
    }

    /** @test */
    public function it_has_many_cachers()
    {
        $multiCacher = $this->multiCacher([
            'strategies' => [
                'half',
                'full',
            ],
        ]);

        $this->assertTrue($multiCacher->cachers()->has('null'));
        $this->assertTrue($multiCacher->cachers()->get('null') instanceof NullCacher);

        $this->assertTrue($multiCacher->cachers()->has('half'));
        $this->assertTrue($multiCacher->cachers()->get('half') instanceof ApplicationCacher);

        $this->assertTrue($multiCacher->cachers()->has('full'));
        $this->assertTrue($multiCacher->cachers()->get('full') instanceof FileCacher);
    }
}
