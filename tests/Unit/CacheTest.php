<?php

namespace Tests\Unit;

use App\Infrastructure\Repository\Redis\RedisImplementation;
use Tests\TestCase;

class CacheTest extends TestCase
{

    public function test_set_and_get_cache_url(): void
    {
        $cache = new RedisImplementation();

        $originalUrl = 'https://url1.com';
        $custom_alias = 'thisIsShortUrlCacheTest';

        // Set cache
        $cache->setCacheUrl($originalUrl, $custom_alias);

        // Get cache
        $cachedUrl = $cache->getCachedUrl($custom_alias);
        $this->assertEquals($originalUrl, $cachedUrl);

        $cache->flushAllCache(); // Clear cache after test
    }
}
