<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\UseCases\CreateShortLink;
use Illuminate\Support\Facades\Log;

class LinksController
{

    public function shorten(Request $request, CreateShortLink $createShortLink): JsonResponse // dependency injection
    {
        try {
            $link = $createShortLink->create( // calling create method
                $request->input('original_url'),
                $request->input('short_url')
            );

            $response = (object) [
                'message' => 'Short link created successfully',
                'url' => $link->short_url
            ];


            return response()->json($response, 201);
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
