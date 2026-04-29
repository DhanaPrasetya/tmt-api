<?php

namespace App\Infrastructure\Repository\Interface;

interface CacheInterface
{
    public function setCacheUrl(string $original_url, string $short_url): void;
    public function getCachedUrl(string $short_url): string;
    public function flushAllCache(): void;
}
