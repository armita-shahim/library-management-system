<?php

namespace App\Exceptions;

use Exception;

class BookAlreadyExistsException extends Exception
{
    public function __construct(string $isbn)
    {
        parent::__construct(
            "Book with ISBN $isbn already exists."
        );
    }
}
