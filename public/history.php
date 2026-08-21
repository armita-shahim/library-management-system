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

$memberId = trim($_GET['member_id'] ?? '');
$history = [];

if ($memberId !== '') {
    $history = $library->getHistoryForMember($memberId);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Member History - Library Management System</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="container">

        <h1>Member History</h1>

        <p class="subtitle">
            View borrowing and returning history for a member.
        </p>

        <form method="GET" action="history.php">

            <input
                type="text"
                name="member_id"
                placeholder="Member ID"
                value="<?= htmlspecialchars($memberId) ?>"
                required>

            <button type="submit">
                View History
            </button>

        </form>

        <?php if ($memberId !== '' && count($history) === 0): ?>

            <p>No history found for this member.</p>

        <?php elseif (count($history) > 0): ?>

            <div class="cards">

                <?php for ($i = 0; $i < count($history); $i++): ?>

                    <div class="card">

                        <?php foreach ($history[$i] as $key => $value): ?>

                            <p>
                                <strong>
                                    <?= htmlspecialchars(ucwords(str_replace('_', ' ', $key))) ?>:
                                </strong>

                                <?= htmlspecialchars((string) $value) ?>
                            </p>

                        <?php endforeach; ?>

                    </div>

                <?php endfor; ?>

            </div>

        <?php endif; ?>

    </div>

</body>

</html>
