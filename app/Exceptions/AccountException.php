<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;

class AccountException extends Exception
{
    public function render($request) {
        return response()->json([
            'error' => $this->message
        ], $this->code);
    }
}
