<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UseCases\CreateShortLink;
use App\Utils\Response\StandarizedResponse;

class UrlController
{

    public function shorten(Request $request, CreateShortLink $createShortLink) // dependency injection
    {
        $link = $createShortLink->create( // calling create method
            $request->input('original_url'),
            $request->input('short_url')
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
