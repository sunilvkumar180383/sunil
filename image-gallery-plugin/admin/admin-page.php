<?php

class Gallery_Admin_Page {
    
    private $gallery_manager;
    
    public function __construct() {
        $this->gallery_manager = new Gallery_Manager();
        add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __( 'Image Gallery', 'image-gallery-manager' ),
            __( 'Image Gallery', 'image-gallery-manager' ),
            'manage_options',
            'igm-gallery',
            array( $this, 'render_admin_page' ),
            'dashicons-images-alt2',
            25
        );
    }
    
    /**
     * Render admin page
     */
    public function render_admin_page() {
        ?>
        <div class="wrap igm-admin-wrap">
            <h1><?php esc_html_e( 'Image Gallery Manager', 'image-gallery-manager' ); ?></h1>
            
            <div class="igm-admin-container">
                <div class="igm-upload-section">
                    <h2><?php esc_html_e( 'Add Images', 'image-gallery-manager' ); ?></h2>
                    <button id="igm-upload-btn" class="button button-primary">
                        <?php esc_html_e( 'Select Images', 'image-gallery-manager' ); ?>
                    </button>
                    
                    <div id="igm-upload-form" style="display: none;">
                        <form id="igm-image-form">
                            <input type="hidden" id="igm-attachment-id" name="attachment_id" />
                            <p>
                                <label for="igm-image-title"><?php esc_html_e( 'Title:', 'image-gallery-manager' ); ?></label>
                                <input type="text" id="igm-image-title" name="title" />
                            </p>
                            <p>
                                <label for="igm-image-description"><?php esc_html_e( 'Description:', 'image-gallery-manager' ); ?></label>
                                <textarea id="igm-image-description" name="description"></textarea>
                            </p>
                            <button type="button" id="igm-add-image-btn" class="button button-primary">
                                <?php esc_html_e( 'Add to Gallery', 'image-gallery-manager' ); ?>
                            </button>
                        </form>
                    </div>
                </div>
                
                <div class="igm-gallery-list">
                    <h2><?php esc_html_e( 'Gallery Images', 'image-gallery-manager' ); ?></h2>
                    <ul id="igm-sortable-list" class="igm-sortable">
                        <?php $this->render_gallery_items(); ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render gallery items
     */
    private function render_gallery_items() {
        $images = $this->gallery_manager->get_images();
        
        if ( empty( $images ) ) {
            echo '<li>' . esc_html__( 'No images in gallery', 'image-gallery-manager' ) . '</li>';
            return;
        }
        
        foreach ( $images as $image ) {
            ?>
            <li class="igm-gallery-list-item" data-image-id="<?php echo intval( $image->image_id ); ?>">
                <div class="igm-list-item-inner">
                    <span class="igm-drag-handle">☰</span>
                    <img src="<?php echo esc_url( $image->attachment_url ); ?>" alt="<?php echo esc_attr( $image->title ); ?>" class="igm-list-thumbnail" />
                    <div class="igm-list-item-info">
                        <h4><?php echo esc_html( $image->title ?: 'Untitled' ); ?></h4>
                        <p><?php echo esc_html( substr( $image->description, 0, 60 ) ); ?></p>
                    </div>
                    <button type="button" class="button igm-delete-btn" data-image-id="<?php echo intval( $image->image_id ); ?>">
                        <?php esc_html_e( 'Delete', 'image-gallery-manager' ); ?>
                    </button>
                </div>
            </li>
            <?php
        }
    }
    
    /**
     * Enqueue admin assets
     */
    public function enqueue_admin_assets() {
        $screen = get_current_screen();
        if ( 'toplevel_page_igm-gallery' !== $screen->id ) {
            return;
        }
        
        wp_enqueue_media();
        wp_enqueue_style( 'igm-admin-style', IGM_PLUGIN_URL . 'assets/css/admin-style.css', array(), '1.0.0' );
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script( 'igm-admin-script', IGM_PLUGIN_URL . 'assets/js/admin-script.js', array( 'jquery', 'jquery-ui-sortable' ), '1.0.0', true );
        
        wp_localize_script( 'igm-admin-script', 'igmData', array(
            'nonce' => wp_create_nonce( 'igm_admin_nonce' ),
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
        ) );
    }
}
?>