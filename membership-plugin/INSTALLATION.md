# Installation Guide - Embroidery File Management Membership Plugin

## Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Composer (optional)
- OpenSSL PHP extension
- PDO MySQL extension

## Installation Steps

### 1. Database Setup

Run the migration script to create all necessary tables:

```bash
mysql -u your_username -p your_database < membership-plugin/database/migrations.sql
```

Or manually execute the SQL in your database management tool (phpMyAdmin, etc.)

### 2. Plugin Configuration

Create a config file `config/database.php`:

```php
<?php
return [
    'host' => 'localhost',
    'user' => 'your_db_user',
    'password' => 'your_db_password',
    'database' => 'your_database',
    'charset' => 'utf8mb4'
];
?>
```

### 3. File Structure

Create necessary directories:

```bash
mkdir -p uploads/embroidery
mkdir -p logs
mkdir -p temp
chmod 755 uploads/embroidery logs temp
```

### 4. Copy Plugin Files

Copy all files from `membership-plugin/` to your web root:

```bash
cp -r membership-plugin/* /var/www/html/
```

### 5. Security Configuration

#### HTTPS Enforcement
Add to your `.htaccess`:
```apache
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

#### CSRF Protection
Include CSRF token in forms:
```php
<?php
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>
<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
```

## Testing

Run tests to ensure everything is working:

```bash
php -S localhost:8000
```

Navigate to:
- Registration: `http://localhost:8000/register.php`
- Login: `http://localhost:8000/login.php`
- Dashboard: `http://localhost:8000/dashboard.php`

## Support

For additional help, refer to:
- Database Schema: `database/migrations.sql`
- Class Documentation: `includes/` directory
- Template Examples: `templates/` directory
