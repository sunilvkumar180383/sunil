<?php
/**
 * Admin Settings Page
 */

class Reviews_Admin {
    
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
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=review',
            __( 'Reviews Slider Settings', 'reviews-slider' ),
            __( 'Settings', 'reviews-slider' ),
            'manage_options',
            'reviews-slider-settings',
            array( $this, 'render_settings_page' )
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting( 'reviews_slider_settings_group', 'reviews_slider_settings' );
    }
    
    /**
     * Render settings page
     */
    public function render_settings_page() {
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'You do not have permission to access this page.', 'reviews-slider' ) );
        }
        
        $settings = get_option( 'reviews_slider_settings', array() );
        ?>
        <div class="wrap">
            <h1><?php _e( 'Reviews Slider Settings', 'reviews-slider' ); ?></h1>
            
            <form method="post" action="options.php">
                <?php settings_fields( 'reviews_slider_settings_group' ); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="items_to_show"><?php _e( 'Items to Show', 'reviews-slider' ); ?></label></th>
                        <td>
                            <input type="number" id="items_to_show" name="reviews_slider_settings[items_to_show]" min="1" max="6" value="<?php echo esc_attr( isset( $settings['items_to_show'] ) ? $settings['items_to_show'] : 3 ); ?>" />
                            <p class="description"><?php _e( 'Number of reviews to show at a time (1-6)', 'reviews-slider' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="autoplay"><?php _e( 'Autoplay', 'reviews-slider' ); ?></label></th>
                        <td>
                            <input type="checkbox" id="autoplay" name="reviews_slider_settings[autoplay]" value="on" <?php checked( isset( $settings['autoplay'] ) ? $settings['autoplay'] : '', 'on' ); ?> />
                            <label for="autoplay"><?php _e( 'Enable autoplay', 'reviews-slider' ); ?></label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="autoplay_speed"><?php _e( 'Autoplay Speed (ms)', 'reviews-slider' ); ?></label></th>
                        <td>
                            <input type="number" id="autoplay_speed" name="reviews_slider_settings[autoplay_speed]" min="1000" step="1000" value="<?php echo esc_attr( isset( $settings['autoplay_speed'] ) ? $settings['autoplay_speed'] : 5000 ); ?>" />
                            <p class="description"><?php _e( 'Milliseconds between slides', 'reviews-slider' ); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="arrows"><?php _e( 'Show Arrows', 'reviews-slider' ); ?></label></th>
                        <td>
                            <input type="checkbox" id="arrows" name="reviews_slider_settings[arrows]" value="on" <?php checked( isset( $settings['arrows'] ) ? $settings['arrows'] : '', 'on' ); ?> />
                            <label for="arrows"><?php _e( 'Show navigation arrows', 'reviews-slider' ); ?></label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="dots"><?php _e( 'Show Dots', 'reviews-slider' ); ?></label></th>
                        <td>
                            <input type="checkbox" id="dots" name="reviews_slider_settings[dots]" value="on" <?php checked( isset( $settings['dots'] ) ? $settings['dots'] : '', 'on' ); ?> />
                            <label for="dots"><?php _e( 'Show pagination dots', 'reviews-slider' ); ?></label>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(); ?>
            </form>
            
            <hr />
            
            <h2><?php _e( 'How to Use', 'reviews-slider' ); ?></h2>
            <p><?php _e( 'Add the following shortcode to any page or post to display the reviews slider:', 'reviews-slider' ); ?></p>
            <code>[reviews_slider]</code>
            
            <h3><?php _e( 'Shortcode Options', 'reviews-slider' ); ?></h3>
            <ul>
                <li><code>category="slug"</code> - <?php _e( 'Filter by review category', 'reviews-slider' ); ?></li>
                <li><code>limit="10"</code> - <?php _e( 'Limit number of reviews (default: all)', 'reviews-slider' ); ?></li>
                <li><code>orderby="date"</code> - <?php _e( 'Order by: date, title, ID', 'reviews-slider' ); ?></li>
                <li><code>order="DESC"</code> - <?php _e( 'Order: ASC or DESC', 'reviews-slider' ); ?></li>
            </ul>
            
            <h3><?php _e( 'Example', 'reviews-slider' ); ?></h3>
            <code>[reviews_slider category="testimonials" limit="10" orderby="date" order="DESC"]</code>
        </div>
        <?php
    }
}
