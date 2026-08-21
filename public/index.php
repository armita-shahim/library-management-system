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

$books = $library->listBooks();
$members = $library->listMembers();
$overdueBooks = $library->getOverdueBooks();

$totalBooks = count($books);
$totalMembers = count($members);
$totalOverdue = count($overdueBooks);

$borrowedBooks = 0;

for ($i = 0; $i < count($books); $i++) {

    if (!$books[$i]->isAvailable()) {
        $borrowedBooks++;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Library Management System</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="container">

        <h1>Library Management System</h1>

        <p class="subtitle">
            Manage your library from one place.
        </p>

        <div class="cards">

            <div class="card">

                <h2>
                    <?= $totalBooks ?>
                </h2>

                <p>
                    Total Books
                </p>

            </div>

            <div class="card">

                <h2>
                    <?= $borrowedBooks ?>
                </h2>

                <p>
                    Borrowed Books
                </p>

            </div>

            <div class="card">

                <h2>
                    <?= $totalMembers ?>
                </h2>

                <p>
                    Members
                </p>

            </div>

            <div class="card">

                <h2>
                    <?= $totalOverdue ?>
                </h2>

                <p>
                    Overdue Books
                </p>

            </div>

        </div>

        <h2>Navigation</h2>

        <p>
            <a href="books.php">
                Manage Books
            </a>
        </p>

        <p>
            <a href="members.php">
                Manage Members
            </a>
        </p>

        <p>
            <a href="overdue.php">
                View Overdue Books
            </a>
        </p>

        <p>
            <a href="history.php">
                View Member History
            </a>
        </p>

    </div>

</body>

</html>
