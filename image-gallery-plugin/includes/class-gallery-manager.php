<?php

class Gallery_Manager {
    
    private $table_name;
    
    public function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'gallery_images';
    }
    
    /**
     * Add image to gallery
     */
    public function add_image( $attachment_id, $title = '', $description = '' ) {
        global $wpdb;
        
        $attachment = get_post( $attachment_id );
        if ( ! $attachment || 'attachment' !== $attachment->post_type ) {
            return false;
        }
        
        $image_url = wp_get_attachment_url( $attachment_id );
        $next_order = $this->get_next_sort_order();
        
        $result = $wpdb->insert(
            $this->table_name,
            array(
                'image_id' => $attachment_id,
                'attachment_url' => $image_url,
                'title' => sanitize_text_field( $title ),
                'description' => sanitize_textarea_field( $description ),
                'sort_order' => $next_order,
                'status' => 'active'
            ),
            array( '%d', '%s', '%s', '%s', '%d', '%s' )
        );
        
        do_action( 'igm_image_added', $attachment_id );
        return $result;
    }
    
    /**
     * Get all gallery images
     */
    public function get_images( $limit = 0 ) {
        global $wpdb;
        
        $query = "SELECT * FROM {$this->table_name} WHERE status = 'active' ORDER BY sort_order ASC";
        
        if ( $limit > 0 ) {
            $query .= " LIMIT " . intval( $limit );
        }
        
        return $wpdb->get_results( $query );
    }
    
    /**
     * Update image sort order
     */
    public function update_sort_order( $image_id, $new_order ) {
        global $wpdb;
        
        return $wpdb->update(
            $this->table_name,
            array( 'sort_order' => intval( $new_order ) ),
            array( 'image_id' => intval( $image_id ) ),
            array( '%d' ),
            array( '%d' )
        );
    }
    
    /**
     * Reorder images
     */
    public function reorder_images( $order_array ) {
        global $wpdb;
        
        foreach ( $order_array as $index => $image_id ) {
            $wpdb->update(
                $this->table_name,
                array( 'sort_order' => $index ),
                array( 'image_id' => intval( $image_id ) ),
                array( '%d' ),
                array( '%d' )
            );
        }
        
        return true;
    }
    
    /**
     * Delete image from gallery
     */
    public function delete_image( $image_id ) {
        global $wpdb;
        
        return $wpdb->delete(
            $this->table_name,
            array( 'image_id' => intval( $image_id ) ),
            array( '%d' )
        );
    }
    
    /**
     * Get next sort order
     */
    private function get_next_sort_order() {
        global $wpdb;
        
        $max_order = $wpdb->get_var(
            "SELECT MAX(sort_order) FROM {$this->table_name}"
        );
        
        return (int) $max_order + 1;
    }
    
    /**
     * Update image details
     */
    public function update_image( $image_id, $data ) {
        global $wpdb;
        
        $update_data = array();
        $format = array();
        
        if ( isset( $data['title'] ) ) {
            $update_data['title'] = sanitize_text_field( $data['title'] );
            $format[] = '%s';
        }
        
        if ( isset( $data['description'] ) ) {
            $update_data['description'] = sanitize_textarea_field( $data['description'] );
            $format[] = '%s';
        }
        
        if ( empty( $update_data ) ) {
            return false;
        }
        
        return $wpdb->update(
            $this->table_name,
            $update_data,
            array( 'image_id' => intval( $image_id ) ),
            $format,
            array( '%d' )
        );
    }
}
?>