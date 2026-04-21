<?php

namespace App\Infrastructure\Repository\Interface;

use App\Models\Links;

interface RepositoryInterface
{
    public function createShortedLink(string $original_url, string $short_url): Links;
    public function getRedirectedLink(string $short_url): ?Links;
}
