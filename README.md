# Library Management System

A library management system built with PHP as part of a backend internship task.

The project provides both a **CLI** and **web interface** for managing books, members, borrowing, returns, history, overdue books, and fines.

## Features

- Add, remove, search, and list books
- Filter books by category
- Add, remove, search, and list members
- Borrow and return books
- Automatic 14-day due dates
- Overdue book detection and fine calculation
- Member borrowing history
- JSON file-based storage
- Input validation and custom exceptions
- Operation logging
- CLI and web interfaces

## Project Structure

```
library-management-system/
│
├── data/
│   ├── books.json
│   ├── members.json
│   ├── history.json
│   └── library.log
│
├── public/
│   ├── index.php
│   ├── books.php
│   ├── members.php
│   ├── overdue.php
│   ├── history.php
│   └── style.css
│
├── src/
│   ├── Exceptions/
│   │   ├── BookAlreadyAvailableException.php
│   │   ├── BookAlreadyExistsException.php
│   │   ├── BookNotAvailableException.php
│   │   ├── BookNotFoundException.php
│   │   ├── InvalidBookDataException.php
│   │   ├── InvalidMemberDataException.php
│   │   ├── MemberAlreadyExistsException.php
│   │   ├── MemberHasBorrowedBooksException.php
│   │   ├── MemberNotBorrowBookException.php
│   │   └── MemberNotFoundException.php
│   │
│   ├── Models/
│   │   ├── Book.php
│   │   └── Member.php
│   │
│   ├── Repositories/
│   │   ├── AbstractRepository.php
│   │   ├── BookRepository.php
│   │   ├── HistoryRepository.php
│   │   └── MemberRepository.php
│   │
│   ├── Service/
│   │   └── Library.php
│   │
│   ├── Storage/
│   │   ├── FileStorage.php
│   │   └── StorageInterface.php
│   │
│   ├── Traits/
│   │   └── Loggable.php
│   │
│   └── Validator/
│       ├── BookValidator.php
│       └── MemberValidator.php
│
├── main.php
├── composer.json
└── README.md
```

## Validation

Book and member data are validated before being saved.

Custom exceptions are used to handle invalid data and library operations such as duplicate records, missing records, unavailable books, and invalid returns.

## How to Run

### CLI

```bash
php main.php
```

### Web

Start the built-in PHP server:

```bash
php -S localhost:8000 -t public
```

Then open:

```
http://localhost:8000
```

## Technologies

- PHP
- HTML5
- CSS3
- Composer
- JSON
