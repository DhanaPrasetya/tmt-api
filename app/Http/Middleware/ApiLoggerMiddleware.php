<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiLoggerMiddleware // Only handle successful responses, errors are logged in bootstrap/app.php
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->isSuccessful()) {
            $method = $request->getMethod();
            $path = $request->getPathInfo();
            $status = $response->getStatusCode();

            Log::info("HTTP [{$status}] | Success | {$method} {$path}");
        }

        return $response;
    }
}
