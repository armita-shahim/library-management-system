<?php

require __DIR__ . '/vendor/autoload.php';

use App\Service\Library;
use App\Storage\FileStorage;
use App\Repositories\BookRepository;
use App\Repositories\MemberRepository;

$bookStorage = new FileStorage(__DIR__ . '/data/books.json');
$memberStorage = new FileStorage(__DIR__ . '/data/members.json');

$bookRepository = new BookRepository($bookStorage);
$memberRepository = new MemberRepository($memberStorage);

$library = new Library(
    $bookRepository,
    $memberRepository
);

while (true) {

    echo PHP_EOL;
    echo "===== Library Management System =====" . PHP_EOL;
    echo "1. Add Book" . PHP_EOL;
    echo "2. Remove Book" . PHP_EOL;
    echo "3. Search Book" . PHP_EOL;
    echo "4. List Books" . PHP_EOL;
    echo "5. Add Member" . PHP_EOL;
    echo "6. Remove Member" . PHP_EOL;
    echo "7. Search Member" . PHP_EOL;
    echo "8. List Members" . PHP_EOL;
    echo "9. Borrow Book" . PHP_EOL;
    echo "10. Return Book" . PHP_EOL;
    echo "0. Exit" . PHP_EOL;

    $choice = readline("Choose an option: ");

    switch ($choice) {

        case '1':
            $title = readline("Enter book title: ");
            $author = readline("Enter book author: ");
            $isbn = readline("Enter book ISBN: ");

            try {
                $library->addBook($title, $author, $isbn);
                echo "Book added successfully." . PHP_EOL;
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage() . PHP_EOL;
            }

            break;

        case '2':
            $isbn = readline("Enter book ISBN: ");

            try {
                $library->removeBook($isbn);
                echo "Book removed successfully." . PHP_EOL;
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage() . PHP_EOL;
            }

            break;

        case '3':
            $isbn = readline("Enter book ISBN: ");

            $book = $library->searchBook($isbn);

            if ($book === null) {
                echo "Book not found." . PHP_EOL;
                break;
            }

            echo "Title: " . $book->getTitle() . PHP_EOL;
            echo "Author: " . $book->getAuthor() . PHP_EOL;
            echo "ISBN: " . $book->getIsbn() . PHP_EOL;
            echo "Available: " . ($book->isAvailable() ? "Yes" : "No") . PHP_EOL;

            break;

        case '4':
            $books = $library->listBooks();

            if (count($books) === 0) {
                echo "No books found." . PHP_EOL;
                break;
            }

            foreach ($books as $book) {
                echo PHP_EOL;
                echo "Title: " . $book->getTitle() . PHP_EOL;
                echo "Author: " . $book->getAuthor() . PHP_EOL;
                echo "ISBN: " . $book->getIsbn() . PHP_EOL;
                echo "Available: " . ($book->isAvailable() ? "Yes" : "No") . PHP_EOL;
            }

            break;

        case '5':
            $id = readline("Enter member ID: ");
            $fullName = readline("Enter full name: ");
            $email = readline("Enter email: ");

            try {
                $library->addMember($id, $fullName, $email);
                echo "Member added successfully." . PHP_EOL;
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage() . PHP_EOL;
            }

            break;

        case '6':
            $id = readline("Enter member ID: ");

            try {
                $library->removeMember($id);
                echo "Member removed successfully." . PHP_EOL;
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage() . PHP_EOL;
            }

            break;

        case '7':
            $id = readline("Enter member ID: ");

            $member = $library->searchMember($id);

            if ($member === null) {
                echo "Member not found." . PHP_EOL;
                break;
            }
            echo "ID: " . $member->getId() . PHP_EOL;
            echo "Name: " . $member->getFullName() . PHP_EOL;
            echo "Email: " . $member->getEmail() . PHP_EOL;
            echo "Borrowed books: " . count($member->getBorrowedBooks()) . PHP_EOL;

            break;

        case '8':
            $members = $library->listMembers();

            if (count($members) === 0) {
                echo "No members found." . PHP_EOL;
                break;
            }

            foreach ($members as $member) {
                echo PHP_EOL;
                echo "ID: " . $member->getId() . PHP_EOL;
                echo "Name: " . $member->getFullName() . PHP_EOL;
                echo "Email: " . $member->getEmail() . PHP_EOL;
                echo "Borrowed books: " . count($member->getBorrowedBooks()) . PHP_EOL;
            }

            break;

        case '9':
            $isbn = readline("Enter book ISBN: ");
            $id = readline("Enter member ID: ");

            try {
                $library->borrowBook($isbn, $id);
                echo "Book borrowed successfully." . PHP_EOL;
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage() . PHP_EOL;
            }

            break;

        case '10':
            $isbn = readline("Enter book ISBN: ");
            $id = readline("Enter member ID: ");

            try {
                $library->returnBook($isbn, $id);
                echo "Book returned successfully." . PHP_EOL;
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage() . PHP_EOL;
            }

            break;

        case '0':
            echo "Goodbye!" . PHP_EOL;
            exit;

        default:
            echo "Invalid option." . PHP_EOL;
    }
}
