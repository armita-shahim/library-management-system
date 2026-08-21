<?php

namespace App\Exceptions;

use Exception;

class MemberHasBorrowedBooksException extends Exception
{
    public function __construct(string $id)
    {
        parent::__construct(
            "Member with ID $id still has borrowed books."
        );
    }
}
