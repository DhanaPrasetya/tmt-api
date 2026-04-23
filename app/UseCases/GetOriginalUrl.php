<?php

namespace App\UseCases;

use App\Infrastructure\Repository\Interface\RepositoryInterface;

class GetOriginalUrl
{
    public function __construct(protected RepositoryInterface $links) {} // dependency injection

    public function get(string $short_url)
    {
        return $this->links->getOriginalUrl($short_url);
    }
};
