<?php

namespace App\Infrastructure\Repository\Eloquent;

use App\Models\Urls;
use App\Infrastructure\Repository\Interface\RepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;

class EloquentImplementation implements RepositoryInterface
{
    public function createShortedUrl(string $original_url, string $custom_alias): Urls
    {
        try {
            return Urls::create([
                'original_url' => $original_url,
                'custom_alias' => $custom_alias
            ]);
        } catch (UniqueConstraintViolationException $e) {
            throw $e;
        }
    }

    public function getOriginalUrl(string $custom_alias): ?Urls
    {
        try {
            return Urls::where('custom_alias', $custom_alias)->first(['original_url']);
        } catch (QueryException $e) {
            throw $e;
        }
    }
}
