<?php

namespace App\Classes;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiResponseClass
{
    public static function throw($e, $status = 500)
    {
        DB::rollBack();

        throw new HttpResponseException(
            response()->json([
                "success" => false,
                "message" => is_string($e) ? $e : $e->getMessage(),
                "data" => null
            ], $status)
        );
    }

    public static function sendResponse($result, $message, $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $result
        ], $code);
    }
}
