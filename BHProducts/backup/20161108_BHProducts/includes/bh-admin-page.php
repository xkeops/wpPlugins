<?php

function bh_add_product_manager_role() {

    // create your role(s)
    // for more details on this see: https://codex.wordpress.org/Function_Reference/add_role 
    // adding "read => true" enables the wp-admin area for the user with just dashboard and profile tabs

    add_role(
        'd3_user_role',
        'D3 User Role',
        array(
                'read' => true,
                'edit_posts' => false,
                'delete_posts' => false,
                'publish_posts' => false,
                'upload_files' => true,
		'custom_menu_slug' => true
		)
    );

}

register_activation_hook( __FILE__, 'bh_add_product_manager_role' );

?>