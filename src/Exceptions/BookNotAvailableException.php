<?php

namespace App\Exceptions;

use Exception;

class BookNotAvailableException extends Exception
{

    public function __construct(string $isbn)
    {
        parent::__construct("Book with ISBN $isbn is not available.");
    }
}
