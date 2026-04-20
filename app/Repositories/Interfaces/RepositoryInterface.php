<?php

namespace App\Repositories\Interfaces;

use App\Models\Links;

interface RepositoryInterface
{
    public function createShortedLink(string $original_url, string $short_url): Links;
    public function getRedirectedLink(string $short_url): ?Links;
}
