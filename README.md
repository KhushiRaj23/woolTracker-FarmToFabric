# 🐑 Wool Tracker - Farm To Fabric

> A Laravel-based web application to streamline and enhance transparency in the wool supply chain through real-time tracking and role-based management.

---

## 📖 Project Overview

**Wool Tracker** is designed to help **farmers**, **processors**, **distributors**, and **retailers** manage wool production efficiently. It offers real-time updates, centralized dashboards, secure access, and role-specific functionalities — aiming to minimize delays and increase trust across the supply chain.

---

## 📋 Table of Contents

- [Project Overview](#-project-overview)
- [Features](#-features)
- [User Roles & Permissions](#-user-roles--permissions)
- [Technology Stack](#-technology-stack)
- [Installation](#-installation)
- [Usage](#-usage)
- [Future Scope](#-future-scope)
- [Appendices](#-appendices)

---

## ✨ Features

- 🐑 Farm Registration
- 📦 Wool Batch Creation and Tracking
- 🏭 Batch Processing and Wool Grading
- 🛍️ Product Creation and Order Management
- 📊 Role-Specific Dashboards
- 🔒 Secure Authentication with Role-Based Access
- 📧 Email Verification for Users
- 📈 Real-time Updates Across the Supply Chain

---

## 👤 User Roles & Permissions

| Operation             | Admin | Farmer | Processor | Distributor | Retailer |
|------------------------|:-----:|:------:|:---------:|:-----------:|:--------:|
| Register Farm          |  ✅    |  ✅    |    ❌     |      ❌     |    ❌    |
| Create Wool Batch      |  ✅    |  ✅    |    ❌     |      ❌     |    ❌    |
| Process and Grade Wool |  ✅    |  ❌    |    ✅     |      ❌     |    ❌    |
| Create Products/Orders |  ✅    |  ❌    |    ❌     |     ✅      |    ❌    |
| Claim Orders           |  ✅    |  ❌    |    ❌     |      ❌     |    ✅    |
| View Dashboard         |  ✅    |  ✅    |    ✅     |     ✅      |    ✅    |

---

## 🛠️ Technology Stack

- **Backend**: [Laravel 10](https://laravel.com/)
- **Frontend**: [Blade Templates], [TailwindCSS]/[Bootstrap]
- **Database**: SQLite (Development Mode) / MySQL (Production Ready)
- **Authentication**: Laravel Breeze / Fortify
- **Hosting**: GitHub + (Planned for Render/Netlify + Laravel API)
- **Communication**: HTTPS Secure Protocol

---

## ⚙️ Installation

1. Clone the repository
   ```bash
   git clone https://github.com/KhushiRaj23/woolTracker-FarmToFabric.git
   cd woolTracker-FarmToFabric
2. Install Composer dependencies
   ```bash
   composer install
3. Install NPM dependencies
   ```bash
   npm install && npm run build
4. Configure .env
   - Copy .env.example to .env
   - Set database as SQLite
   - Generate app key
    ```bash
    php artisan key:generate
5. Run database migrations
   ```bash
   php artisan migrate
6. start the serve
   ```bash
   php artisan serve

---

## 💻 Screenshot

![image](https://github.com/user-attachments/assets/feb764a5-94c7-464f-8d0b-78d937d57aec)
![image](https://github.com/user-attachments/assets/b18442ce-0777-4994-8bb2-ba2ea1f98dff)
![image](https://github.com/user-attachments/assets/92b25068-007d-4390-b2e2-b3794eca414e)


---

## 🚀 Usage

- Register as a specific user (Farmer, Processor, Distributor, Retailer).
- Perform operations based on role permissions (like batch creation, grading, product creation, order claiming).
- Admin manages user approvals and monitors overall activities via Dashboard.

---

## 🔮 Future Scope

- Integration with IoT Sensors for real-time wool batch data.
- Integration with Blockchain for immutable tracking records.
- Advanced analytics dashboards for stakeholders.
- Notifications and messaging between supply chain actors.

---
