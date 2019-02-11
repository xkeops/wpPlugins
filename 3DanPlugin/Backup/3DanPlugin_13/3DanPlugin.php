<?php
/*
* Plugin Name: 3 Dan Plugin
* Plugin URI: http://www.mississauga.ca/
* Description: A custom music review plugin built for example.
* Version: 1.0
* Author: Dan P
* Author URI: http://culture.mississauga.ca/
*/

/****************
* global variables
*****************/
$mfwp_prefix = 'dp3_';
$mfwp_plugin_name = '3 Dan Plugin';

//retrieve plugins settings from options table
$mfwp_options = get_option('mfwp_settings');


/****************
* includes
****************/

include('includes/display-functions.php');
include('includes/db-functions.php');

include('includes/admin-page.php');

register_activation_hook( __FILE__, 'dp3_install' );

?>