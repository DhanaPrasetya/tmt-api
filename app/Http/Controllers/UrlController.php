<?php

namespace App\Http\Controllers;

use App\UseCases\CreateShortUrl;
use App\Utils\Response\StandarizedResponse;
use App\Http\Requests\CreateShortUrlValidator;

class UrlController
{

    public function shorten(CreateShortUrlValidator $request, CreateShortUrl $createShortUrl) // form validation & use cases dependency injection
    {
        $validatedRequest = $request->validated();

        $link = $createShortUrl->create(
            $validatedRequest['original_url'],
            $validatedRequest['short_url']
        );

        $responseObject = [
            'code' => 201,
            'message' => 'URL shortened successfully.',
            'data' => $link->short_url,
        ];

        return StandarizedResponse::standarized_response($responseObject);
    }

    // public function short_link(string $id): JsonResponse
    // {
    //     return response()->json();
    // }
}
