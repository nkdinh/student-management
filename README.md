# Student Management System

This guide will help you set up the Student Management System on your local machine. The setup instructions differ based on your MySQL configuration.

## Prerequisites

- Node.js (v16 or higher)
- MySQL Server or XAMPP
- MySQL Workbench (mandatory for devices with MySQL Server installed)

## Setup Instructions

### For Devices with MySQL Server Installed

1. Clone the repository:
   git clone https://github.com/your-repo/student-management.git
2. Navigate to the project directory:
   cd student-management
3. Install dependencies:
   composer install
4. Create a new MySQL database:
   CREATE DATABASE student_management;
5. Configure the database connection in .env :
   DB_HOST=localhost
   DB_USER=root
   DB_PASSWORD=your_password
   DB_NAME=student_management
6. Run migrations:
   symfony doctrine:migrations:migrate
7. Start the server:
   symfony server:start
8. Signs that you have successfully installed the project:
   "[OK] Web server listening
   The Web server is using PHP CGI 8.2.12  
    https://127.0.0.1:8000"

### For Devices with XAMPP Installed

1. Start XAMPP, ensure Apache and MySQL are running
2. Access PHPMyAdmin at http:localhost/phpmyadmin/
3. Install dependencies:
   composer install
4. Create a new database named student_management
5. Import the database schema from the provided SQL file (student_management.sql)
6. Configure the database connection in .env :
   DB_HOST=localhost
   DB_USER=your_username
   DB_PASSWORD=your_password
   DB_NAME=student_management
7. Run migrations:
   symfony doctrine:migrations:migrate
8. Start the server:
   symfony server:start

### Troubleshooting

- If you encounter connection issues, ensure MySQL is running
- For XAMPP users, verify the MySQL port
- Check your firewall settings if you can't connect to the database
