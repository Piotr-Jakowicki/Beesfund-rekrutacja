<?php

namespace App\Exceptions;

use Exception;

class InvalidStatusException extends Exception
{
    public function render()
    {
        return response()->json([
            'code' => '400',
            'type' => 'Client Error',
            'message' => 'Invalid status value'
        ], 405);
    }
}
