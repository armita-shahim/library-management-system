<?php

namespace App\Validator;

use App\Exceptions\InvalidBookDataException;

class BookValidator
{
    public function validate(
        string $title,
        string $author,
        string $category,
        string $isbn
    ): void {

        if (trim($title) === '') {
            throw new InvalidBookDataException(
                "Book title cannot be empty."
            );
        }

        if (trim($author) === '') {
            throw new InvalidBookDataException(
                "Book author cannot be empty."
            );
        }

        if (trim($category) === '') {
            throw new InvalidBookDataException(
                "Book category cannot be empty."
            );
        }

        if (!ctype_digit($isbn)) {
            throw new InvalidBookDataException(
                "ISBN must contain only digits."
            );
        }

        if (strlen($isbn) !== 10 && strlen($isbn) !== 13) {
            throw new InvalidBookDataException(
                "ISBN must contain 10 or 13 digits."
            );
        }
    }
}
