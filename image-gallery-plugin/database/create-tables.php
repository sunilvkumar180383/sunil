<?php

function create_gallery_tables() {
    global $wpdb;
    
    $charset_collate = $wpdb->get_charset_collate();
    
    // Images table
    $images_table = $wpdb->prefix . 'gallery_images';
    $sql_images = "CREATE TABLE IF NOT EXISTS $images_table (
        id BIGINT(20) NOT NULL AUTO_INCREMENT,
        image_id BIGINT(20) NOT NULL,
        attachment_url VARCHAR(500) NOT NULL,
        title VARCHAR(255),
        description LONGTEXT,
        sort_order INT(11) DEFAULT 0,
        status VARCHAR(20) DEFAULT 'active',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY image_id (image_id)
    ) $charset_collate;";
    
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta( $sql_images );
}
?>