<?php

namespace App\UseCases;

use App\Infrastructure\Repository\Interface\CacheInterface;
use App\Infrastructure\Repository\Interface\RepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetOriginalUrl
{
    public function __construct(protected RepositoryInterface $links, protected CacheInterface $cache) {} // dependency injection

    public function get(string $custom_alias)
    {

        $cachedCustomAlias = $this->cache->getCachedUrl($custom_alias);

        if ($cachedCustomAlias) {
            $this->cache->setCacheUrl($cachedCustomAlias, $custom_alias); // Refresh cache TTL
            $objectResponse = (object) ['original_url' => $cachedCustomAlias]; // Create an object to match the expected return type
            return $objectResponse;
        }

        $queryData = $this->links->getOriginalUrl($custom_alias); // hold original url

        if (!$queryData) {
            throw new ModelNotFoundException;
        }

        $this->cache->setCacheUrl($queryData->original_url, $custom_alias);

        return $queryData;
    }
};
