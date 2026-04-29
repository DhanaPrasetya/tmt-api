<?php

namespace App\Infrastructure\Repository\Redis;

use App\Infrastructure\Repository\Interface\CacheInterface;
use Illuminate\Support\Facades\Redis;

class RedisImplementation implements CacheInterface
{
    public function setCacheUrl(string $original_url, string $short_url): void
    {
        $ttl = 10 * 60; // Cache for 10 minutes
        Redis::connection('default')->set("st:{$short_url}", $original_url, 'EX', $ttl);
    }

    public function getCachedUrl(string $short_url): string
    {
        $cachedData = Redis::connection('default')->get("st:{$short_url}");

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
