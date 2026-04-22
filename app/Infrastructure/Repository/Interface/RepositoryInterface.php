<?php

namespace App\Infrastructure\Repository\Interface;

use App\Models\Urls;

interface RepositoryInterface
{
    public function createShortedLink(string $original_url, string $short_url): Urls;
    public function getRedirectedLink(string $short_url): ?Urls;
}
