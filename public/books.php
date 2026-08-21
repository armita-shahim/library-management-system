<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Service\Library;
use App\Storage\FileStorage;
use App\Repositories\BookRepository;
use App\Repositories\MemberRepository;
use App\Repositories\HistoryRepository;
use App\Validator\BookValidator;
use App\Validator\MemberValidator;

$bookStorage = new FileStorage(__DIR__ . '/../data/books.json');
$memberStorage = new FileStorage(__DIR__ . '/../data/members.json');
$historyStorage = new FileStorage(__DIR__ . '/../data/history.json');

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

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'] ?? '';

    if ($action === 'add') {

        $title = trim($_POST['title'] ?? '');
        $author = trim($_POST['author'] ?? '');
        $category = trim($_POST['category'] ?? '');
        $isbn = trim($_POST['isbn'] ?? '');

        try {

            $library->addBook(
                $title,
                $author,
                $category,
                $isbn
            );

            $success = 'Book added successfully.';
        } catch (Exception $e) {

            $error = $e->getMessage();
        }
    }

    if ($action === 'remove') {

        $isbn = trim($_POST['isbn'] ?? '');

        try {

            $library->removeBook($isbn);

            $success = 'Book removed successfully.';
        } catch (Exception $e) {

            $error = $e->getMessage();
        }
    }

    if ($action === 'borrow') {

        $isbn = trim($_POST['isbn'] ?? '');
        $id = trim($_POST['member_id'] ?? '');

        try {

            $library->borrowBook($isbn, $id);

            $success = 'Book borrowed successfully.';
        } catch (Exception $e) {

            $error = $e->getMessage();
        }
    }

    if ($action === 'return') {

        $isbn = trim($_POST['isbn'] ?? '');
        $id = trim($_POST['member_id'] ?? '');

        try {

            $library->returnBook($isbn, $id);

            $success = 'Book returned successfully.';
        } catch (Exception $e) {

            $error = $e->getMessage();
        }
    }
}

$query = trim($_GET['query'] ?? '');
$category = trim($_GET['category'] ?? '');
$available = $_GET['available'] ?? '';


$allBooks = $library->listBooks();
$categories = [];

for ($i = 0; $i < count($allBooks); $i++) {
    $categories[] = $allBooks[$i]->getCategory();
}

$categories = array_unique($categories);
sort($categories);


if ($query !== '') {
    $books = $library->searchBooks($query);
} elseif ($category !== '') {
    $books = $library->filterBooksByCategory($category);
} elseif ($available === '1') {
    $books = $library->filterAvailableBooks();
} else {
    $books = $library->listBooks();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Books - Library Management System</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="container">

        <h1>Books</h1>

        <p class="subtitle">
            Manage your library books.
        </p>

        <?php if ($success !== ''): ?>

            <p class="success">
                <?= htmlspecialchars($success) ?>
            </p>

        <?php endif; ?>

        <?php if ($error !== ''): ?>

            <p class="error">
                <?= htmlspecialchars($error) ?>
            </p>

        <?php endif; ?>

        <form method="GET" action="books.php">

            <input
                type="text"
                name="query"
                placeholder="Search books..."
                value="<?= htmlspecialchars($query) ?>">

            <button type="submit">
                Search
            </button>

        </form>

        <form method="GET" action="books.php">

            <select name="category">

                <option value="">All Categories</option>

                <?php for ($i = 0; $i < count($categories); $i++): ?>

                    <option
                        value="<?= htmlspecialchars($categories[$i]) ?>"
                        <?= $category === $categories[$i] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($categories[$i]) ?>
                    </option>

                <?php endfor; ?>
            </select>

            <button type="submit">
                Filter
            </button>

        </form>

        <h2>Add New Book</h2>

        <form method="POST" action="books.php">

            <input
                type="hidden"
                name="action"
                value="add">

            <input
                type="text"
                name="title"
                placeholder="Book title"
                required>

            <input
                type="text"
                name="author"
                placeholder="Author"
                required>

            <input
                type="text"
                name="category"
                placeholder="Category"
                required>

            <input
                type="text"
                name="isbn"
                placeholder="ISBN"
                required>

            <button type="submit">
                Add Book
            </button>

        </form>

        <form method="GET" action="books.php">

            <input type="hidden" name="available" value="1">

            <button type="submit">
                Available Books
            </button>

        </form>

        <?php if (count($books) === 0): ?>

            <p>No books found.</p>

        <?php else: ?>

            <div class="cards">

                <?php for ($i = 0; $i < count($books); $i++): ?>

                    <?php $book = $books[$i]; ?>

                    <div class="card">

                        <h2>
                            <?= htmlspecialchars($book->getTitle()) ?>
                        </h2>

                        <p>
                            Author:
                            <?= htmlspecialchars($book->getAuthor()) ?>
                        </p>

                        <p>
                            Category:
                            <?= htmlspecialchars($book->getCategory()) ?>
                        </p>

                        <p>
                            ISBN:
                            <?= htmlspecialchars($book->getIsbn()) ?>
                        </p>

                        <p>
                            Status:
                            <?= $book->isAvailable() ? 'Available' : 'Borrowed' ?>
                        </p>

                        <form method="POST" action="books.php">

                            <input
                                type="hidden"
                                name="action"
                                value="remove">

                            <input
                                type="hidden"
                                name="isbn"
                                value="<?= htmlspecialchars($book->getIsbn()) ?>">

                            <button type="submit">
                                Remove
                            </button>

                        </form>

                        <?php if ($book->isAvailable()): ?>

                            <form method="POST" action="books.php">

                                <input
                                    type="hidden"
                                    name="action"
                                    value="borrow">

                                <input
                                    type="hidden"
                                    name="isbn"
                                    value="<?= htmlspecialchars($book->getIsbn()) ?>">

                                <input
                                    type="text"
                                    name="member_id"
                                    placeholder="Member ID"
                                    required>

                                <button type="submit">
                                    Borrow
                                </button>

                            </form>

                        <?php else: ?>

                            <form method="POST" action="books.php">

                                <input
                                    type="hidden"
                                    name="action"
                                    value="return">

                                <input
                                    type="hidden"
                                    name="isbn"
                                    value="<?= htmlspecialchars($book->getIsbn()) ?>">

                                <input
                                    type="text"
                                    name="member_id"
                                    placeholder="Member ID"
                                    required>

                                <button type="submit">
                                    Return
                                </button>

                            </form>

                        <?php endif; ?>

                    </div>

                <?php endfor; ?>

            </div>

        <?php endif; ?>

    </div>

</body>

</html>
