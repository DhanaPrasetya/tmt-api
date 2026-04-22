<?php

namespace App\Infrastructure\Repository\Eloquent;

use App\Models\Links;
use App\Infrastructure\Repository\Interface\RepositoryInterface;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Log;


class EloquentImplementation implements RepositoryInterface
{
    public function createShortedLink(string $original_url, string $short_url): Links
    {
        try {
            return Links::create([
                'original_url' => $original_url,
                'short_url' => $short_url
            ]);
        } catch (UniqueConstraintViolationException $e) {

            throw $e;
        }
    }

    public function getRedirectedLink(string $short_url): ?Links
    {
        try {
            return Links::where('short_url', $short_url)->first();
        } catch (QueryException $e) {
            throw $e;
        }
    }
}
