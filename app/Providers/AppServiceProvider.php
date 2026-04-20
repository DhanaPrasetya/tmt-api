<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\RepositoryInterface;
use App\Repositories\Implementation\EloquentImplementation;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Bind the interface to the implementation
        $this->app->bind(RepositoryInterface::class, EloquentImplementation::class);
    }
}
