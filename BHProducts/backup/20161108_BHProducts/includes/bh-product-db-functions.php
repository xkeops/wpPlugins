<?php

add_action( 'init', 'create_posttype' );
function create_posttype() {
  register_post_type( 'd3_product',
    array(
      'labels' => array(
        'name' => __( 'Products' ),
        'singular_name' => __( 'Product' )
      ),
      'public' => true,
      'has_archive' => true,
      'rewrite' => array('slug' => 'products'),
    )
  );
}


?>