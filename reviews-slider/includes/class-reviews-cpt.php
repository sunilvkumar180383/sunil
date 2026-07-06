<?php
/**
 * Custom Post Type for Reviews
 */

class Reviews_CPT {
    
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
        add_action( 'init', array( $this, 'register_post_type' ) );
        add_action( 'init', array( $this, 'register_taxonomy' ) );
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
        add_action( 'save_post_review', array( $this, 'save_meta_boxes' ) );
    }
    
    /**
     * Register custom post type
     */
    public static function register_post_type() {
        $labels = array(
            'name'               => __( 'Reviews', 'reviews-slider' ),
            'singular_name'      => __( 'Review', 'reviews-slider' ),
            'menu_name'          => __( 'Reviews', 'reviews-slider' ),
            'all_items'          => __( 'All Reviews', 'reviews-slider' ),
            'view_item'          => __( 'View Review', 'reviews-slider' ),
            'add_new_item'       => __( 'Add New Review', 'reviews-slider' ),
            'add_new'            => __( 'Add New', 'reviews-slider' ),
            'edit_item'          => __( 'Edit Review', 'reviews-slider' ),
            'update_item'        => __( 'Update Review', 'reviews-slider' ),
            'search_items'       => __( 'Search Reviews', 'reviews-slider' ),
            'not_found'          => __( 'No reviews found', 'reviews-slider' ),
            'not_found_in_trash' => __( 'No reviews found in trash', 'reviews-slider' ),
        );
        
        $args = array(
            'label'               => __( 'Reviews', 'reviews-slider' ),
            'description'         => __( 'Customer reviews', 'reviews-slider' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'thumbnail' ),
            'taxonomies'          => array( 'review_category' ),
            'hierarchical'        => false,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 20,
            'menu_icon'           => 'dashicons-star-filled',
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'show_in_rest'        => true,
        );
        
        register_post_type( 'review', $args );
    }
    
    /**
     * Register taxonomy
     */
    public function register_taxonomy() {
        $labels = array(
            'name'              => __( 'Review Categories', 'reviews-slider' ),
            'singular_name'     => __( 'Review Category', 'reviews-slider' ),
            'search_items'      => __( 'Search Categories', 'reviews-slider' ),
            'all_items'         => __( 'All Categories', 'reviews-slider' ),
            'parent_item'       => __( 'Parent Category', 'reviews-slider' ),
            'edit_item'         => __( 'Edit Category', 'reviews-slider' ),
            'update_item'       => __( 'Update Category', 'reviews-slider' ),
            'add_new_item'      => __( 'Add New Category', 'reviews-slider' ),
            'new_item_name'     => __( 'New Category Name', 'reviews-slider' ),
            'menu_name'         => __( 'Categories', 'reviews-slider' ),
        );
        
        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_rest'      => true,
        );
        
        register_taxonomy( 'review_category', 'review', $args );
    }
    
    /**
     * Add meta boxes
     */
    public function add_meta_boxes() {
        add_meta_box(
            'review_details',
            __( 'Review Details', 'reviews-slider' ),
            array( $this, 'render_meta_box' ),
            'review',
            'normal',
            'high'
        );
    }
    
    /**
     * Render meta box
     */
    public function render_meta_box( $post ) {
        wp_nonce_field( 'review_meta_nonce', 'review_nonce' );
        
        $author_name = get_post_meta( $post->ID, 'review_author_name', true );
        $author_title = get_post_meta( $post->ID, 'review_author_title', true );
        $author_image = get_post_meta( $post->ID, 'review_author_image', true );
        $rating = get_post_meta( $post->ID, 'review_rating', true );
        
        ?>
        <table class="form-table">
            <tr>
                <th><label for="review_author_name"><?php _e( 'Author Name', 'reviews-slider' ); ?></label></th>
                <td>
                    <input type="text" id="review_author_name" name="review_author_name" value="<?php echo esc_attr( $author_name ); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th><label for="review_author_title"><?php _e( 'Author Title/Company', 'reviews-slider' ); ?></label></th>
                <td>
                    <input type="text" id="review_author_title" name="review_author_title" value="<?php echo esc_attr( $author_title ); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th><label for="review_rating"><?php _e( 'Rating', 'reviews-slider' ); ?></label></th>
                <td>
                    <select id="review_rating" name="review_rating">
                        <option value="1" <?php selected( $rating, '1' ); ?>>1 <?php _e( 'Star', 'reviews-slider' ); ?></option>
                        <option value="2" <?php selected( $rating, '2' ); ?>>2 <?php _e( 'Stars', 'reviews-slider' ); ?></option>
                        <option value="3" <?php selected( $rating, '3' ); ?>>3 <?php _e( 'Stars', 'reviews-slider' ); ?></option>
                        <option value="4" <?php selected( $rating, '4' ); ?>>4 <?php _e( 'Stars', 'reviews-slider' ); ?></option>
                        <option value="5" <?php selected( $rating, '5' ); ?>>5 <?php _e( 'Stars', 'reviews-slider' ); ?></option>
                    </select>
                </td>
            </tr>
            <tr>
                <th><label for="review_author_image"><?php _e( 'Author Image', 'reviews-slider' ); ?></label></th>
                <td>
                    <div id="review_image_container">
                        <?php if ( $author_image ) : ?>
                            <img src="<?php echo esc_url( $author_image ); ?>" style="max-width: 100px; height: auto;" />
                            <br />
                        <?php endif; ?>
                    </div>
                    <input type="hidden" id="review_author_image" name="review_author_image" value="<?php echo esc_attr( $author_image ); ?>" />
                    <button type="button" class="button" id="review_image_button"><?php _e( 'Choose Image', 'reviews-slider' ); ?></button>
                    <?php if ( $author_image ) : ?>
                        <button type="button" class="button" id="review_image_remove"><?php _e( 'Remove Image', 'reviews-slider' ); ?></button>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
        <script>
        jQuery(function($) {
            var mediaUploader;
            $('#review_image_button').click(function(e) {
                e.preventDefault();
                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }
                mediaUploader = wp.media.frames.file_frame = wp.media({
                    title: '<?php _e( "Choose Image", "reviews-slider" ); ?>',
                    button: { text: '<?php _e( "Choose Image", "reviews-slider" ); ?>' },
                    multiple: false
                });
                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    $('#review_author_image').val(attachment.url);
                    $('#review_image_container').html('<img src="' + attachment.url + '" style="max-width: 100px; height: auto;" /><br />');
                });
                mediaUploader.open();
            });
            
            $('#review_image_remove').click(function(e) {
                e.preventDefault();
                $('#review_author_image').val('');
                $('#review_image_container').html('');
                $(this).remove();
            });
        });
        </script>
        <?php
    }
    
    /**
     * Save meta boxes
     */
    public function save_meta_boxes( $post_id ) {
        if ( ! isset( $_POST['review_nonce'] ) || ! wp_verify_nonce( $_POST['review_nonce'], 'review_meta_nonce' ) ) {
            return;
        }
        
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        
        if ( isset( $_POST['review_author_name'] ) ) {
            update_post_meta( $post_id, 'review_author_name', sanitize_text_field( $_POST['review_author_name'] ) );
        }
        
        if ( isset( $_POST['review_author_title'] ) ) {
            update_post_meta( $post_id, 'review_author_title', sanitize_text_field( $_POST['review_author_title'] ) );
        }
        
        if ( isset( $_POST['review_rating'] ) ) {
            update_post_meta( $post_id, 'review_rating', sanitize_text_field( $_POST['review_rating'] ) );
        }
        
        if ( isset( $_POST['review_author_image'] ) ) {
            update_post_meta( $post_id, 'review_author_image', esc_url_raw( $_POST['review_author_image'] ) );
        }
    }
}
