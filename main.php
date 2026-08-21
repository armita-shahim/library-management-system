<?php

require __DIR__ . '/vendor/autoload.php';

use App\Service\Library;
use App\Storage\FileStorage;
use App\Repositories\BookRepository;
use App\Repositories\MemberRepository;
use App\Repositories\HistoryRepository;
use App\Validator\BookValidator;
use App\Validator\MemberValidator;

$bookStorage = new FileStorage(__DIR__ . '/data/books.json');
$memberStorage = new FileStorage(__DIR__ . '/data/members.json');
$historyStorage = new FileStorage(__DIR__ . '/data/history.json');

$bookRepository = new BookRepository($bookStorage);
$memberRepository = new MemberRepository($memberStorage);
$historyRepository = new HistoryRepository($historyStorage);

$bookValidator = new BookValidator();
$memberValidator = new MemberValidator();

$library = new Library(
    $bookRepository,
    $memberRepository,
    $historyRepository,
    $bookValidator,
    $memberValidator
);

while (true) {

    echo PHP_EOL;
    echo "===== Library Management System =====" . PHP_EOL;
    echo "1.  Add Book" . PHP_EOL;
    echo "2.  Remove Book" . PHP_EOL;
    echo "3.  Search Books" . PHP_EOL;
    echo "4.  List Books" . PHP_EOL;
    echo "5.  Filter Available Books" . PHP_EOL;
    echo "6.  Filter Books by Category" . PHP_EOL;
    echo "7.  Add Member" . PHP_EOL;
    echo "8. Remove Member" . PHP_EOL;
    echo "9. Search Members" . PHP_EOL;
    echo "10. List Members" . PHP_EOL;
    echo "11. Borrow Book" . PHP_EOL;
    echo "12. Return Book" . PHP_EOL;
    echo "13. View Member History" . PHP_EOL;
    echo "14. View Overdue Books" . PHP_EOL;
    echo "15. View Overdue Fines" . PHP_EOL;
    echo "0.  Exit" . PHP_EOL;

    $choice = readline("Choose an option: ");

    switch ($choice) {

        case '1':

            $title = readline("Enter book title: ");
            $author = readline("Enter book author: ");
            $category = readline("Enter book category: ");
            $isbn = readline("Enter book ISBN: ");

            try {

                $library->addBook(
                    $title,
                    $author,
                    $category,
                    $isbn
                );

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

            $query = readline("Enter search query: ");

            $books = $library->searchBooks($query);

            if (count($books) === 0) {

                echo "No books found." . PHP_EOL;
                break;
            }

            echo PHP_EOL;
            echo "Search results:" . PHP_EOL;

            for ($i = 0; $i < count($books); $i++) {

                $book = $books[$i];

                echo PHP_EOL;
                echo "Title: " . $book->getTitle() . PHP_EOL;
                echo "Author: " . $book->getAuthor() . PHP_EOL;
                echo "Category: " . $book->getCategory() . PHP_EOL;
                echo "ISBN: " . $book->getIsbn() . PHP_EOL;
                echo "Available: "
                    . ($book->isAvailable() ? "Yes" : "No")
                    . PHP_EOL;
            }

            break;


        case '4':

            $books = $library->listBooks();

            if (count($books) === 0) {

                echo "No books found." . PHP_EOL;
                break;
            }

            for ($i = 0; $i < count($books); $i++) {

                $book = $books[$i];
                echo PHP_EOL;
                echo "Title: " . $book->getTitle() . PHP_EOL;
                echo "Author: " . $book->getAuthor() . PHP_EOL;
                echo "Category: " . $book->getCategory() . PHP_EOL;
                echo "ISBN: " . $book->getIsbn() . PHP_EOL;
                echo "Available: "
                    . ($book->isAvailable() ? "Yes" : "No")
                    . PHP_EOL;

                if ($book->getDueDate() !== null) {
                    echo "Due Date: "
                        . $book->getDueDate()
                        . PHP_EOL;
                }
            }

            break;


        case '5':

            $books = $library->filterAvailableBooks();

            if (count($books) === 0) {

                echo "No available books found." . PHP_EOL;
                break;
            }

            echo "Available books:" . PHP_EOL;

            for ($i = 0; $i < count($books); $i++) {

                echo "- "
                    . $books[$i]->getTitle()
                    . PHP_EOL;
            }

            break;


        case '6':

            $category = readline("Enter category: ");

            $books = $library->filterBooksByCategory($category);

            if (count($books) === 0) {

                echo "No books found in this category." . PHP_EOL;
                break;
            }

            echo "Books in category:" . PHP_EOL;

            for ($i = 0; $i < count($books); $i++) {

                echo "- "
                    . $books[$i]->getTitle()
                    . PHP_EOL;
            }

            break;

        case '7':

            $id = readline("Enter member ID: ");
            $fullName = readline("Enter full name: ");
            $email = readline("Enter email: ");

            try {

                $library->addMember(
                    $id,
                    $fullName,
                    $email
                );

                echo "Member added successfully." . PHP_EOL;
            } catch (Exception $e) {

                echo "Error: " . $e->getMessage() . PHP_EOL;
            }

            break;


        case '8':

            $id = readline("Enter member ID: ");

            try {

                $library->removeMember($id);

                echo "Member removed successfully." . PHP_EOL;
            } catch (Exception $e) {

                echo "Error: " . $e->getMessage() . PHP_EOL;
            }

            break;


        case '9':

            $query = readline("Enter search query: ");

            $members = $library->searchMembers($query);

            if (count($members) === 0) {

                echo "No members found." . PHP_EOL;
                break;
            }

            echo "Search results:" . PHP_EOL;

            for ($i = 0; $i < count($members); $i++) {

                $member = $members[$i];

                echo PHP_EOL;
                echo "ID: " . $member->getId() . PHP_EOL;
                echo "Name: " . $member->getFullName() . PHP_EOL;
                echo "Email: " . $member->getEmail() . PHP_EOL;
                echo "Borrowed books: "
                    . count($member->getBorrowedBooks())
                    . PHP_EOL;
            }

            break;


        case '10':
            $members = $library->listMembers();

            if (count($members) === 0) {

                echo "No members found." . PHP_EOL;
                break;
            }

            for ($i = 0; $i < count($members); $i++) {

                $member = $members[$i];

                echo PHP_EOL;
                echo "ID: " . $member->getId() . PHP_EOL;
                echo "Name: " . $member->getFullName() . PHP_EOL;
                echo "Email: " . $member->getEmail() . PHP_EOL;
                echo "Borrowed books: "
                    . count($member->getBorrowedBooks())
                    . PHP_EOL;
            }

            break;


        case '11':

            $isbn = readline("Enter book ISBN: ");
            $id = readline("Enter member ID: ");

            try {

                $library->borrowBook(
                    $isbn,
                    $id
                );

                echo "Book borrowed successfully." . PHP_EOL;
            } catch (Exception $e) {

                echo "Error: " . $e->getMessage() . PHP_EOL;
            }

            break;


        case '12':

            $isbn = readline("Enter book ISBN: ");
            $id = readline("Enter member ID: ");

            try {

                $library->returnBook(
                    $isbn,
                    $id
                );

                echo "Book returned successfully." . PHP_EOL;
            } catch (Exception $e) {

                echo "Error: " . $e->getMessage() . PHP_EOL;
            }

            break;


        case '13':

            $id = readline("Enter member ID: ");

            try {

                $history = $library->getHistoryForMember($id);

                if (count($history) === 0) {

                    echo "No history found." . PHP_EOL;
                    break;
                }

                echo "Member history:" . PHP_EOL;

                for ($i = 0; $i < count($history); $i++) {

                    echo PHP_EOL;

                    foreach ($history[$i] as $key => $value) {
                        echo $key . ": " . $value . PHP_EOL;
                    }
                }
            } catch (Exception $e) {

                echo "Error: " . $e->getMessage() . PHP_EOL;
            }

            break;


        case '14':

            $books = $library->getOverdueBooks();

            if (count($books) === 0) {

                echo "No overdue books." . PHP_EOL;
                break;
            }

            echo "Overdue books:" . PHP_EOL;

            for ($i = 0; $i < count($books); $i++) {

                $book = $books[$i];

                echo PHP_EOL;
                echo "Title: " . $book->getTitle() . PHP_EOL;
                echo "ISBN: " . $book->getIsbn() . PHP_EOL;
                echo "Due Date: " . $book->getDueDate() . PHP_EOL;
            }

            break;


        case '15':

            $fines = $library->getOverdueFines();

            if (count($fines) === 0) {

                echo "No overdue fines." . PHP_EOL;
                break;
            }

            echo "Overdue fines:" . PHP_EOL;

            for ($i = 0; $i < count($fines); $i++) {

                echo PHP_EOL;
                echo "Title: "
                    . $fines[$i]['title']
                    . PHP_EOL;

                echo "ISBN: "
                    . $fines[$i]['isbn']
                    . PHP_EOL;

                echo "Fine: "
                    . $fines[$i]['fine']
                    . " Toman"
                    . PHP_EOL;
            }

            break;


        case '0':

            echo "Goodbye!" . PHP_EOL;
            exit;


        default:

            echo "Invalid option." . PHP_EOL;
    }
}
