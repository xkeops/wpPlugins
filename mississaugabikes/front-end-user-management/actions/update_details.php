<?php
add_action('init', 'simplistics_update_details');
function simplistics_update_details(){
  	if (isset( $_POST["simplistics_user_login"] ) && wp_verify_nonce($_POST['simplistics_profile_nonce'], 'simplistics-profile-nonce')) {
		
		global $wpdb;
  		$user_ID = get_current_user_id();
  		if(empty($user_ID)) {
  			exit;
  		}

		$user_login		= $_POST["simplistics_user_login"];	
		$user_email		= $_POST["simplistics_user_email"];
		$user_name = $_POST["simplistics_user_firstname"]." ".$_POST["simplistics_user_lastname"];
  
		require_once(ABSPATH . WPINC . '/registration.php');

		if(username_exists($user_login) && username_exists($user_login)!=$user_ID) {
			simplistics_errors()->add('username_unavailable', __('Username already taken'));
		}
		if(!validate_username($user_login)) {
			simplistics_errors()->add('username_invalid', __('Invalid username'));
		}
		if($user_login == '') {
			simplistics_errors()->add('username_empty', __('Please enter a username'));
		}
		if(!is_email($user_email)) {
			simplistics_errors()->add('email_invalid', __('Invalid email'));
		}
		if(email_exists($user_email) && email_exists($user_email)!=$user_ID) {
			simplistics_errors()->add('email_used', __('Email already registered'));
		}

		$errors = simplistics_errors()->get_error_messages();
 
		if(empty($errors)) {

			$update_profile = $wpdb->update (
				"wp_users",
				array(
					"user_login" => $user_login,
					"user_email" => $user_email,
					"user_nicename" => $user_name,
					"display_name" => $user_name,
				),
				array (
					"ID" => $user_ID
				)
			);

			/*$extension = explode(".",basename( $_FILES["simplistics_user_profile_picture"]["name"]));
			$target_dir = URI_PATH."pictures/";
			//$target_file = $target_dir . $user_ID.".".$extension[count($extension)-1];
			

			try{
				$check = move_uploaded_file($_POST["simplistics_user_profile_picture"]["tmp_name"], $target_file);
			}catch(Exception $e){
				die ('File did not upload: ' . $e->getMessage());
			}
			*/

			update_user_meta( $user_ID, 'nickname', $user_name);

			if(!empty($update_profile)){
				wp_redirect(get_home_url()); exit;
			
			}
		}
	}
}

//upload profile picture on the server
function uploadProfilePicture(){
	check_ajax_referer('profile-picture-nonce','security');
	global $wpdb;
	$user_ID = get_current_user_id();
	//$extension = explode(".",basename( $_FILES["simplistics_user_profile_picture"]["name"]));
	$target_dir = URI_PATH."pictures/";
	//$target_file = $target_dir . $user_ID.".".$extension[count($extension)-1];
	$target_file = $target_dir . $user_ID.".png";
	$base64img = $_POST["file"];

	if($base64img!="false"){
		$base64img = str_replace('data:image/png;base64,', '', $base64img);
		$base64img = str_replace('data:image/jpg;base64,', '', $base64img);
		$base64img = str_replace('data:image/jpeg;base64,', '', $base64img);
		$base64img = str_replace('data:image/gif;base64,', '', $base64img);

		$img = base64_decode($base64img);

		file_put_contents($target_file,$img);

	}
	
	$user_name = $_POST["simplistics_user_firstname"]." ".$_POST["simplistics_user_lastname"];

	$update_profile = $wpdb->update (
		"wp_users",
		array(
			"user_nicename" => $user_name,
			"display_name" => $user_name
		),
		array (
			"ID" => $user_ID
		)
	);
	

}