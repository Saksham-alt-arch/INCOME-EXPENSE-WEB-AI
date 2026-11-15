# Personal Finance Tracker

## Project Overview

Personal Finance Tracker is a web application that helps users manage their personal income and expenses. Users can create accounts, log in, add transactions, and monitor their financial balance.

## Technologies Used

- HTML – Page structure
- CSS – Website styling
- JavaScript – Client-side form validation
- PHP – Backend functionality
- MySQL – Database management

## Main Features

1. User registration and login.
2. Secure password hashing.
3. Personal financial dashboard.
4. Add income and expense transactions.
5. Edit existing transactions.
6. Delete transactions.
7. Automatically calculate total income, total expenses, and current balance.
8. Separate transaction records for each user.
9. Client-side and server-side form validation.
10. User logout and session protection.

## Database Structure

The project uses a MySQL database named `personal_finance_tracker`.

It contains two tables:

### users

Stores user account information:

- user_id
- username
- email
- password_hash
- created_at

### transactions

Stores financial transaction information:

- transaction_id
- user_id
- type
- amount
- description
- transaction_date
- created_at

## Project Folder Structure

```text
INCOME-EXPENSE-WEB-AI/
│
├── css/
│   └── style.css
│
├── js/
│   └── validation.js
│
├── database/
│   └── personal_finance_tracker.sql
│
├── index.php
├── register.php
├── login.php
├── logout.php
├── dashboard.php
├── transaction.php
├── edit_transaction.php
├── delete_transaction.php
├── db.php
└── README.md
```

## How to Run the Project

1. Install XAMPP or a compatible PHP and MySQL environment.
2. Start the PHP server and MySQL server.
3. Create the database using `database/personal_finance_tracker.sql`.
4. Configure the database connection in `db.php`.
5. Open the project in your browser.

## Project Goal

The goal of this project is to develop a simple personal finance management system that allows users to organize and track their income and expenses through a web application.
