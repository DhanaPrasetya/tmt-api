<?php

namespace App\Utils\Response;

class StandarizedResponse
{
    public static function standarized_response($responseObject)
    {
        $message = 'Success';

        if ($responseObject['code'] >= 400) {
            $message = 'Failed';
        }

        return response()->json([
            'code' => $responseObject['code'],
            'status' => $message,
            'message' => $responseObject['message'],
            'data' => $responseObject['data'],
        ], $responseObject['code']);
    }
}
