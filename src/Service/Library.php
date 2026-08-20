<?php

namespace App\Service;

use App\Repositories\BookRepository;
use App\Repositories\MemberRepository;
use App\Models\Book;
use App\Models\Member;
use App\Traits\Loggable;
use Exception;

class Library
{
    use Loggable;

    private BookRepository $bookRepository;
    private MemberRepository $memberRepository;

    public function __construct(
        BookRepository $bookRepository,
        MemberRepository $memberRepository
    ) {
        $this->bookRepository = $bookRepository;
        $this->memberRepository = $memberRepository;
    }

    public function addBook(
        string $title,
        string $author,
        string $isbn
    ): void {

        $existingBook = $this->bookRepository->findByIsbn($isbn);

        if ($existingBook !== null) {
            throw new Exception("Book with this ISBN already exists.");
        }
        $book = new Book(
            $title,
            $author,
            $isbn,
            true
        );

        $this->bookRepository->save($book);
        $this->log("Book added: $title");
    }

    public function removeBook(string $isbn): void
    {
        $book = $this->bookRepository->findByIsbn($isbn);

        if ($book === null) {
            throw new Exception("Book not found.");
        }
        $this->bookRepository->delete($isbn);
        $this->log("Book removed: {$book->getTitle()}");
    }

    public function searchBook(string $isbn): ?Book
    {
        return $this->bookRepository->findByIsbn($isbn);
    }

    public function listBooks(): array
    {
        return $this->bookRepository->findAll();
    }

    public function addMember(
        string $id,
        string $fullName,
        string $email
    ): void {
        $existingMember = $this->memberRepository->findById($id);

        if ($existingMember !== null) {
            throw new Exception("Member with this ID already exists.");
        }
        $member = new Member(
            $id,
            $fullName,
            $email,
            []
        );

        $this->memberRepository->save($member);
        $this->log("Member added: $fullName");
    }

    public function removeMember(string $id): void
    {

        $member = $this->memberRepository->findById($id);
        if ($member === null) {
            throw new Exception("Member not found.");
        }
        if (count($member->getBorrowedBooks()) > 0) {
            throw new Exception("Member still has borrowed books.");
        }
        $this->memberRepository->delete($id);
        $this->log("Member removed: {$member->getFullName()}");
    }

    public function searchMember(string $id): ?Member
    {
        return $this->memberRepository->findById($id);
    }

    public function listMembers(): array
    {
        return $this->memberRepository->findAll();
    }

    public function borrowBook(
        string $isbn,
        string $id
    ): void {
        $book = $this->bookRepository->findByIsbn($isbn);

        if ($book === null) {
            throw new Exception("Book not found.");
        }
        $member = $this->memberRepository->findById($id);

        if ($member === null) {
            throw new Exception("Member not found.");
        }
        if (!$book->isAvailable()) {
            throw new Exception("Book is not available.");
        }
        $book->setAvailable(false);

        $borrowedBooks = $member->getBorrowedBooks();
        $borrowedBooks[] = $isbn;

        $member->setBorrowedBooks($borrowedBooks);

        $this->bookRepository->update($book);
        $this->memberRepository->update($member);

        $this->log(
            "Book borrowed: $isbn by member: $id"
        );
    }

    public function returnBook(
        string $isbn,
        string $id
    ): void {
        $book = $this->bookRepository->findByIsbn($isbn);

        if ($book === null) {
            throw new Exception("Book not found.");
        }

        $member = $this->memberRepository->findById($id);

        if ($member === null) {
            throw new Exception("Member not found.");
        }
        if ($book->isAvailable()) {
            throw new Exception("Book is already available.");
        }
        $borrowedBooks = $member->getBorrowedBooks();

        $index = array_search($isbn, $borrowedBooks, true);

        if ($index === false) {
            throw new Exception("This member did not borrow this book.");
        }
        unset($borrowedBooks[$index]);

        $member->setBorrowedBooks(array_values($borrowedBooks));
        $book->setAvailable(true);

        $this->bookRepository->update($book);
        $this->memberRepository->update($member);

        $this->log("Book returned: $isbn by member: $id");
    }
}
