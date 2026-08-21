<?php

namespace App\Exceptions;

use Exception;

class MemberNotFoundException extends Exception
{

    public function __construct(string $id)
    {

        parent::__construct("Member with ID $id was not found.");
    }
}
