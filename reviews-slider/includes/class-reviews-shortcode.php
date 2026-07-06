<?php
/**
 * Reviews Shortcode Handler
 */

class Reviews_Shortcode {
    
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
        add_shortcode( 'reviews_slider', array( $this, 'render_shortcode' ) );
    }
    
    /**
     * Render shortcode
     */
    public function render_shortcode( $atts ) {
        $atts = shortcode_atts( array(
            'category' => '',
            'limit' => -1,
            'orderby' => 'date',
            'order' => 'DESC',
        ), $atts, 'reviews_slider' );
        
        $args = array(
            'post_type' => 'review',
            'posts_per_page' => $atts['limit'],
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
        );
        
        if ( ! empty( $atts['category'] ) ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'review_category',
                    'field' => 'slug',
                    'terms' => $atts['category'],
                ),
            );
        }
        
        $query = new WP_Query( $args );
        
        if ( ! $query->have_posts() ) {
            return '<p>' . __( 'No reviews found.', 'reviews-slider' ) . '</p>';
        }
        
        $settings = get_option( 'reviews_slider_settings', array() );
        $items_to_show = isset( $settings['items_to_show'] ) ? (int) $settings['items_to_show'] : 3;
        $autoplay = isset( $settings['autoplay'] ) ? $settings['autoplay'] : 'on';
        $autoplay_speed = isset( $settings['autoplay_speed'] ) ? (int) $settings['autoplay_speed'] : 5000;
        $arrows = isset( $settings['arrows'] ) ? $settings['arrows'] : 'on';
        $dots = isset( $settings['dots'] ) ? $settings['dots'] : 'on';
        
        ob_start();
        ?>
        <div class="reviews-slider-container">
            <div class="reviews-slider" data-items="<?php echo esc_attr( $items_to_show ); ?>" data-autoplay="<?php echo esc_attr( $autoplay ); ?>" data-autoplay-speed="<?php echo esc_attr( $autoplay_speed ); ?>" data-arrows="<?php echo esc_attr( $arrows ); ?>" data-dots="<?php echo esc_attr( $dots ); ?>">
                <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <div class="review-slide">
                        <div class="review-content">
                            <div class="review-text">
                                <?php the_content(); ?>
                            </div>
                            <div class="review-rating">
                                <?php 
                                $rating = get_post_meta( get_the_ID(), 'review_rating', true );
                                if ( $rating ) {
                                    for ( $i = 0; $i < (int) $rating; $i++ ) {
                                        echo '<span class="star">★</span>';
                                    }
                                }
                                ?>
                            </div>
                            <div class="review-author">
                                <?php 
                                $author_image = get_post_meta( get_the_ID(), 'review_author_image', true );
                                if ( $author_image ) {
                                    echo '<img src="' . esc_url( $author_image ) . '" alt="' . esc_attr( get_post_meta( get_the_ID(), 'review_author_name', true ) ) . '" class="author-image" />';
                                }
                                ?>
                                <div class="author-info">
                                    <strong class="author-name"><?php echo esc_html( get_post_meta( get_the_ID(), 'review_author_name', true ) ); ?></strong>
                                    <span class="author-title"><?php echo esc_html( get_post_meta( get_the_ID(), 'review_author_title', true ) ); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php
        wp_reset_postdata();
        return ob_get_clean();
    }
}
