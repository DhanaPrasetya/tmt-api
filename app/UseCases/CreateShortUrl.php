<?php

namespace App\UseCases;

use App\Infrastructure\Repository\Interface\RepositoryInterface;

class CreateShortUrl
{
    public function __construct(protected RepositoryInterface $links) {} // dependency injection

    public function create(string $original_url, string $custom_alias)
    {
        $dataObject = $this->links->createShortedUrl(
            $original_url,
            $custom_alias
        );

        $dataObjectStripped = [
            'custom_alias' => $dataObject->custom_alias
        ];

        return $dataObjectStripped;
    }
}
