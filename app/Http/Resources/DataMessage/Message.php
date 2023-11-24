<?php

namespace App\Http\Resources\DataMessage;

use Illuminate\Http\JsonResponse;

class Message
{
    public static function TextMessage($message, $code = 200, $header =[]): JsonResponse{
        return response()->json($message, $code, $header);
    }

}
