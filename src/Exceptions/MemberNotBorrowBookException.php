<?php

namespace App\Exceptions;

use Exception;

class MemberNotBorrowBookException extends Exception
{

    public function __construct(string $id, string $isbn)
    {

        parent::__construct("Member with ID $id did not borrow book with ISBN $isbn.");
    }
}
