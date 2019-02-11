<?php
/*
Plugin Name: Front End User Management
Plugin URI: https://simplistics.ca
Description: Provides simple front end registration and login forms
Version: 1.0
Author: Itzik Levy
Author URI: https://simplistics.ca
*/

require_once( plugin_dir_path(__FILE__).'actions/login.php' );
require_once( plugin_dir_path(__FILE__).'actions/password_reset.php' );
require_once( plugin_dir_path(__FILE__).'actions/register.php' );
require_once( plugin_dir_path(__FILE__).'actions/update_details.php' );
require_once( plugin_dir_path(__FILE__).'actions/action_ajax.php' );
require_once( WP_PLUGIN_DIR.'/bike_challenge/model/achievement.php' );

DEFINE('URI_PATH',plugin_dir_path(__FILE__));
DEFINE('DIR_URL',plugin_dir_url(__FILE__));

if ( file_exists( get_template_directory().'/feum/custom.php' ) ) {
	require_once( get_template_directory().'/feum/custom.php' );
}

function includePluginTemplate ( $template ) {
	if ( !$template ) { echo 'Must specify a template name!'; return; }
	if ( file_exists( get_template_directory().'/feum/views/'.$template.'.php' ) ) {
		include( get_template_directory().'/feum/views/'.$template.'.php' );
	} elseif ( file_exists( plugin_dir_path(__FILE__).'views/'.$template.'.php' ) ) {
		include( plugin_dir_path(__FILE__).'views/'.$template.'.php' );
	} else {
		echo 'Template "'.$template.'" not found!';
	}
}

function feumIncludeTemplate ( $atts ) {
	extract( shortcode_atts( array(
        'template' => false
    ), $atts ) );
	ob_start();
	includePluginTemplate( $template );
	return ob_get_clean();
}
add_shortcode('feum_view', 'feumIncludeTemplate');


function simplistics_errors(){
    static $wp_error;
    return isset($wp_error) ? $wp_error : ($wp_error = new WP_Error(null, "no errors", null));
}

function simplistics_show_error_messages() {

	if($codes = simplistics_errors()->get_error_codes()) {
		
		echo '<div class="simplistics_errors"><span class="error"><strong>' . __('Errors') . '</strong>:</span><ul>';
		   foreach($codes as $code){
		        $message = simplistics_errors()->get_error_message($code);
		        echo '<li>'.$message.'</li>';
		    }
		echo '</ul></div>';
	}	
}


function simplistics_get_error_messages() {

	if($codes = simplistics_errors()->get_error_codes()) {
		$res = "";
		$res.= '<div class="simplistics_errors"><span class="error"><strong>' . __('Errors') . '</strong>:</span><ul>';
		   foreach($codes as $code){
		        $message = simplistics_errors()->get_error_message($code);
		        $res.= '<li>'.$message.'</li>';
		    }
		$res.= '</ul></div>';
		return $res;
	}	
}

add_action('wp_print_scripts', 'loginscripts'); // Add Conditional Page Scripts

function  loginscripts(){
	
	wp_register_script('loginscripts', 'https://www.google.com/recaptcha/api.js'); // Custom scripts
	wp_register_script('updatescripts', plugin_dir_url(__FILE__). 'js/script.js', array('jquery'), '1.0.0');
	
	/*
	wp_register_script('croppiescripts', plugin_dir_url(__FILE__). 'js/croppie.min.js',array('jquery'));

	wp_enqueue_style( 'croppie', plugin_dir_url(__FILE__). 'css/croppie.css',false,'1.1','all');
	*/
	
	wp_register_script('croppiescripts', plugin_dir_url(__FILE__). 'js/cropper.min.js',array('jquery'));
	wp_register_script('tether', 'https://npmcdn.com/tether@1.2.4/dist/js/tether.min.js'); 
	
	wp_register_script('bootstrapjs', plugin_dir_url(__FILE__). 'js/bootstrap.min.js');

	wp_enqueue_style( 'croppie', plugin_dir_url(__FILE__). 'css/cropper.min.css',false,'1.1','all');

	wp_enqueue_style( 'bootstrap', plugin_dir_url(__FILE__).'css/custom-bootstrap.css');
	wp_enqueue_style( 'main', plugin_dir_url(__FILE__).'css/main.css');

	wp_enqueue_script('tether'); // Enqueue it!
	wp_enqueue_script('loginscripts'); // Enqueue it!
	wp_enqueue_script('croppiescripts'); // Enqueue it!
	wp_enqueue_script('updatescripts'); // Enqueue it!
	wp_enqueue_script('bootstrapjs'); // Enqueue it!
	

	wp_localize_script( 'updatescripts', 'script', array( 'url' => plugin_dir_url(__FILE__) ));
	
	// make the ajaxurl var available to the above script
	wp_localize_script( 'updatescripts', 'user_ajax',array( 'ajaxurl' => admin_url('admin-ajax.php'),'ajax_nonce' => wp_create_nonce('profile-picture-nonce')));
}