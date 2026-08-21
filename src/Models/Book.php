<?php

namespace App\Models;

class Book
{
    private string $title;
    private string $author;
    private string $isbn;
    private bool $available;
    private ?string $borrowedAt;
    private ?string $dueDate;
    private string $category;


    public function __construct(
        string $title,
        string $author,
        string $category,
        string $isbn,
        bool $available,
        ?string $borrowedAt = null,
        ?string $dueDate = null
    ) {

        $this->title = $title;
        $this->author = $author;
        $this->category = $category;
        $this->isbn = $isbn;
        $this->available = $available;
        $this->borrowedAt = $borrowedAt;
        $this->dueDate = $dueDate;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function getIsbn(): string
    {
        return $this->isbn;
    }

    public function isAvailable(): bool
    {
        return $this->available;
    }

    public function getBorrowedAt(): ?string
    {
        return $this->borrowedAt;
    }

    public function getDueDate(): ?string
    {
        return $this->dueDate;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    public function setIsbn(string $isbn): void
    {
        $this->isbn = $isbn;
    }

    public function setAvailable(bool $available): void
    {
        $this->available = $available;
    }

    public function setBorrowedAt(?string $borrowedAt): void
    {
        $this->borrowedAt = $borrowedAt;
    }

    public function setDueDate(?string $dueDate): void
    {
        $this->dueDate = $dueDate;
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'author' => $this->author,
            'category' => $this->category,
            'isbn' => $this->isbn,
            'available' => $this->available,
            'borrowedAt' => $this->borrowedAt,
            'dueDate' => $this->dueDate
        ];
    }

    public static function fromArray(array $data): Book
    {
        return new Book(
            $data['title'],
            $data['author'],
            $data['category'],
            $data['isbn'],
            $data['available'],
            $data['borrowedAt'] ?? null,
            $data['dueDate'] ?? null
        );
    }
}
