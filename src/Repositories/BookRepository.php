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
