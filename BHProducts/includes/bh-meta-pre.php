  <?php
    /**
     * Adds a meta box to the post editing screen
     */
    function prfx_custom_meta() {
        add_meta_box( 'prfx_meta', __( 'Meta Box Title', 'prfx-textdomain' ), 'prfx_meta_callback', 'post' );
    }
    add_action( 'add_meta_boxes', 'prfx_custom_meta' );

    /**
     * Outputs the content of the meta box
     */
    function prfx_meta_callback( $post ) {
        wp_nonce_field( basename( __FILE__ ), 'prfx_nonce' );
        $prfx_stored_meta = get_post_meta( $post->ID );
        ?>

        <p>
            <label for="meta-image" class="prfx-row-title"><?php _e( 'Example File Upload', 'prfx-textdomain' )?></label>
            <input type="text" name="meta-image" id="meta-image" value="<?php if ( isset ( $prfx_stored_meta['meta-image'] ) ) echo $prfx_stored_meta['meta-image'][0]; ?>" />
            <input type="button" id="meta-image-button" class="button" value="<?php _e( 'Choose or Upload an Image', 'prfx-textdomain' )?>" />
        </p>


        <?php
    }     
    /**
     * Saves the custom meta input
     */
    function prfx_meta_save( $post_id ) {

        // Checks save status
        $is_autosave = wp_is_post_autosave( $post_id );
        $is_revision = wp_is_post_revision( $post_id );
        $is_valid_nonce = ( isset( $_POST[ 'prfx_nonce' ] ) && wp_verify_nonce( $_POST[ 'prfx_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

        // Exits script depending on save status
        if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
            return;
        }

        // Checks for input and sanitizes/saves if needed
        if( isset( $_POST[ 'meta-text' ] ) ) {
            update_post_meta( $post_id, 'meta-text', sanitize_text_field( $_POST[ 'meta-text' ] ) );
        }

        // Checks for input and saves if needed
        if( isset( $_POST[ 'meta-image' ] ) ) {
            update_post_meta( $post_id, 'meta-image', $_POST[ 'meta-image' ] );
        }

    }
    add_action( 'save_post', 'prfx_meta_save' );


    /**
     * Adds the meta box stylesheet when appropriate
     */
    function prfx_admin_styles(){
        global $typenow;
        if( $typenow == 'post' ) {
            wp_enqueue_style( 'prfx_meta_box_styles', plugin_dir_url( __FILE__ ) . 'meta-box-styles.css' );
        }
    }
    add_action( 'admin_print_styles', 'prfx_admin_styles' );


    /**
     * Loads the color picker javascript
     */
    function prfx_color_enqueue() {
        global $typenow;
        if( $typenow == 'post' ) {
            wp_enqueue_style( 'wp-color-picker' );
            wp_enqueue_script( 'meta-box-color-js', plugin_dir_url( __FILE__ ) . 'meta-box-color.js', array( 'wp-color-picker' ) );
        }
    }
    add_action( 'admin_enqueue_scripts', 'prfx_color_enqueue' );

    /**
     * Loads the image management javascript
     */
    function prfx_image_enqueue() {
        global $typenow;
        if( $typenow == 'post' ) {
            wp_enqueue_media();

            // Registers and enqueues the required javascript.
            wp_register_script( 'meta-box-image', plugin_dir_url( __FILE__ ) . 'meta-box-image.js', array( 'jquery' ) );
            wp_localize_script( 'meta-box-image', 'meta_image',
                array(
                    'title' => __( 'Choose or Upload an Image', 'prfx-textdomain' ),
                    'button' => __( 'Use this image', 'prfx-textdomain' ),
                )
            );
            wp_enqueue_script( 'meta-box-image' );
        }
    }
    add_action( 'admin_enqueue_scripts', 'prfx_image_enqueue' );