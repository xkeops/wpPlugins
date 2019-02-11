<?php
function mfwp_options_page($content) 
	{
	global $mfwp_options;	
	global $wpdb;

	ob_start(); 	
	?>	
	<div class="wrap">
	<h2>Mississauga - Plugin Options</h2>
	<form action="#v_form" method="post" id="v_form">
		<?php settings_fields('mfwp_settings_group'); ?>
		<h4><?php _e('Twitter Information', 'mfwp_domain'); ?></h4>
		<p>
			<label class="description" for="mfwp_settings[twitter_url]">
			<?php _e('Enter your Twitter URL', 'mfwp_domain') ?></label>
			<input id="twitter_url" name="twitter_url" type="text" value="<?php echo $mfwp_options['twitter_url']; ?>" />
		</p>
		<p class="submit">
			<input type="submit" name="submit_form" class="button-primary" value="<?php _e('Save Options', 'mfwp_domain'); ?>" />
		</p>
	</form>
	</div>

	<?php
	$html = ob_get_clean();

    // does the inserting, in case the form is filled and submitted
    $welcome_text = "test"; //strip_tags($_POST["twitter_url"], "");
    if ( isset( $_POST["submit_form"] ) && $welcome_text != "" ) {

	$current_user = wp_get_current_user();
	$dp3_email = $current_user->user_email;
	
	$table_name = $wpdb->prefix . 'dp3';
	
	$wpdb->insert( 
		$table_name, 
		array( 
			'time' => current_time( 'mysql' ), 
			'email' => $dp3_email, 
			'text' => $welcome_text, 
		) 
	);
        $html = "<p>Your info was successfully recorded. Thanks!!</p>";
    }
    // if the form is submitted but the name is empty
    if ( isset( $_POST["submit_form"] ) && $_POST["twitter_url"] == "" )
        $html .= "<p>You need to fill the required fields.</p>";


//outputs everything	
echo $html;
}

function mfwp_add_options_link() {
	/*
		add_options_page('D3 Link Plugin Options', 'DD3 Plugin', 'manage_options', 'mfwp-options', 'mfwp_options_page');
	*/
	add_options_page('D3 Link Plugin Options', 'DD3 Plugin', 'read', 'mfwp-options', 'mfwp_options_page');
}

add_action('admin_menu','mfwp_add_options_link');

function mfwp_register_settings(){
	register_setting('mfwp_settings_group','mfwp_settings');
}

add_action('admin_init','mfwp_register_settings')

?>