# Library Management System

A complete, responsive, and beginner-friendly Library Management System built using Core PHP, MySQL, HTML5, CSS3, and JavaScript.

## Features
- **Admin Authentication & Registration**: Secure login and new admin user creation with PHP session handling and `password_hash()`.
- **Modern Dashboard UI**: Real-time stats, responsive layout, dark/light theme toggle.
- **Book Management**: Full CRUD operations with search, filters, and real-time available stock calculation.
- **Student Management**: Student registration and tracking of active/historical borrowing records.
- **Issue & Return System**: Smart issuing system enforcing limits, stock validation, automatic due date calculation, and overdue fine computation.
- **Analytics & Reports**: Visual charts using Chart.js powered by live MySQL data.

---

## Installation Guide (XAMPP)

1. **Start XAMPP Services**:
   - Open XAMPP Control Panel and start **Apache** and **MySQL**.

2. **Setup Project Folder**:
   - Extract the project folder `library_management` to your XAMPP `htdocs` directory:
     - **Windows**: `C:\xampp\htdocs\library_management`
     - **macOS**: `/Applications/XAMPP/htdocs/library_management`

3. **Import Database**:
   - Open `http://localhost/phpmyadmin/` in your browser.
   - Create a database named `library_management`.
   - Go to the **Import** tab, select `library_management/database/library_management.sql`, and click **Go**.

4. **Access Application**:
   - Navigate to: `http://localhost/library_management/`
   - Default login: `admin@library.com` / `admin123` (or register a new admin via the registration page).
