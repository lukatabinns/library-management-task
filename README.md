# 📚 Library Management System (Laravel 11 API)

A RESTful API built with Laravel 11, implementing JWT authentication, role-based access, and book borrowing/returning functionality.

This system supports user registration, librarian book management, and borrowing features, ideal for library or educational environments.

# Features

## User Management

User registration (name, email, password, role)

JWT Authentication (login, logout, token refresh)

Role-based authorization:

Librarian: manage books (create/update/delete)

User: view/search books, borrow/return books

## Book Management

CRUD operations for books:

id, title, author, genre, isbn (unique), published_at, copies_total, copies_available

Search books by title, author, genre

Pagination for book lists

## Borrowing / Returning

Borrow books if copies_available > 0

Prevent borrowing same book twice concurrently

Configurable max active borrows per user (default: 5)

Borrowing duration configurable (default: 14 days)

Return books and increment available copies

# Requirements

PHP 8.1+

Composer 2.2+

MySQL / MariaDB

Laravel 11

JWT Authentication via tymon/jwt-auth

# Installation

1. Clone the repository:

git clone https://github.com/lukatabinns/library-management.git
cd library-management

2. Install dependencies:

composer install

3. Copy .env file:

cp .env.example .env

4. Generate application key:

php artisan key:generate

5. Generate JWT secret:

php artisan jwt:secret

6. Run database migrations:

php artisan migrate

7. (Optional) Seed initial data:

php artisan db:seed

# Configuration

Update .env with your database and JWT settings:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=library
DB_USERNAME=root
DB_PASSWORD=

APP_KEY=base64:...
JWT_SECRET=base64:...

# Running the Application

Start the Laravel development server:

php artisan serve

The API will be available at:

http://127.0.0.1:8000

# Testing

Run PHPUnit tests:

php artisan test

# License

This project is licensed under the MIT License.
