<?php

namespace App\Exceptions;

use Exception;

class BookAlreadyAvailableException extends Exception
{
    public function __construct(string $isbn)
    {
        parent::__construct(
            "Book with ISBN $isbn is already available."
        );
    }
}
