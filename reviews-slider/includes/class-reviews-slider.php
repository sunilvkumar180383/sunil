<?php
/**
 * Main Reviews Slider Class
 */

class Reviews_Slider {
    
    private static $instance = null;
    
    /**
     * Get singleton instance
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Enqueue styles and scripts
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
        
        // Load text domain
        add_action( 'init', array( $this, 'load_textdomain' ) );
    }
    
    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_scripts() {
        // Slick Slider CSS
        wp_enqueue_style( 
            'slick-slider-css',
            'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css',
            array(),
            '1.8.1'
        );
        
        wp_enqueue_style(
            'slick-slider-theme',
            'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css',
            array(),
            '1.8.1'
        );
        
        // Plugin styles
        wp_enqueue_style(
            'reviews-slider-style',
            REVIEWS_SLIDER_PLUGIN_URL . 'assets/css/reviews-slider.css',
            array( 'slick-slider-css' ),
            REVIEWS_SLIDER_VERSION
        );
        
        // Slick Slider JS
        wp_enqueue_script(
            'slick-slider-js',
            'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js',
            array( 'jquery' ),
            '1.8.1',
            true
        );
        
        // Plugin scripts
        wp_enqueue_script(
            'reviews-slider-script',
            REVIEWS_SLIDER_PLUGIN_URL . 'assets/js/reviews-slider.js',
            array( 'jquery', 'slick-slider-js' ),
            REVIEWS_SLIDER_VERSION,
            true
        );
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts() {
        wp_enqueue_style(
            'reviews-slider-admin',
            REVIEWS_SLIDER_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            REVIEWS_SLIDER_VERSION
        );
        
        wp_enqueue_script(
            'reviews-slider-admin',
            REVIEWS_SLIDER_PLUGIN_URL . 'assets/js/admin.js',
            array( 'jquery' ),
            REVIEWS_SLIDER_VERSION,
            true
        );
    }
    
    /**
     * Load plugin text domain
     */
    public function load_textdomain() {
        load_plugin_textdomain(
            'reviews-slider',
            false,
            dirname( plugin_basename( REVIEWS_SLIDER_PLUGIN_DIR . 'reviews-slider.php' ) ) . '/languages/'
        );
    }
    
    /**
     * Activation hook
     */
    public static function activate() {
        // Create plugin tables or initialize options
        Reviews_CPT::register_post_type();
        flush_rewrite_rules();
        
        // Set default options
        if ( ! get_option( 'reviews_slider_settings' ) ) {
            update_option( 'reviews_slider_settings', array(
                'items_to_show' => 3,
                'autoplay' => 'on',
                'autoplay_speed' => 5000,
                'arrows' => 'on',
                'dots' => 'on',
            ) );
        }
    }
    
    /**
     * Deactivation hook
     */
    public static function deactivate() {
        flush_rewrite_rules();
    }
}
