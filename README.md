# 🎓 Student Management System

### Bicol University | Web-Based Student Management System

A web-based Student Management System developed for Bicol University to simplify and organize student information. The system provides role-based access for administrators and users, allowing efficient student registration, record management, searching, and approval of student submissions.

## 📌 Project Overview

The Student Management System is designed to make managing student records more organized, accessible, and efficient. It reduces manual record handling by providing a centralized database where student information can be stored, viewed, and updated.

The system includes separate **Admin** and **User** dashboards with different access permissions.

## ✨ Key Features

### 👨‍💼 Admin Dashboard
- View and search student records
- Add new student information
- Edit and update existing student records
- Delete student records
- Approve or reject pending student submissions
- Manage student information through a centralized dashboard

### 👤 User Dashboard
- View and search student records
- View detailed student information
- Submit new student information
- Monitor pending submissions
- Access a user-friendly dashboard

### 🔐 Authentication
- User login and registration
- Role-based access for Admin and User
- Logout functionality

## 🛠️ Technologies Used

| Technology | Purpose |
|---|---|
| HTML5 | Web page structure |
| CSS3 | Styling and interface design |
| JavaScript | Frontend functionality |
| PHP | Backend processing |
| MySQL | Database management |
| XAMPP | Local development server |

## 📂 Project Structure

```text
Student_Management_System/
└── bu_sms_db/
    ├── images/
    ├── admin_dashboard.html
    ├── admin_add.html
    ├── admin_approvals.html
    ├── user_dashboard.html
    ├── user_add.html
    ├── user_pending.html
    ├── login.php
    ├── signup.php
    ├── auth.php
    ├── db_connect.php
    ├── get_students.php
    ├── add_student.php
    ├── update_student.php
    ├── delete_student.php
    ├── approve_student.php
    ├── reject_student.php
    ├── bu_sms_db.sql
    └── Other supporting files
```

## 🚀 Installation and Setup

1. Install **XAMPP** on your computer.
2. Clone the repository:

   `git clone https://github.com/emrd1819/Student_Management_System.git`

3. Copy the `bu_sms_db` folder into the XAMPP `htdocs` directory.
4. Start **Apache** and **MySQL** from the XAMPP Control Panel.
5. Open `http://localhost/phpmyadmin`.
6. Create a database named `bu_sms_db` and import the provided `bu_sms_db.sql` file.
7. Configure `db_connect.php` with your local database credentials.
8. Open the application in your browser:

   `http://localhost/bu_sms_db/login.php`

## 🎯 Project Objectives

- Simplify student registration and record management.
- Provide organized and centralized student information.
- Implement role-based access for administrators and users.
- Improve the efficiency of student record searching and updating.
- Support a structured student submission and approval process.

## 📚 Project Information

**Project Name:** Student Management System  
**Institution:** Bicol University  
**Project Type:** Academic Web Development Project  
**Repository:** https://github.com/emrd1819/Student_Management_System

---

*Developed as an academic project to demonstrate web development, database management, and role-based system functionality.*
