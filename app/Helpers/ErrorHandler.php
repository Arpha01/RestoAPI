<?php

namespace App\Helpers;

class ErrorHandler
{
    public static function errorResource($errorMessage = [], $code = 400) 
    {
        return [
            'success' => false,
            'code' => $code,
            'errors' => $errorMessage
        ];
    }
}
