# Simple Payroll Management System (SPMS)

A web-based Payroll Management System built with PHP and MySQL, deployed with Docker on Render and connected to Aiven Cloud MySQL.

---

## 🌐 Live Demo & Access

* **Live URL:** [https://online-payment-hub-m0mb.onrender.com/admin/login.php](https://online-payment-hub-m0mb.onrender.com/admin/login.php)

### 🔐 Admin Login Credentials
| Field | Value |
| :--- | :--- |
| **Username** | `admin` |
| **Password** | `admin123` |

---

## ✨ Features

- **Dashboard:** Overview of active employees, departments, and payroll summaries.
- **Employee Management:** Add, edit, view, and delete employee profiles with photos and monthly salaries.
- **Department & Designation:** Manage company departments and employee designations.
- **Payroll Processing:** Automated payroll calculations including present days, overtime, undertime/late deductions, gross pay, and net pay.
- **Dynamic Tax Table:** Configurable tax brackets (fixed tax + percentage over) effective by dates.
- **Earnings & Deductions:** Support for custom earnings (allowances) and deductions (Cash Advance, SSS, HMDF, PhilHealth).
- **User Management:** Admin account creation and credential management.

---

## 🛠️ Technology Stack

- **Backend:** PHP 8.1 (OOP & Procedural, MySQLi with SSL)
- **Database:** Aiven Cloud MySQL 8.4 (with TLS/SSL encryption)
- **Frontend:** Bootstrap 5, jQuery 3.6, FontAwesome, DataTables, Summernote Editor
- **Deployment & Containerization:** Docker, Apache 2.4, Render Web Service

---

## 🚀 Environment Variables (Render)

When deploying to Render, the following environment variables are configured:

| Variable | Description | Example |
| :--- | :--- | :--- |
| `DB_HOST` | Aiven MySQL Hostname | `mysql-xxxx.aivencloud.com` |
| `DB_PORT` | Aiven MySQL Port | `12893` |
| `DB_USER` | MySQL Username | `avnadmin` |
| `DB_PASSWORD` | MySQL Password | `AVNS_...` |
| `DB_NAME` | Database Name | `defaultdb` |
| `DB_SSL` | Enable SSL for MySQL | `true` |
| `PORT` | Web Server Port (Render) | `80` |

---

## 📁 Project Structure

```text
├── Dockerfile              # Docker container build definition
├── docker-entrypoint.sh    # Container startup and database initialization script
├── render.yaml             # Render deployment blueprint
├── index.php               # Root entrypoint redirecting to /admin/
├── README.md               # Project documentation and credentials
└── spms/                   # Application source code
    ├── Actions.php         # Backend CRUD controller & API handler
    ├── DBConnection.php    # Database connection with cloud SSL auto-detect
    ├── test_db.php         # Database connection diagnostic tool
    ├── admin/              # Admin panel views and controllers
    ├── db/                 # Database schema (spms_db.sql)
    └── upload/             # Employee uploaded photos
```

---

## 📜 License
This project is open-source and available for educational and commercial use.
