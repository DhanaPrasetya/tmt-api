<?php

namespace App\Infrastructure\Repository\Interface;

use App\Models\Urls;

interface RepositoryInterface
{
    public function createShortedUrl(string $original_url, string $custom_alias): Urls;
    public function getOriginalUrl(string $custom_alias): ?Urls;
}
