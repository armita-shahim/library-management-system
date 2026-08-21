<?php

namespace App\Validator;

use App\Exceptions\InvalidMemberDataException;

class MemberValidator
{
    public function validate(
        string $id,
        string $fullName,
        string $email
    ): void {

        if (trim($id) === '') {
            throw new InvalidMemberDataException(
                "Member ID cannot be empty."
            );
        }

        if (trim($fullName) === '') {
            throw new InvalidMemberDataException(
                "Member name cannot be empty."
            );
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidMemberDataException(
                "Invalid email address."
            );
        }
    }
}
