# Student Portal Dashboard

A responsive student management portal built with PHP and MySQL. The application provides authentication, student registration, profile management, search, CRUD operations, password management, profile photo uploads, and a dashboard with student statistics.

## Features

- Secure login and session-based authentication
- Student registration with profile photo upload
- Student search by name, email, and phone
- View, edit, and delete student records
- Dashboard statistics
- Profile management and password reset
- MySQL database integration using prepared statements
- Responsive dashboard UI with reusable PHP components

## Tech Stack

- PHP
- MySQL / MySQLi
- HTML5
- CSS3
- Font Awesome
- Object-oriented PHP

## Local Setup

### Requirements

- PHP 7.4+ (PHP 8.x recommended)
- MySQL / MariaDB
- XAMPP, WAMP, or Laragon

### Installation

1. Clone or download this repository.
2. Place the project inside your local web server directory, such as `htdocs` for XAMPP.
3. Open phpMyAdmin and import `database.sql`.
4. If your local MySQL credentials differ, update `includes/connection.php`.
5. Open:

   `http://localhost/student-portal-dashboard/login.php`

### Demo Login

- **Username:** `demo`
- **Password:** `demo123`

## Project Structure

```text
student-portal-dashboard/
├── css/
├── images/
├── includes/
├── uploads/
├── database.sql
├── dashboard.php
├── login.php
├── register.php
├── manage_students.php
├── view_student.php
├── edit.php
├── reset-password.php
└── README.md
```

## Security Notes

- Database queries use MySQLi prepared statements where user input is involved.
- Passwords are stored using PHP's `password_hash()` and verified with `password_verify()`.
- Uploaded profile images are validated by MIME type and file size.
- Production deployments should additionally use CSRF protection, POST-based destructive actions, rate limiting, environment-based database credentials, and HTTPS.

## Portfolio

This project demonstrates practical PHP/MySQL CRUD development, authentication, database integration, form handling, file uploads, and dashboard UI development.
