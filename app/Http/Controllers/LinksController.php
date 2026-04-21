<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\UseCases\CreateShortLink;
use Illuminate\Support\Facades\Log;
use App\Utils\Response\StandarizedResponse;

class LinksController
{

    public function shorten(Request $request, CreateShortLink $createShortLink) // dependency injection
    {
        try {
            $link = $createShortLink->create( // calling create method
                $request->input('original_url'),
                $request->input('short_url')
            );

            $responseData = $link->short_url;

            return StandarizedResponse::standarized_response(201, $responseData);
        } catch (\Throwable $e) {
            Log::error('Link creation failed', [
                'message' => $e->getMessage(),
                'exception' => $e,
            ]);

            return response()->json([
                'message' => 'Error creating link',
            ], 500);
        }
    }

    // public function short_link(string $id): JsonResponse
    // {
    //     return response()->json();
    // }
}
