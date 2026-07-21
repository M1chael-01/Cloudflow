![a](https://github.com/user-attachments/assets/228eb438-bea0-42ae-b91a-50d387a7d317)

# CloudFlow

CloudFlow is a full-stack web application built as a graduation project. It combines an **e-commerce system**, **cloud file storage**, and an **administration panel** for managing users, products, and orders — all in a single, cohesive platform.

The project was developed using **PHP, JavaScript, CSS, and MySQL**, and demonstrates the ability to design and implement a complete web application from the ground up: database architecture, backend logic, authentication, and a functional, user-facing interface.

---

## 🚀 Project Highlights

- Designed and implemented a **relational database schema** for users, files, products, and orders.
- Built a **custom authentication system** (registration, login, session handling).
- Combined **three distinct application modules** (cloud storage, e-shop, admin panel) into one integrated system.
- Implemented **role-based access control**, separating regular user functionality from administrative privileges.
- Built entirely without a framework, showcasing solid fundamentals in **PHP, JavaScript, CSS, and MySQL**.

---

## Overview

CloudFlow demonstrates how multiple web application modules can be integrated into a single system:

- User authentication
- Cloud file management
- Product catalog
- Order system
- Administration dashboard

The project focuses on combining backend logic, database management, and a functional user interface into a working, real-world-style application.

---

## Features

### 👤 User Features
- User registration and login
- Personal dashboard
- File management (upload, view, download)
- Access to the e-shop
- Creating and tracking orders

### 🛒 E-shop
- Product catalog
- Add products to cart
- Create orders
- Order history for each user

### 🛠️ Admin Panel
Administrators have access to extended functionality:
- User management
- Product management
- Order management
- Dashboard overview with key metrics
- Full access to application data

---

## Technologies Used

| Category   | Technology       |
|------------|------------------|
| Backend    | PHP              |
| Frontend   | JavaScript       |
| Styling    | CSS              |
| Database   | MySQL            |
| Server     | Apache (`.htaccess`) |

---

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/username/cloudflow.git
   ```

2. **Move the project into your local server directory** (e.g., `htdocs` for XAMPP).

3. **Import the database**
   - Open phpMyAdmin (`http://localhost/phpmyadmin`)
   - Create a new database
   - Import the SQL file included in the project's `/sql` folder

4. **Configure the database connection**
   - Open the configuration file (e.g., `config.php`)
   - Set your database name, username, and password

5. **Start Apache and MySQL** in XAMPP (or your local server tool of choice).

6. **Run the application**
   ```
   http://localhost/cloudflow
   ```

---

## What This Project Demonstrates

CloudFlow was built to practice and showcase real-world web development skills, including:

- Structuring a multi-module application with clean separation of concerns
- Designing a normalized MySQL database
- Handling user sessions and access control securely
- Writing maintainable PHP backend code
- Building interactive frontend behavior with vanilla JavaScript

---

## License

This project was created for educational purposes as part of a graduation assignment.
