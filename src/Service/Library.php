<?php

namespace App\Service;

use App\Exceptions\BookAlreadyAvailableException;
use App\Exceptions\BookAlreadyExistsException;
use App\Exceptions\BookNotAvailableException;
use App\Exceptions\BookNotFoundException;
use App\Exceptions\MemberAlreadyExistsException;
use App\Exceptions\MemberHasBorrowedBooksException;
use App\Exceptions\MemberNotBorrowBookException;
use App\Exceptions\MemberNotFoundException;
use App\Repositories\BookRepository;
use App\Repositories\MemberRepository;
use App\Repositories\HistoryRepository;
use App\Models\Book;
use App\Models\Member;
use App\Validator\BookValidator;
use App\Validator\MemberValidator;
use App\Traits\Loggable;
use DateTime;


class Library
{
    use Loggable;

    private BookRepository $bookRepository;
    private MemberRepository $memberRepository;
    private HistoryRepository $historyRepository;
    private BookValidator $bookValidator;
    private MemberValidator $memberValidator;

    public function __construct(
        BookRepository $bookRepository,
        MemberRepository $memberRepository,
        HistoryRepository $historyRepository,
        BookValidator $bookValidator,
        MemberValidator $memberValidator
    ) {
        $this->bookRepository = $bookRepository;
        $this->memberRepository = $memberRepository;
        $this->historyRepository = $historyRepository;
        $this->bookValidator = $bookValidator;
        $this->memberValidator = $memberValidator;
    }

    public function addBook(
        string $title,
        string $author,
        string $category,
        string $isbn
    ): void {

        $this->bookValidator->validate(
            $title,
            $author,
            $category,
            $isbn
        );

        $existingBook = $this->bookRepository->findByIsbn($isbn);

        if ($existingBook !== null) {
            throw new BookAlreadyExistsException($isbn);
        }
        $book = new Book(
            $title,
            $author,
            $category,
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
            throw new BookNotFoundException($isbn);
        }
        $this->bookRepository->delete($isbn);
        $this->log("Book removed: {$book->getTitle()}");
    }

    public function searchBook(string $isbn): ?Book
    {
        return $this->bookRepository->findByIsbn($isbn);
    }

    public function searchBooks(string $query): array
    {
        return $this->bookRepository->search($query);
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

        $this->memberValidator->validate(
            $id,
            $fullName,
            $email
        );

        $existingMember = $this->memberRepository->findById($id);

        if ($existingMember !== null) {
            throw new MemberAlreadyExistsException($id);
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
            throw new MemberNotFoundException($id);
        }
        if (count($member->getBorrowedBooks()) > 0) {
            throw new MemberHasBorrowedBooksException($id);
        }
        $this->memberRepository->delete($id);
        $this->log("Member removed: {$member->getFullName()}");
    }

    public function searchMember(string $id): ?Member
    {
        return $this->memberRepository->findById($id);
    }

    public function searchMembers(string $query): array
    {
        return $this->memberRepository->search($query);
    }

    public function listMembers(): array
    {
        return $this->memberRepository->findAll();
    }

    public function filterAvailableBooks(): array
    {
        return $this->bookRepository->filterAvailable();
    }

    public function filterBooksByCategory(string $category): array
    {
        return $this->bookRepository->filterByCategory($category);
    }

    public function getHistoryForMember(string $id): array
    {
        return $this->historyRepository->findByMember($id);
    }

    public function borrowBook(
        string $isbn,
        string $id
    ): void {
        $book = $this->bookRepository->findByIsbn($isbn);

        if ($book === null) {
            throw new BookNotFoundException($isbn);
        }
        $member = $this->memberRepository->findById($id);

        if ($member === null) {
            throw new MemberNotFoundException($id);
        }
        if (!$book->isAvailable()) {
            throw new BookNotAvailableException($isbn);
        }
        $book->setAvailable(false);
        $borrowedAt = date('Y-m-d H:i:s');
        $dueDate = date('Y-m-d H:i:s', strtotime('+14 days'));

        $book->setBorrowedAt($borrowedAt);
        $book->setDueDate($dueDate);

        $borrowedBooks = $member->getBorrowedBooks();
        $borrowedBooks[] = $isbn;

        $member->setBorrowedBooks($borrowedBooks);

        $this->bookRepository->update($book);
        $this->memberRepository->update($member);

        $this->historyRepository->save(
            'borrow',
            $isbn,
            $id
        );

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
            throw new BookNotFoundException($isbn);
        }

        $member = $this->memberRepository->findById($id);

        if ($member === null) {
            throw new MemberNotFoundException($id);
        }

        if ($book->isAvailable()) {
            throw new BookAlreadyAvailableException($isbn);
        }

        $borrowedBooks = $member->getBorrowedBooks();

        $index = array_search($isbn, $borrowedBooks, true);

        if ($index === false) {
            throw new MemberNotBorrowBookException($id, $isbn);
        }

        unset($borrowedBooks[$index]);

        $member->setBorrowedBooks(array_values($borrowedBooks));

        $book->setAvailable(true);
        $book->setBorrowedAt(null);
        $book->setDueDate(null);

        $this->bookRepository->update($book);
        $this->memberRepository->update($member);

        $this->historyRepository->save(
            'return',
            $isbn,
            $id
        );

        $this->log("Book returned: $isbn by member: $id");
    }



    public function getOverdueBooks(): array
    {
        $books = $this->bookRepository->findAll();
        $overdueBooks = [];

        for ($i = 0; $i < count($books); $i++) {
            if (
                !$books[$i]->isAvailable() &&
                $books[$i]->getDueDate() !== null &&
                strtotime($books[$i]->getDueDate()) < time()
            ) {
                $overdueBooks[] = $books[$i];
            }
        }

        return $overdueBooks;
    }

    public function calculateFine(Book $book): int
    {
        if ($book->getDueDate() === null) {
            return 0;
        }

        $dueDate = new DateTime($book->getDueDate());
        $today = new DateTime();

        if ($dueDate >= $today) {
            return 0;
        }

        $difference = $dueDate->diff($today);

        return $difference->days * 1000;
    }

    public function getOverdueFines(): array
    {
        $overdueBooks = $this->getOverdueBooks();
        $fines = [];

        for ($i = 0; $i < count($overdueBooks); $i++) {
            $book = $overdueBooks[$i];

            $fines[] = [
                'isbn' => $book->getIsbn(),
                'title' => $book->getTitle(),
                'fine' => $this->calculateFine($book)
            ];
        }

        return $fines;
    }
}
