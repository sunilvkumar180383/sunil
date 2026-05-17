<?php
/**
 * Plugin Name: Image Gallery Manager
 * Plugin URI: https://example.com
 * Description: Manage and display images in a responsive grid with hover effects
 * Version: 1.0.0
 * Author: Your Name
 * License: GPL v2 or later
 * Text Domain: image-gallery-manager
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'IGM_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'IGM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'IGM_PLUGIN_FILE', __FILE__ );

// Include required files
require_once IGM_PLUGIN_PATH . 'includes/class-gallery-manager.php';
require_once IGM_PLUGIN_PATH . 'includes/class-gallery-shortcode.php';
require_once IGM_PLUGIN_PATH . 'database/create-tables.php';
require_once IGM_PLUGIN_PATH . 'admin/admin-page.php';
require_once IGM_PLUGIN_PATH . 'admin/ajax-handlers.php';

class Image_Gallery_Manager_Plugin {
    
    public function __construct() {
        add_action( 'plugins_loaded', array( $this, 'init' ) );
        register_activation_hook( IGM_PLUGIN_FILE, array( $this, 'activate' ) );
        register_deactivation_hook( IGM_PLUGIN_FILE, array( $this, 'deactivate' ) );
    }
    
    public function init() {
        // Initialize components
        new Gallery_Manager();
        new Gallery_Shortcode();
        new Gallery_Admin_Page();
        new Gallery_AJAX_Handlers();
    }
    
    public function activate() {
        // Create database tables
        create_gallery_tables();
        flush_rewrite_rules();
    }
    
    public function deactivate() {
        flush_rewrite_rules();
    }
}

new Image_Gallery_Manager_Plugin();
?>