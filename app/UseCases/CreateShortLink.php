<?php

namespace App\UseCases;

use App\Repositories\Interfaces\RepositoryInterface;

class CreateShortLink
{
    public function __construct(protected RepositoryInterface $links) {} // dependency injection

    public function create(string $original_url, string $short_url)
    {
        $link = $this->links->createShortedLink(
            $original_url,
            $short_url
        );

        return $link;
    }
}
