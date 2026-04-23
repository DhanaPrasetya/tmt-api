<?php

use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request; // Use the Laravel Request for better compatibility
use Illuminate\Support\Facades\Log;
use App\Utils\Response\StandarizedResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php', // for frontend routes (unused)
        api: __DIR__ . '/../routes/api.php', // for API routes
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) { // global middleware configuration
        $middleware->append(\App\Http\Middleware\ApiLoggerMiddleware::class);
    })
    ->withExceptions(function ($exceptions) {
        $exceptions->stopIgnoring(ValidationException::class);

        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            return true;
        });
        // Global logger
        $exceptions->report(function (Throwable $e) {

            $statusCode = match (true) {
                $e instanceof HttpExceptionInterface => $e->getStatusCode(),
                $e instanceof NotFoundHttpException => 404,
                $e instanceof ModelNotFoundException => 404,
                $e instanceof UniqueConstraintViolationException => 409,
                $e instanceof ValidationException => 422,
                $e instanceof QueryException => 500,
                default => 500,
            };

            $trace = collect($e->getTrace())->first(function ($frame) {
                return isset($frame['file']) && !str_contains($frame['file'], 'vendor');
            });

            $line = $trace['line'] ?? $e->getLine();
            $message = $e->getPrevious()?->getMessage() ?? $e->getMessage();
            $fullPath = $trace['file'] ?? $e->getFile();
            $relativePath = str_replace(base_path() . '/', '', $fullPath);

            Log::error("HTTP [{$statusCode}] | {$message} | {$relativePath}:{$line}");

            return false;
        });
        // Unique violation response
        $exceptions->render(function (UniqueConstraintViolationException $e) {
            $responseObject = [
                'code' => 409,
                'message' => 'Already exists',
                'data' => null,
            ];

            return StandarizedResponse::standarized_response($responseObject);
        });
        // Route not found error response
        $exceptions->render(function (NotFoundHttpException $e) {
            $responseObject = [
                'code' => 404,
                'message' => 'Route not found',
                'data' => null,
            ];

            return StandarizedResponse::standarized_response($responseObject);
        });
        // Data not found error response
        $exceptions->render(function (ModelNotFoundException $e) {
            $responseObject = [
                'code' => 404,
                'message' => 'Data not found',
                'data' => null,
            ];

            return StandarizedResponse::standarized_response($responseObject);
        });
        // Internal error response
        $exceptions->render(function (QueryException $e, Request $request) {

            $responseObject = [
                'code' => 500,
                'message' => 'Internal server error',
                'data' => null,
            ];

            return StandarizedResponse::standarized_response($responseObject);
        });
    })
    ->create();
