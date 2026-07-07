# Embroidery File Management - Membership Plugin

## Project Overview
A secure website for embroidery file management with user registration, membership tiers, and secure file upload/download functionality.

## Features

### 1. User Registration & Authentication
- Secure registration form
- Email verification
- Login/Logout functionality
- Password reset option

### 2. Membership Tiers (3 Types)
- **Free Trial** (30 Days)
  - 5 files max
  - 10 MB per file
  - Basic support
  
- **Basic Membership**
  - 50 files max
  - 50 MB per file
  - Email support
  - Monthly: $4.99 / Annually: $49.99

- **Standard Membership**
  - 500 files max
  - 500 MB per file
  - Priority email support
  - Monthly: $9.99 / Annually: $99.99

- **Premium Membership**
  - Unlimited files
  - 1 GB per file
  - 24/7 support + phone support
  - Monthly: $19.99 / Annually: $199.99

### 3. Member Dashboard
- File management (upload, download, delete)
- File organization (folders/collections)
- Usage statistics
- Account settings
- Subscription management

### 4. File Security
- Virus scanning
- Encryption at rest
- Secure file downloads with token-based access
- File versioning
- Backup management

## Technology Stack
- Backend: PHP 7.4+
- Database: MySQL 5.7+
- Frontend: HTML5, CSS3, JavaScript
- File Storage: Secure encrypted storage

## Plugin Structure
```
membership-plugin/
├── includes/
│   ├── class-membership-user.php
│   ├── class-membership-tier.php
│   ├── class-file-manager.php
│   └── class-payment-gateway.php
├── templates/
│   ├── dashboard/
│   ├── registration/
│   ├── membership/
│   └── file-upload/
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── database/
│   └── migrations.sql
├── api/
│   ├── register.php
│   ├── login.php
│   ├── upload.php
│   └── download.php
└── index.php
```

## Security Features
- HTTPS enforced
- CSRF protection
- SQL injection prevention (prepared statements)
- XSS protection
- File type validation
- Rate limiting on uploads
- Secure session management

## Installation & Setup
See INSTALLATION.md for detailed setup instructions.
