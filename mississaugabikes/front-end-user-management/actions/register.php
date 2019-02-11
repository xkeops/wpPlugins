<?php
add_action('init', 'simplistics_register');
function simplistics_register() {
	global $setting;
  	if (isset( $_POST["simplistics_register_email"] ) && wp_verify_nonce($_POST['simplistics_register_nonce'], 'simplistics-register-nonce')) {
  		
  		//Verify captcha
  		$url = 'https://www.google.com/recaptcha/api/siteverify';
  		//data required by the google API
		$data = array('secret' => '6LfwkBQUAAAAANxF2hI2-34q04cDAPg5HLPUQTPv', 'response' => $_POST["g-recaptcha-response"]);
		$options = array(
		    'http' => array(
		        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
		        'method'  => 'POST',
		        'content' => http_build_query($data)
		    )
		);
		$context  = stream_context_create($options);
		$captcha = json_decode(file_get_contents($url, false, $context));
		
		//Process form

		$user_login	= $_POST["simplistics_register_email"];	
		$user_email	= $_POST["simplistics_register_email"];
		$user_email_confirmation = $_POST["simplistics_register_email"];
		$user_pass	= $_POST["password"];
		$pass_confirm = $_POST["password"];


		$name = $_POST['first_name']." ".$_POST['last_name'];

		

		//When agreeing to the terms of services add to metadata
		$ipadress = $_SERVER['REMOTE_ADDR'];

		$initials = $_POST["initials"];		

		//$password_strength = trim($_POST['password_strength']);

		$questions = Question::getListPublishedQuestions();
		$answers = array();
		foreach($questions as $question){
			$answers[$question->id]=$_POST["question-".$question->id];
		}


		$activation = rand(99999999,9999999999999);

		$_SESSION['errors_registration'] = "";
		$_SESSION["value_first_name"]="";
		$_SESSION["value_last_name"]="";
		$_SESSION["value_email"]="";
		$_SESSION["value_age"]="";
		//$_SESSION["value_phone"]="";
		//$_SESSION["value_address"]="";
		//$_SESSION["value_city"]="";
		$_SESSION["value_postal"]="";
 
		require_once(ABSPATH . WPINC . '/registration.php');

		if(email_exists($user_email)) {
			simplistics_errors()->add('email_used', __('Email already registered'));
		} elseif (!is_email($user_email)) {
			simplistics_errors()->add('email_invalid', __('Invalid email'));
		} elseif ( $user_email == '' ) {
			simplistics_errors()->add('email_empty', __('Please enter an email address'));
		}elseif($user_email!=$user_email_confirmation){
			simplistics_errors()->add('email_invalid', __('The confirmation email does not correspond'));
		}

		if($_POST['first_name'] == '') {
			simplistics_errors()->add('first_name_empty', __('Please enter your first name'));
		}

		if($_POST['last_name'] == '') {
			simplistics_errors()->add('last_name_empty', __('Please enter your last name'));
		}
	
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

		if ($_POST["age"] == '') {
			simplistics_errors()->add('age_empty', __('You must enter your age'));
		}

		/*if ($_POST["phone"] == '') {
			simplistics_errors()->add('phone_empty', __('You must enter your phone number'));
		}*/
		
		/*if ($_POST["address"] == '' || $_POST["city"] == '' || $_POST["province"] == '' || $_POST["postal_code"] == '') {
			simplistics_errors()->add('address_empty', __('You must enter your address'));
		}*/

		if ($_POST["postal_code"] == '') {
			simplistics_errors()->add('postalcode_empty', __('You must enter your postal code'));
		}

		//$captcha is true if captcha ok
		if (!$captcha->success) {
			simplistics_errors()->add('captcha_false', __('You must validate the captcha'));
		}

		if(!$_POST["terms"]){
			simplistics_errors()->add('terms_notchecked', __('You must accept the terms of services'));
		}

		if($initials==""){
			simplistics_errors()->add('initials_false', __('You must enter your initials'));
		}

		$errors = simplistics_errors()->get_error_messages();
		session_start();
		$_SESSION['errors_registration'] = simplistics_get_error_messages();
		
		if(empty($errors)) {
			

			global $wpdb;
			
			$wpdb->insert(
				$wpdb->prefix."users",
				array(
					"user_login" => $user_login,
					"user_email" => $user_email,
					"user_nicename" => $name,
					"display_name" => $name,
					"user_activation_key" => $activation,
					"user_pass" => wp_hash_password($user_pass),
					"user_registered" => date('Y-m-d H:i:s'),
				)
			);


			$new_user_id = $wpdb->insert_id;

			foreach($answers as $key=>$value){
				$wpdb->insert(
				$wpdb->prefix."signup_answers",
					array(
						"answer" => $value,
						"question_id" => $key,
						"user_id" => $new_user_id
					)
				);
			}
			
			

			add_user_meta( $new_user_id, 'gender', $_POST["gender"]);
			add_user_meta( $new_user_id, 'age', $_POST["age"]);
			//add_user_meta( $new_user_id, 'phone', $_POST["phone"]);

			//add_user_meta( $new_user_id, 'address', $_POST["address"]);
			
			//add_user_meta( $new_user_id, 'city', $_POST["city"]);
			//add_user_meta( $new_user_id, 'province', $_POST["province"]);
			add_user_meta( $new_user_id, 'postal_code', $_POST["postal_code"]);




			/*add_user_meta( $new_user_id, 'nickname', $user_login);
			add_user_meta( $new_user_id, 'description', '');
			add_user_meta( $new_user_id, 'rich_editing', 'true');
			add_user_meta( $new_user_id, 'comment_shortcuts', 'false');
			add_user_meta( $new_user_id, 'admin_color', 'fresh');
			add_user_meta( $new_user_id, 'use_ssl', '0');
			add_user_meta( $new_user_id, 'show_admin_bar_front', 'true');
			add_user_meta( $new_user_id, 'wp_capabilities', 'a:1:{s:10:"subscriber";b:1;}');
			add_user_meta( $new_user_id, 'wp_user_level', '0');
			add_user_meta( $new_user_id, 'default_password_nag', '1');
			add_user_meta( $new_user_id, 'session_tokens', '');
			add_user_meta( $new_user_id, 'active_status', '0');*/
			update_user_meta($new_user_id, 'date_signup',date('Y-m-d H:i:s'));
			update_user_meta($new_user_id, 'ip_address',$ipadress);
			update_user_meta($new_user_id, 'initials',$initials);

			// wp_update_user(array('ID' => $new_user_id,'role' => $user_role));

			//send account info to user
			sendRegistrationEmail($user_email,$name,$user_pass);

			wp_setcookie($user_email, $user_pass, true);
			wp_set_current_user( $new_user_id );
			wp_signon( array( 'user_login' => $user_email, 'user_password' => $user_pass ) );

			wp_redirect($setting->getPluginHome()); exit;
			
 			
		}

		$_SESSION["value_first_name_name"]=$_POST['first_name'];
		$_SESSION["value_last_name_name"]=$_POST['last_name'];		
		$_SESSION["value_email"]=$user_email;
		$_SESSION["value_age"]=$_POST["age"];
		//$_SESSION["value_phone"]=$_POST["phone"];
		//$_SESSION["value_address"]=$_POST["address"];
		//$_SESSION["value_city"]=$_POST["city"];
		$_SESSION["value_postal"]=$_POST["postal_code"];
		
		wp_redirect($setting->getPluginRegistration());
		exit;
	}
}

function sendRegistrationEMail($to,$first_name,$password){
	global $setting;

	$subject = $setting->getRegistrationSubject();
	
	$headers = array('Content-Type: text/html; charset=UTF-8','From: '.$setting->getEmailFrom().' <noreply@bikechallenge.com');
	
	$body = '<html><body>';
	/*$body .= '<h1>Welcome '.$name.'</h1>';
	$body .= '<h2>Here are your account info :</h2>';
	$body .= '<p>Login : '.$to.'</p>';
	$body .= '<p>Pasword : '.$password.'</p>';*/
	$body.=$setting->getRegistrationEmail();
	$body .= '</body></html>';


	wp_mail( $to, $subject, $body, $headers );

}