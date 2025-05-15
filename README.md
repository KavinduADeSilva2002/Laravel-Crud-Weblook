# Laravel CRM Project (Vue.js Frontend)

This is a demo **Customer Relationship Management (CRM)** system developed using **Laravel 10** (PHP) for the backend and **Vue.js** for the frontend, integrated with **MySQL**, **Mailtrap**, and **Stripe**.

## 🚀 Features

- **Admin Authentication**
  - Admin Login/Register using Laravel Breeze or JetStream
  - Email confirmation after registration via MailTrap

- **Dashboard**
  - Simple dashboard with navigation and user profile management
  - Logout functionality

- **Customer Management**
  - Create, update, change status, and delete customers

- **Proposal Management**
  - Create proposals linked to customers
  - Edit, update status, and delete proposals

- **Invoice Management**
  - Create invoices linked to customers
  - Edit, delete, update status
  - Send invoice via email with payment button

- **Email Integration**
  - Uses **MailTrap** for sending invoice emails

- **Stripe Payment Integration**
  - Secure Stripe payment gateway
  - Auto status update on successful payment

- **Transaction Tracking**
  - View all customer-related transactions in the admin dashboard

## 🛠️ Tech Stack

- **Backend:** Laravel 12 (PHP)
- **Frontend:** Vue.js (InertiaJS optional)
- **Database:** MySQL
- **Authentication:** Laravel Breeze or JetStream
- **Email Service:** MailTrap
- **Payment Gateway:** Stripe

## 📦 Installation Guide

1. **Clone the Repository**
   ```bash
   git clone https://github.com/your-username/laravel-crm.git
   cd laravel-crm
