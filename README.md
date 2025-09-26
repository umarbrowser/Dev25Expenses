# 📌 Dev25Expenses

**Development Timeline (Team B: Saifullah & Umar)**  
A simple PHP + MySQL expense tracker with CSV import/export, built step by step over 4 weeks.

---

## 🚀 Project Overview
Dev25Expenses is a lightweight web app for tracking expenses.  
The project is structured around a **4-week development plan**, gradually evolving from environment setup to a polished final product.

---

# Dev25Expenses - Expense Tracker

A complete PHP-based expense tracking application developed by Team B (Saifullah & Umar) for the Dev25 program.

## Features

- User registration and authentication
- Add, edit, delete expenses
- Expense categorization
- CSV import/export functionality
- Responsive design
- Expense statistics and reporting
- Secure password hashing

## Installation

1. **Prerequisites**
   - PHP 7.4 or higher
   - MySQL/MariaDB
   - Web server (Apache/Nginx)

2. **Setup Steps**
   - Clone or download the project files to your web server directory
   - Create a MySQL database named `dev25expenses`
   - Update database credentials in `config/database.php`
   - The application will automatically create the required tables

3. **Access the Application**
   - Navigate to the project directory in your web browser
   - Register a new account or use the default credentials
---

## 🗓 Development Timeline

### Week 1: Environment Setup & Database Design
**🎯 Goal:** Lay the foundation with a clean, reliable setup.  

- **Environment Setup**
  - *Saifullah:* Install & configure PHP (XAMPP).  
  - *Umar:* Set up Git & GitHub repo.  

- **Database Design**
  - Both: Design ERD (users → expenses).  
  - Define schema, create MySQL/MariaDB DB + migrations.  

✅ **Deliverable:** Fully working environment + DB schema pushed.

---

### Week 2: Framework & UI Foundation
**🎯 Goal:** Build the skeleton + first UI components.  

- **Framework Setup**
  - *Saifullah:* Routing structure (MVC).  
  - *Umar:* Database connectivity layer.  

- **UI Development**
  - Responsive templates for:
    - User Registration & Login (with validation)  
    - Expense Dashboard (summary + overview)  
    - Expense Entry (table with add/edit/remove)  

✅ **Deliverable:** Navigable pages with static UI + routing.

---

### Week 3: Core Functionality + CSV Integration
**🎯 Goal:** Add backend logic, dynamic data, and CSV support.  

- **Expense Tracking Logic**
  - *Saifullah:* Backend controllers & models for CRUD.  
  - *Umar:* SQL queries for CRUD + DB integration.  

- **Dynamic Display**
  - Dashboard & list views show live expense data.  

- **CSV Import/Export**
  - Import with validation.  
  - Export full expense dataset.  

✅ **Deliverable:** Functional tracker with CSV support.

---

### Week 4: Optimization & Testing
**🎯 Goal:** Refine, test thoroughly, and prepare final app.  

- **Optimization**
  - Refactor codebase (readability + maintainability).  
  - Improve query performance & error handling.  

- **Testing**
  - Backend: Unit tests for CRUD & CSV.  
  - Frontend: Usability + responsive tests.  
  - CSV: Validate with real data.  

✅ **Deliverable:** Polished, stable, ready-to-demo app.

---

## 🛠 Tech Stack
- **Backend:** PHP (MVC, XAMPP)  
- **Database:** MySQL / MariaDB  
- **Frontend:** HTML, CSS, JavaScript (responsive UI)  
- **Version Control:** Git & GitHub  

---

## 📂 Repo Structure
