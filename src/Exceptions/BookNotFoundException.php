<?php

namespace App\Exceptions;

use Exception;

class BookNotFoundException extends Exception
{

    public function __construct(string $isbn)
    {
        parent::__construct("Book with ISBN $isbn was not found.");
    }
}
