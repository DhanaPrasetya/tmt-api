<?php

namespace App\Infrastructure\Repository\Eloquent;

use App\Models\Urls;
use App\Infrastructure\Repository\Interface\RepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;

class EloquentImplementation implements RepositoryInterface
{
    public function createShortedLink(string $original_url, string $short_url): Urls
    {
        try {
            return Urls::create([
                'original_url' => $original_url,
                'short_url' => $short_url
            ]);
        } catch (UniqueConstraintViolationException $e) {

            throw $e;
        }
    }

    public function getRedirectedLink(string $short_url): ?Urls
    {
        try {
            return Urls::where('short_url', $short_url)->first();
        } catch (QueryException $e) {
            throw $e;
        }
    }
}
