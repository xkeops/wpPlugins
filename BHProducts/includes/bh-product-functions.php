<?php

add_action( 'init', 'create_posttype' );

function create_posttype() {
  $args = array(
      'labels' => array(
        'name' => __( 'Products' ),
        'singular_name' => __( 'Product' ),
	'add_new' => __( 'Add New Product' )
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'products'),
	'capability_type'     => array('psp_product','psp_products'),
        'map_meta_cap'        => true,
	'menu_position' => 5,
    );

  register_post_type( 'd3_product', $args );
}


add_action('admin_init','psp_add_role_caps',999);


function psp_add_role_caps() {
 
 // Add the roles you'd like to administer the custom post types
 $roles = array('d3_user_role','editor','administrator');
 
 // Loop through each role and assign capabilities
 foreach($roles as $the_role) { 
 
      $role = get_role($the_role);
 
              $role->add_cap( 'read' );
              $role->add_cap( 'read_psp_product');
              $role->add_cap( 'read_private_psp_products' );
              $role->add_cap( 'edit_psp_product' );
              $role->add_cap( 'edit_psp_products' );
              $role->add_cap( 'edit_others_psp_products' );
              $role->add_cap( 'edit_published_psp_products' );
              $role->add_cap( 'publish_psp_products' );
              $role->add_cap( 'delete_others_psp_products' );
              $role->add_cap( 'delete_private_psp_products' );
              $role->add_cap( 'delete_published_psp_products' );
 
 		}
}


?>