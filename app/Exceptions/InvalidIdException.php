<?php

namespace App\Exceptions;

use Exception;

class InvalidIdException extends Exception
{
    public function render()
    {
        return response()->json([
            'code' => '400',
            'type' => 'Client Error',
            'message' => 'Invalid ID supplied'
        ], 400);
    }
}
