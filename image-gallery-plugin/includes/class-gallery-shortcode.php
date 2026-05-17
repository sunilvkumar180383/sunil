<?php

class Gallery_Shortcode {
    
    private $gallery_manager;
    
    public function __construct() {
        $this->gallery_manager = new Gallery_Manager();
        add_shortcode( 'image_gallery', array( $this, 'render_gallery' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
    }
    
    /**
     * Render gallery shortcode
     */
    public function render_gallery( $atts ) {
        $atts = shortcode_atts( array(
            'columns' => 3,
            'limit' => -1,
        ), $atts, 'image_gallery' );
        
        $images = $this->gallery_manager->get_images( intval( $atts['limit'] ) );
        
        if ( empty( $images ) ) {
            return '<p>' . esc_html__( 'No images found', 'image-gallery-manager' ) . '</p>';
        }
        
        ob_start();
        ?>
        <div class="igm-gallery-wrapper" data-columns="<?php echo intval( $atts['columns'] ); ?>">
            <div class="igm-gallery-grid">
                <?php foreach ( $images as $image ) : ?>
                    <div class="igm-gallery-item">
                        <div class="igm-image-container">
                            <img src="<?php echo esc_url( $image->attachment_url ); ?>" 
                                 alt="<?php echo esc_attr( $image->title ); ?>"
                                 class="igm-gallery-image" />
                            <div class="igm-overlay">
                                <div class="igm-overlay-content">
                                    <?php if ( ! empty( $image->title ) ) : ?>
                                        <h3 class="igm-image-title"><?php echo esc_html( $image->title ); ?></h3>
                                    <?php endif; ?>
                                    <?php if ( ! empty( $image->description ) ) : ?>
                                        <p class="igm-image-description"><?php echo esc_html( $image->description ); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
    
    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        wp_enqueue_style( 'igm-frontend-style', IGM_PLUGIN_URL . 'assets/css/frontend-style.css', array(), '1.0.0' );
        wp_enqueue_script( 'igm-frontend-script', IGM_PLUGIN_URL . 'assets/js/frontend-script.js', array( 'jquery' ), '1.0.0', true );
    }
}
?>