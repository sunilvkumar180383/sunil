<?php
/**
 * Plugin Name: Reviews Slider
 * Plugin URI: https://example.com/reviews-slider
 * Description: Display customer reviews in an elegant slider showing 3 reviews at a time
 * Version: 1.0.0
 * Author: Sunil Kumar
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: reviews-slider
 * Domain Path: /languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin constants
define( 'REVIEWS_SLIDER_VERSION', '1.0.0' );
define( 'REVIEWS_SLIDER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'REVIEWS_SLIDER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Include required files
require_once REVIEWS_SLIDER_PLUGIN_DIR . 'includes/class-reviews-slider.php';
require_once REVIEWS_SLIDER_PLUGIN_DIR . 'includes/class-reviews-cpt.php';
require_once REVIEWS_SLIDER_PLUGIN_DIR . 'includes/class-reviews-shortcode.php';
require_once REVIEWS_SLIDER_PLUGIN_DIR . 'includes/class-reviews-admin.php';

// Initialize the plugin
add_action( 'plugins_loaded', array( 'Reviews_Slider', 'get_instance' ) );
add_action( 'init', array( 'Reviews_CPT', 'get_instance' ) );
add_action( 'init', array( 'Reviews_Shortcode', 'get_instance' ) );
add_action( 'admin_menu', array( 'Reviews_Admin', 'get_instance' ) );

// Activation and deactivation hooks
register_activation_hook( __FILE__, array( 'Reviews_Slider', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Reviews_Slider', 'deactivate' ) );
