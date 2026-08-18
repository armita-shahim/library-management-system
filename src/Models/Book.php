<?php

namespace App\Models;

class Book
{
    private string $title;
    private string $author;
    private string $isbn;
    private bool $available;

    public function __construct(
        string $title,
        string $author,
        string $isbn,
        bool $available
    ) {

        $this->title = $title;
        $this->author = $author;
        $this->isbn = $isbn;
        $this->available = $available;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function isAvailable(): bool
    {
        return $this->available;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    public function setIsbn(string $isbn): void
    {
        $this->isbn = $isbn;
    }

    public function setAvailable(bool $available): void
    {
        $this->available = $available;
    }

    public function toArray(): array
    {
        return
            [
                'title' => $this->title,
                'author' => $this->author,
                'isbn' => $this->isbn,
                'available' => $this->available
            ];
    }

    public static function fromArray(array $data): Book
    {
        return new Book(
            $data['title'],
            $data['author'],
            $data['isbn'],
            $data['available']
        );
    }
}
