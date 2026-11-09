# LICHFL Leads Generation & Call Center Management System

A web-based Lead Management and Call Center Tracking System built for managing customer leads, loan applications, telecalling workflows, agent daily activity logs, field visits, and chat interactions.

---

## 🚀 Key Features

### 👤 Telecaller / Agent Portal
- **Dashboard & Performance**: Real-time summary of assigned leads, call statuses, and follow-ups.
- **Lead & Customer Registration**: Add and update customer records, loan requirements, and contact details.
- **Complaint & Service Request Tracking**: Register complaints, update statuses (In-Progress, Resolved, Closed), and record status histories.
- **Field Visit & Movement Logging**: Track on-field visits, agent movements, and daily reports.
- **Internal & Customer Chat**: Built-in messaging platform for internal team coordination and customer inquiries.
- **Selfie / Attendance Verification**: Daily check-in and location selfie logging for agents.

### 🛡️ Admin Management Console
- **Role & User Management**: Admin and agent access controls.
- **Lead Assignment**: Distribute incoming leads and customer queries among agents.
- **Comprehensive Status Tracking**: Track all loan types, statuses, and agent remarks.
- **Audit & Monitoring**: Monitor agent chats, attendance/selfie logs, and daily performance metrics.

---

## 🛠️ Tech Stack

- **Backend**: PHP (PHP 7.4+ / 8.x)
- **Database**: MySQL / MariaDB
- **Frontend**: HTML5, CSS3, JavaScript, jQuery, Bootstrap, Pace JS, MetisMenu, Lobibox
- **Web Server**: Apache (XAMPP / WampServer / LAMP / cPanel)

---

## 📋 Prerequisites

- PHP 7.4 or higher (PHP 8.x recommended)
- MySQL 5.7+ or MariaDB 10.4+
- Web server (Apache via XAMPP, WampServer, or Linux LAMP)

---

## ⚙️ Installation & Setup

### 1. Clone the Repository
Clone this repository to your local web root directory (e.g., `C:/xampp/htdocs/call` for XAMPP):
```bash
git clone https://github.com/ajayrathod-dev/Lichfl-leads-generation-system.git
```

### 2. Database Setup
1. Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
2. Create a new database named `call_center`:
   ```sql
   CREATE DATABASE call_center CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
3. Import the database schema from `call_center.sql/call_center.sql` (or `call_center (2).sql`).
4. (Optional) Run any pending migration scripts located in the `migrations/` folder in numerical order:
   - `migrations/001_add_loan_type.sql`
   - `migrations/002_create_loan_status_history.sql`

### 3. Database Configuration
1. Open `connection.php` (or copy from `connection.example.php`):
   ```php
   $servername = "localhost";
   $username   = "root";          // Your MySQL username
   $password   = "";              // Your MySQL password
   $dbname     = "call_center";   // Database name
   ```
2. Also check `admin/connection.php` if configuring separate database credentials for the admin portal.

### 4. Run the Application
1. Start **Apache** and **MySQL** in XAMPP Control Panel.
2. Access the agent portal:
   ```
   http://localhost/call/login.php
   ```
3. Access the admin portal:
   ```
   http://localhost/call/admin/login.php
   ```

---

## 📂 Project Structure

```
├── admin/                  # Admin portal dashboard and management tools
│   ├── ajax_actions.php    # AJAX endpoints for admin operations
│   ├── all_loans_status.php# Loan status reports and filtering
│   ├── assign_customers.php# Lead distribution to agents
│   └── ...
├── assets/                 # CSS, JavaScript, plugins, and fonts
├── classes/                # Shared helper modules and utility classes
├── migrations/             # SQL migration files for database updates
├── uploads/                # User uploads directory
├── api.php                 # Core REST/AJAX endpoints
├── chat.php                # Agent chat interface
├── complaint.php           # Customer complaint management
├── connection.php          # Database connection configuration
├── connection.example.php  # Sample database configuration template
├── daily_report.php        # Daily agent reporting
├── dashboard.php           # Agent dashboard
├── field_visit.php         # Field visit tracking
├── info.php                # Customer/lead details view
├── login.php               # Agent login authentication
└── README.md               # Documentation
```

---

## 🔒 Security Best Practices

- Keep database credentials secure; do not commit production passwords to public repositories.
- Use `connection.example.php` as a template for public distribution.
- Ensure write permissions are restricted on the `uploads/` directory to prevent unauthorized script execution.

---

## 📄 License

This project is proprietary and confidential. Unauthorized copying, distribution, or use is strictly prohibited.

