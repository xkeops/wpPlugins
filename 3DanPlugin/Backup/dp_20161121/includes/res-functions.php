<?php

// Update CSS within in Admin
function admin_style() {
  $pPluginCss = plugins_url( 'css/style.css', dirname( __FILE__ ) );
  wp_enqueue_style('admin-styles', $pPluginCss);
}
add_action('admin_enqueue_scripts', 'admin_style');

?>