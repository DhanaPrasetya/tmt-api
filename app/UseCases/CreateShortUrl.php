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

        $frontendUrl = env('FRONTEND_URL', 'http://localhost');

        $dataObjectStripped = [
            'short_url' => "{$frontendUrl}/{$dataObject->short_url}"
        ];

        return $dataObjectStripped;
    }
}
