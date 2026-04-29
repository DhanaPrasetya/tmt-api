<?php

namespace App\UseCases;

use App\Infrastructure\Repository\Interface\CacheInterface;
use App\Infrastructure\Repository\Interface\RepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetOriginalUrl
{
    public function __construct(protected RepositoryInterface $links, protected CacheInterface $cache) {} // dependency injection

    public function get(string $short_url)
    {

        $cachedUrl = $this->cache->getCachedUrl($short_url);

        if ($cachedUrl) {
            $this->cache->setCacheUrl($cachedUrl, $short_url); // Refresh cache TTL
            $objectResponse = (object) ['original_url' => $cachedUrl]; // Create an object to match the expected return type
            return $objectResponse;
        }

        $queryData = $this->links->getOriginalUrl($short_url); // hold original url

        if (!$queryData) {
            throw new ModelNotFoundException;
        }

        $this->cache->setCacheUrl($queryData->original_url, $short_url);

        return $queryData;
    }
};
