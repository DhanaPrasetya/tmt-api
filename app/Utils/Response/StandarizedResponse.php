<?php

namespace App\Utils\Response;

class StandarizedResponse
{
    public static function standarized_response($status_code, $data)
    {
        $message = 'Success';

        if ($status_code >= 400) {
            $message = 'Failed';
        }

        return response()->json([
            'status' => $status_code,
            'message' => $message,
            'data' => $data,
        ], $status_code);
    }
}
