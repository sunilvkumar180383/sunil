<?php

class Gallery_AJAX_Handlers {
    
    private $gallery_manager;
    
    public function __construct() {
        $this->gallery_manager = new Gallery_Manager();
        add_action( 'wp_ajax_igm_add_image', array( $this, 'handle_add_image' ) );
        add_action( 'wp_ajax_igm_delete_image', array( $this, 'handle_delete_image' ) );
        add_action( 'wp_ajax_igm_reorder_images', array( $this, 'handle_reorder_images' ) );
    }
    
    /**
     * Handle add image
     */
    public function handle_add_image() {
        check_ajax_referer( 'igm_admin_nonce' );
        
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Unauthorized' );
        }
        
        $attachment_id = intval( $_POST['attachment_id'] ?? 0 );
        $title = sanitize_text_field( $_POST['title'] ?? '' );
        $description = sanitize_textarea_field( $_POST['description'] ?? '' );
        
        if ( ! $attachment_id ) {
            wp_send_json_error( 'Invalid attachment ID' );
        }
        
        $result = $this->gallery_manager->add_image( $attachment_id, $title, $description );
        
        if ( $result ) {
            wp_send_json_success( array( 'message' => 'Image added successfully' ) );
        } else {
            wp_send_json_error( 'Failed to add image' );
        }
    }
    
    /**
     * Handle delete image
     */
    public function handle_delete_image() {
        check_ajax_referer( 'igm_admin_nonce' );
        
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Unauthorized' );
        }
        
        $image_id = intval( $_POST['image_id'] ?? 0 );
        
        if ( ! $image_id ) {
            wp_send_json_error( 'Invalid image ID' );
        }
        
        $result = $this->gallery_manager->delete_image( $image_id );
        
        if ( $result ) {
            wp_send_json_success( array( 'message' => 'Image deleted successfully' ) );
        } else {
            wp_send_json_error( 'Failed to delete image' );
        }
    }
    
    /**
     * Handle reorder images
     */
    public function handle_reorder_images() {
        check_ajax_referer( 'igm_admin_nonce' );
        
        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Unauthorized' );
        }
        
        $order = isset( $_POST['order'] ) ? array_map( 'intval', $_POST['order'] ) : array();
        
        if ( empty( $order ) ) {
            wp_send_json_error( 'Invalid order data' );
        }
        
        $this->gallery_manager->reorder_images( $order );
        wp_send_json_success( array( 'message' => 'Order updated successfully' ) );
    }
}
?>