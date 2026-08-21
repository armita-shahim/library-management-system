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

        $id = trim($_POST['id'] ?? '');
        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        try {

            $library->addMember(
                $id,
                $fullName,
                $email
            );

            $success = 'Member added successfully.';
        } catch (Exception $e) {

            $error = $e->getMessage();
        }
    }

    if ($action === 'remove') {

        $id = trim($_POST['id'] ?? '');

        try {

            $library->removeMember($id);

            $success = 'Member removed successfully.';
        } catch (Exception $e) {

            $error = $e->getMessage();
        }
    }
}

$query = trim($_GET['query'] ?? '');

if ($query !== '') {
    $members = $library->searchMembers($query);
} else {
    $members = $library->listMembers();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Members - Library Management System</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

    <div class="container">

        <h1>Members</h1>

        <p class="subtitle">
            Manage library members.
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

        <form method="GET" action="members.php">

            <input
                type="text"
                name="query"
                placeholder="Search members..."
                value="<?= htmlspecialchars($query) ?>">

            <button type="submit">
                Search
            </button>

        </form>

        <h2>Add New Member</h2>

        <form method="POST" action="members.php">

            <input
                type="hidden"
                name="action"
                value="add">

            <input
                type="text"
                name="id"
                placeholder="Member ID"
                required>

            <input
                type="text"
                name="full_name"
                placeholder="Full name"
                required>

            <input
                type="email"
                name="email"
                placeholder="Email"
                required>

            <button type="submit">
                Add Member
            </button>

        </form>

        <?php if (count($members) === 0): ?>

            <p>No members found.</p>

        <?php else: ?>

            <div class="cards">

                <?php for ($i = 0; $i < count($members); $i++): ?>

                    <?php $member = $members[$i]; ?>

                    <div class="card">

                        <h2>
                            <?= htmlspecialchars($member->getFullName()) ?>
                        </h2>

                        <p>
                            ID:
                            <?= htmlspecialchars($member->getId()) ?>
                        </p>
                        <p>
                            Email:
                            <?= htmlspecialchars($member->getEmail()) ?>
                        </p>

                        <p>
                            Borrowed books:
                            <?= count($member->getBorrowedBooks()) ?>
                        </p>

                        <form method="POST" action="members.php">

                            <input
                                type="hidden"
                                name="action"
                                value="remove">

                            <input
                                type="hidden"
                                name="id"
                                value="<?= htmlspecialchars($member->getId()) ?>">

                            <button type="submit">
                                Remove
                            </button>

                        </form>

                    </div>

                <?php endfor; ?>

            </div>

        <?php endif; ?>

    </div>

</body>

</html>
