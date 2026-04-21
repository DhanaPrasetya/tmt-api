<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Infrastructure\Repository\Interface\RepositoryInterface;
use App\Infrastructure\Repository\Eloquent\EloquentImplementation;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind the interface to the implementation
        $this->app->bind(RepositoryInterface::class, EloquentImplementation::class);
    }
}
