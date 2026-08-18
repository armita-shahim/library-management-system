<?php

namespace App\Models;

class Member
{
    private string $id;
    private string $fullName;
    private string $email;
    private array $borrowedBooks;

    public function __construct(
        string $id,
        string $fullName,
        string $email,
        array $borrowedBooks = []
    ) {
        $this->id = $id;
        $this->fullName = $fullName;
        $this->email = $email;
        $this->borrowedBooks = $borrowedBooks;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getBorrowedBooks(): array
    {
        return $this->borrowedBooks;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setFullName(string $fullName): void
    {
        $this->fullName = $fullName;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function setBorrowedBooks(array $borrowedBooks): void
    {
        $this->borrowedBooks = $borrowedBooks;
    }

    public function toArray(): array
    {
        return
            [
                'id' => $this->id,
                'fullName' => $this->fullName,
                'email' => $this->email,
                'borrowedBooks' => $this->borrowedBooks
            ];
    }

    public static function fromArray(array $data): Member
    {
        return new Member(
            $data['id'],
            $data['fullName'],
            $data['email'],
            $data['borrowedBooks'] ?? []
        );
    }
}
