# Freelance Marketplace

A database lab project built with PHP and MySQL. Clients post jobs and compare proposals, freelancers submit bids and work, and both roles can record ratings after completion. The database tracks simulated payments and activity logs.

**Group 22 | FAST University Peshawar | BCS-6A | DB Lab Project**

---

## 👥 Group Members
Farooq Umer, Shahmeer Amir and Hadi.

---

## ⚙️ Technologies Used
- **PHP** (Backend)
- **MySQL** (Database)
- **JavaScript** (Frontend interactions)
- **HTML / CSS / Bootstrap 5** (UI)

---

## 🚀 How to Run (XAMPP)

### Step 1 — Install XAMPP
Download from: https://www.apachefriends.org/

### Step 2 — Copy Project
Copy the `freelance_marketplace` folder to:
```
C:\xampp\htdocs\freelance_marketplace
```

### Step 3 — Import Database

Use a fresh local database. The script selects the database named `freelance` and drops its existing project tables before rebuilding them.
1. Open XAMPP → Start **Apache** and **MySQL**
2. Open browser → go to `http://localhost/phpmyadmin`
3. Click **New** → create database named `freelance`
4. Click **Import** → choose `sql/freelance.sql` → click **Go**

### Step 4 — Open the Project
Open browser → go to:
```
http://localhost/freelance_marketplace
```

---

## Local demo login credentials

### Admin
- **Email:** admin@freelance.com
- **Password:** password

### Test Client (register yourself)
- Go to `/index.php?page=register`
- Select role: **Client**

### Test Freelancer (register yourself)
- Go to `/index.php?page=register`
- Select role: **Freelancer**

---

## 📁 Project Structure
```
freelance_marketplace/
├── index.php                  # Main router
├── config/
│   ├── app.php                # App config, helpers, session
│   └── database.php           # DB connection
├── controllers/
│   ├── auth_controller.php    # Login, Register, Logout
│   ├── job_controller.php     # Post, Delete, Toggle jobs
│   ├── proposal_controller.php# Submit, Accept, Reject proposals
│   ├── rating_controller.php  # Submit ratings
│   ├── payment_controller.php # Simulate payments
│   ├── profile_controller.php # Update profile
│   └── admin_controller.php   # Admin actions
├── views/
│   ├── shared/                # Header, Footer, Flash, Home, Dashboard
│   ├── auth/                  # Login, Register, Forgot/Reset Password
│   ├── client/                # Dashboard, Post Job, My Jobs, Proposals, Payment
│   ├── freelancer/            # Dashboard, Browse Jobs, My Proposals, Submit Work
│   └── admin/                 # Dashboard, Users, Jobs, Skills, Logs
└── sql/
    └── freelance.sql          # Database schema + sample data
```

---

## ✅ Features
- User Registration & Login (Client / Freelancer / Admin)
- Client: Post jobs, view proposals, accept/reject, mark complete
- Freelancer: Browse jobs, submit proposals, submit work, withdraw
- Both: Rate each other after job completion
- Payment simulation page
- Password reset via token
- Admin: Manage users, jobs, skills, view logs
- Activity logging for all major actions

---

## 🗄️ Database Tables (13 tables)
1. `user` — All users
2. `client` — Client profiles
3. `freelancer` — Freelancer profiles
4. `skill` — Skills list
5. `freelancer_skill` — Freelancer skills (many-to-many)
6. `job` — Jobs posted by clients
7. `job_skill` — Skills required per job
8. `job_proposal` — Proposals submitted by freelancers
9. `job_rating` — Ratings after job completion
10. `job_payment` — Payment records
11. `job_payment_dispatch` — Payment dispatch records
12. `log` — System activity logs
13. `reset_password_token` — Password reset tokens
