<?php

namespace App\Repositories;

use App\Models\Book;

class BookRepository extends AbstractRepository
{
    public function save(Book $book): void
    {
        $books = $this->storage->load();
        $books[] = $book->toArray();
        $this->storage->save($books);
    }

    public function findAll(): array
    {
        $data = $this->storage->load();

        return array_map(
            fn(array $bookData): Book => Book::fromArray($bookData),
            $data
        );
    }

    public function findByIsbn(string $isbn): ?Book
    {
        $books = $this->findAll();

        for ($i = 0; $i < count($books); $i++) {
            if ($books[$i]->getIsbn() === $isbn) {
                return $books[$i];
            }
        }

        return null;
    }

    public function search(string $query): array
    {
        $books = $this->findAll();
        $results = [];

        for ($i = 0; $i < count($books); $i++) {
            if (
                stripos($books[$i]->getTitle(), $query) !== false ||
                stripos($books[$i]->getAuthor(), $query) !== false ||
                stripos($books[$i]->getIsbn(), $query) !== false ||
                stripos($books[$i]->getCategory(), $query) !== false
            ) {
                $results[] = $books[$i];
            }
        }

        return $results;
    }

    public function filterAvailable(): array
    {
        $books = $this->findAll();
        $results = [];

        for ($i = 0; $i < count($books); $i++) {
            if ($books[$i]->isAvailable()) {
                $results[] = $books[$i];
            }
        }

        return $results;
    }

    public function filterByCategory(string $category): array
    {
        $books = $this->findAll();
        $results = [];

        for ($i = 0; $i < count($books); $i++) {
            if (strtolower($books[$i]->getCategory()) === strtolower($category)) {
                $results[] = $books[$i];
            }
        }

        return $results;
    }

    public function update(Book $book): bool
    {
        $books = $this->findAll();

        for ($i = 0; $i < count($books); $i++) {
            if ($books[$i]->getIsbn() === $book->getIsbn()) {
                $books[$i] = $book;

                $data = array_map(
                    fn(Book $book): array => $book->toArray(),
                    $books
                );

                $this->storage->save($data);

                return true;
            }
        }

        return false;
    }

    public function delete(string $isbn): bool
    {
        $books = $this->findAll();

        $filteredBooks = array_filter(
            $books,
            fn(Book $book): bool => $book->getIsbn() !== $isbn
        );

        if (count($books) === count($filteredBooks)) {
            return false;
        }

        $data = array_map(
            fn(Book $book): array => $book->toArray(),
            $filteredBooks
        );

        $this->storage->save(array_values($data));

        return true;
    }
}
