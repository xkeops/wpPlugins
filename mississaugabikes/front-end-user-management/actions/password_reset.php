<?php
add_action('init', 'simplistics_password_reset');
function simplistics_password_reset() {
	global $setting;
  	if (wp_verify_nonce($_POST['simplistics_reset_password_nonce'], 'simplistics-reset-password-nonce')) {
		
  		if(isset($_POST["simplistics_user_email"]) || !empty($_POST["simplistics_user_email"])) {
  			$user_email	= $_POST["simplistics_user_email"];
  			if($user_email == '') {
				simplistics_errors()->add('email_empty', __('Please enter an email'));
			} else {
				$user_data = get_user_by( 'email', $user_email );
				if(!empty($user_data)) { 
		  			$headers[] = 'Content-Type: text/html; charset=UTF-8';
					$headers[] = 'From: Mississauga bike challenge <tests@simplistics.ca>' . "\r\n"; /// CHANGE EMAIL
					$subject = "Your Mississauga Bike Challenge password reset request.";
					$message = "<h1>You have requested to reset your password.</h1>";
					$message .= "<p>If you did not request this, please contact mississaugabikechallenge.com at tests@simplistics.ca</p>"; // CHANGE SITE AND EMAIL
					$message .= "<a href='".get_site_url()."/reset-password?msg=".$user_data->user_activation_key.">'>Click here to reset your password.</a>";
					wp_mail($user_email, $subject, $message, $headers);
					wp_redirect($setting->getPluginPassword()."?msg=emailsent"); exit;
				}
			}
			wp_redirect($setting->getPluginPassword()."?msg=fail"); exit;
		}

		if(isset($_POST["simplistics_user_pass"]) || !empty($_POST["simplistics_user_pass_hash"])) {
			$user_pass		= $_POST["simplistics_user_pass"];
			$pass_confirm 	= $_POST["simplistics_user_pass_confirm"];
			$user_activation_key = $_POST["simplistics_user_pass_hash"];
			if($user_pass == '') {
				simplistics_errors()->add('password_empty', __('Please enter a password'));
			} else {
				if ( strlen( $user_pass ) < 8 || strlen( $user_pass ) > 16 ) {
					simplistics_errors()->add('password_length', __('Your password must be 8-16 characters long'));
				}
				if ( !preg_match('/\d/', $user_pass ) ) {
					simplistics_errors()->add('password_no_number', __('Your password must contain at least one number'));
				}
				if ( !preg_match('/[a-z]/', $user_pass ) ) {
					simplistics_errors()->add('password_no_lowercase', __('Your password must contain at least one lowercase letter'));
				}
				if ( !preg_match('/[A-Z]/', $user_pass ) ) {
					simplistics_errors()->add('password_no_uppercase', __('Your password must contain at least one uppercase letter'));
				}
			}
			if($user_pass != $pass_confirm) {
				simplistics_errors()->add('password_mismatch', __('Passwords do not match'));
			}
			$errors = simplistics_errors()->get_error_messages();

			
			if(empty($errors)) {
				global $wpdb;
				$update_pass = $wpdb->update (
					$wpdb->prefix."users",
					array(
						"user_pass" => wp_hash_password($user_pass)
					),
					array (
						"user_activation_key" => $user_activation_key
					)
				);

				if($update_pass!=false) {
					
					wp_redirect($setting->getPluginLogin());
					exit;

				} else {
					wp_redirect($setting->getPluginPassword()."?msg=fail"); exit;
				}
			}
		}
	}
}