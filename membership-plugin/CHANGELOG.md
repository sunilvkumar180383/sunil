# Changelog

## Version 1.0.0 (Initial Release)

### Features
- User Registration & Email Verification
- User Authentication (Login/Logout)
- Password Management (Reset, Change)
- 4 Membership Tiers (Free Trial, Basic, Standard, Premium)
- 30-Day Free Trial Period
- Member Dashboard with File Management
- Embroidery File Upload/Download
- File Organization (Folders/Collections)
- Storage Limits per Tier
- File Encryption & Security
- Payment Gateway Integration
- Membership Management
- Account Settings & Profile Management
- Download History & Activity Logs
- Responsive Design
- HTTPS Security
- SQL Injection Prevention
- XSS Protection
- CSRF Protection

### Database Tables
- emb_users
- emb_tiers
- emb_memberships
- emb_files
- emb_folders
- emb_file_versions
- emb_download_logs
- emb_payments
- emb_activity_logs

### API Endpoints
- /api/register.php - User Registration
- /api/login.php - User Login
- /api/upload.php - File Upload
- /api/download.php - File Download
- /api/delete-file.php - File Deletion
- /api/get-files.php - Get User Files
- /api/get-plans.php - Get Membership Plans

### Templates
- registration/register.php
- dashboard/dashboard.php
- membership/pricing.php

### Stylesheets
- assets/css/style.css
- assets/css/dashboard.css
- assets/css/pricing.css

### JavaScript Files
- assets/js/dashboard.js

### Future Enhancements
- Two-Factor Authentication (2FA)
- Social Login Integration
- Advanced File Search
- API for Third-Party Integration
- Mobile App Support
