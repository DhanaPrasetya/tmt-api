<?php

namespace App\UseCases;

use App\Infrastructure\Repository\Interface\RepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GetOriginalUrl
{
    public function __construct(protected RepositoryInterface $links) {} // dependency injection

    public function get(string $short_url)
    {
        $queryData = $this->links->getOriginalUrl($short_url);

        if (!$queryData) {
            throw new ModelNotFoundException;
        }

        return $queryData;
    }
};
