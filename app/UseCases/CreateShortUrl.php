<?php

namespace App\UseCases;

use App\Infrastructure\Repository\Interface\RepositoryInterface;

class CreateShortUrl
{
    public function __construct(protected RepositoryInterface $links) {} // dependency injection

    public function create(string $original_url, string $short_url)
    {
        $dataObject = $this->links->createShortedUrl(
            $original_url,
            $short_url
        );

        $dataObjectStripped = [
            'short_url' => $dataObject->short_url
        ];

        return $dataObjectStripped;
    }
}
