<?php
/**
 * Membership Plugin - Main Entry Point
 * Embroidery File Management System
 */

// Start session
session_start();

// Configuration
$config = [
    'site_name' => 'Embroidery File Manager',
    'version' => '1.0.0',
    'db_host' => 'localhost',
    'db_user' => 'root',
    'db_password' => '',
    'db_name' => 'embroidery_db',
    'upload_dir' => __DIR__ . '/uploads/',
    'temp_dir' => __DIR__ . '/temp/'
];

// Database Connection
try {
    $db = new PDO(
        'mysql:host=' . $config['db_host'] . ';dbname=' . $config['db_name'] . ';charset=utf8mb4',
        $config['db_user'],
        $config['db_password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die('Database Connection Error: ' . $e->getMessage());
}

// Include Plugin Classes
require_once __DIR__ . '/includes/class-membership-user.php';
require_once __DIR__ . '/includes/class-membership-tier.php';
require_once __DIR__ . '/includes/class-file-manager.php';

// Initialize Plugin Classes
$membership_user = new Membership_User($db);
$membership_tier = new Membership_Tier($db);
$file_manager = new File_Manager($db, $config['upload_dir']);

// Helper Functions
function is_user_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function get_current_user_id() {
    return $_SESSION['user_id'] ?? null;
}

function require_login() {
    if (!is_user_logged_in()) {
        header('Location: /register.php');
        exit;
    }
}

// Plugin Version
define('MEMBERSHIP_PLUGIN_VERSION', $config['version']);
define('MEMBERSHIP_PLUGIN_DIR', __DIR__);

// Return configuration for use in other files
return [
    'db' => $db,
    'config' => $config,
    'membership_user' => $membership_user,
    'membership_tier' => $membership_tier,
    'file_manager' => $file_manager
];
?>