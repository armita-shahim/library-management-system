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

$overdueFines = $library->getOverdueFines();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Overdue Books - Library Management System</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="container">

        <h1>Overdue Books</h1>

        <p class="subtitle">
            Books that have passed their due date.
        </p>

        <?php if (count($overdueFines) === 0): ?>

            <p>No overdue books.</p>

        <?php else: ?>

            <div class="cards">

                <?php for ($i = 0; $i < count($overdueFines); $i++): ?>

                    <?php $fine = $overdueFines[$i]; ?>

                    <div class="card">

                        <h2>
                            <?= htmlspecialchars($fine['title']) ?>
                        </h2>

                        <p>
                            ISBN:
                            <?= htmlspecialchars($fine['isbn']) ?>
                        </p>

                        <p>
                            Fine:
                            <?= htmlspecialchars($fine['fine']) ?>
                            Toman
                        </p>

                    </div>

                <?php endfor; ?>

            </div>

        <?php endif; ?>

    </div>

</body>

</html>
