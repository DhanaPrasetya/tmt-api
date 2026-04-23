<?php

namespace App\Infrastructure\Repository\Interface;

use App\Models\Urls;

interface RepositoryInterface
{
    public function createShortedUrl(string $original_url, string $short_url): Urls;
    public function getOriginalUrl(string $short_url): ?Urls;
}
