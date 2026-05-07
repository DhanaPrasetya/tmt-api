<?php

namespace App\Http\Controllers;

use App\UseCases\CreateShortUrl;
use App\Utils\Response\StandarizedResponse;
use App\Http\Requests\CreateShortUrlValidator;
use App\Http\Requests\GetOriginalUrlValidator;
use App\UseCases\GetOriginalUrl;

class UrlController
{

    public function shorten(CreateShortUrlValidator $request, CreateShortUrl $createShortUrl) // form validation & use cases dependency injection
    {
        $validatedRequest = $request->validated();

        $responseData = $createShortUrl->create(
            $validatedRequest['original_url'],
            $validatedRequest['custom_alias']
        );

        $responseObject = [
            'code' => 201,
            'message' => 'URL shortened successfully.',
            'data' => $responseData,
        ];

        return StandarizedResponse::standarized_response($responseObject);
    }

    public function get_original_url(GetOriginalUrlValidator $request, GetOriginalUrl $getOriginalUrl) // form validation & use cases dependency injection
    {
        $validatedRequest = $request->validated();

        $responseData = $getOriginalUrl->get($validatedRequest['short_url']);

        $responseObject = [
            'code' => 200,
            'message' => 'Original URL retrieved successfully.',
            'data' => $responseData,
        ];

        return StandarizedResponse::standarized_response($responseObject);
    }
}
