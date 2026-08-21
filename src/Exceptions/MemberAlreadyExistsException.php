<?php

namespace App\Exceptions;

use Exception;

class MemberAlreadyExistsException extends Exception
{

    public function __construct(string $id)
    {

        parent::__construct("Member with ID $id already exists.");
    }
}
