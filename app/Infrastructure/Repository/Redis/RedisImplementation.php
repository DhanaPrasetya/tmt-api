<?php

namespace App\Infrastructure\Repository\Redis;

use App\Infrastructure\Repository\Interface\CacheInterface;
use Illuminate\Support\Facades\Redis;

class RedisImplementation implements CacheInterface
{
    public function setCacheUrl(string $original_url, string $custom_alias): void
    {
        $ttl = 10 * 60; // Cache for 10 minutes
        Redis::connection('default')->set("ca:{$custom_alias}", $original_url, 'EX', $ttl);
    }

    public function getCachedUrl(string $custom_alias): string
    {
        $cachedData = Redis::connection('default')->get("ca:{$custom_alias}");

        if (!$cachedData) {
            return '';
        }

        return $cachedData;
    }

    public function flushAllCache(): void
    {
        Redis::connection('default')->flushdb();
    }
}
