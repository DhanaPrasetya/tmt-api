<?php

namespace App\Infrastructure\Repository\Interface;

interface CacheInterface
{
    public function setCacheUrl(string $original_url, string $custom_alias): void;
    public function getCachedUrl(string $custom_alias): string;
    public function flushAllCache(): void;
}
