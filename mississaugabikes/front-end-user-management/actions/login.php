<?php
add_action('init', 'simplistics_login');
function simplistics_login() {
	global $setting;

	if(isset($_POST['login']) && wp_verify_nonce($_POST['simplistics_login_nonce'], 'simplistics-login-nonce')) {
 		
 		$user = get_user_by('email', $_POST['login']);

 		
		if(!$user || !wp_check_password($_POST['password'], $user->user_pass, $user->ID)) {
			simplistics_errors()->add('empty_username', __('Invalid Login Credentials'));
		}
		if(!isset($_POST['password']) || $_POST['password'] == '') {
			simplistics_errors()->add('empty_password', __('Please enter a password'));
		}
 
		$errors = simplistics_errors()->get_error_messages();

 
		if(empty($errors)) {
			wp_setcookie($_POST['login'], $_POST['password'], true);
			wp_set_current_user( $user->ID );
			wp_signon( array( 'user_login' => $user->data->user_login, 'user_password' => $_POST['password'] ) );
 
			if(!empty($_COOKIE["last_page_cookie"])) {
                $redirect_to = $_COOKIE["last_page_cookie"];
                wp_redirect($redirect_to); exit;
            } else {
                wp_redirect(get_home_url().'/'.$setting->getPluginHome());
                exit;
            }

			
		}
	}
}