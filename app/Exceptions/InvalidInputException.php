<?php

namespace App\Exceptions;

use Exception;

class InvalidInputException extends Exception
{
    public function render()
    {
        return response()->json([
            'code' => '405',
            'type' => 'Client Error',
            'message' => 'Invalid input'
        ], 405);
    }
}
