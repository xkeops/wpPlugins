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
		<h4><?php _e('Information', 'mfwp_domain'); ?></h4>
		<p>
			<label class="description" for="mfwp_settings[twitter_url]"><?php _e('Distance', 'mfwp_domain') ?></label>
			<input id="distance" name="distance" type="text" value="" />
		</p>
		<p class="submit">
			<input type="submit" name="submit_form" class="button-primary" value="<?php _e('Save Options', 'mfwp_domain'); ?>" />
		</p>
	</form>
	</div>

	<?php
	$html = ob_get_clean();

    // does the inserting, in case the form is filled and submitted
    $dp3_distance = strip_tags($_POST["distance"], "");
    if ( isset( $_POST["submit_form"] ) && $dp3_distance != "" ) {

	$current_user = wp_get_current_user();
	$dp3_email = $current_user->user_email;
	
	$table_name = $wpdb->prefix . 'dp3';
	
	$wpdb->insert( 
		$table_name, 
		array( 
			'time' => current_time( 'mysql' ), 
			'email' => $dp3_email, 
			'distance' => $dp3_distance, 
		) 
	);
        $html = "<p>Your info was successfully recorded. Thanks!!</p>";
    }
    // if the form is submitted but the name is empty
    if ( isset( $_POST["submit_form"] ) && $_POST["distance"] == "" )
        $html .= "<p>You need to fill the required fields.</p>";


//outputs everything	
echo $html;
}

function mfwp_add_options_link() {
	/*
		add_options_page('D3 Link Plugin Options', 'DD3 Plugin', 'manage_options', 'mfwp-options', 'mfwp_options_page');
	*/
	//add_menu_page('D3 Link Plugin Options', 'Cycle', 'read', 'mfwp-options', 'mfwp_options_page');

	add_menu_page('D3 Link Plugin Options', 'Cycle', 'read', 'cycle-options', 'mfwp_options_page');
	add_submenu_page('cycle-options', 'Goal', 'Goal', 'read', 'd3-goal', 'd3_goal');

}
add_action('admin_menu','mfwp_add_options_link');


function d3_goal() {
	global $mfwp_options;	
	global $wpdb;
?>

	<div class="wrap">
	<h2>Goal Set</h2>
	<form action="#v_form" method="post" id="v_form">
		<?php settings_fields('mfwp_settings_group'); ?>
		<h4><?php _e('Cycling Goal', 'mfwp_domain'); ?></h4>
		<p>
			<label class="description" for="mfwp_settings[twitter_url]"><?php _e('Goal', 'mfwp_domain') ?></label>
			<input id="distance" name="distance" type="text" value="" />
		</p>
		<p class="submit">
			<input type="submit" name="submit_form" class="button-primary" value="<?php _e('Save Options', 'mfwp_domain'); ?>" />
		</p>
	</form>
	</div>

	<?php
	$html = ob_get_clean();

    // does the inserting, in case the form is filled and submitted
    $dp3_distance = strip_tags($_POST["distance"], "");
    if ( isset( $_POST["submit_form"] ) && $dp3_distance != "" ) {

	$current_user = wp_get_current_user();
	$dp3_email = $current_user->user_email;
	
	$table_name = $wpdb->prefix . 'dp3';
	
	$wpdb->get_results( "SELECT * FROM $table_name where email = '$dp3_email' and type='goal'" );
	if($wpdb->num_rows==0) {
		$wpdb->insert( 
			$table_name, 
			array( 
				'time' => current_time( 'mysql' ), 
				'email' => $dp3_email, 
				'distance' => $dp3_distance, 
				'type' => 'goal', 
				) 
			);
		$html = 'INSERT: ';
	}
	else {
	//UPDATE
		$wpdb->update( 
			$table_name, 
			array( 
				'time' => current_time( 'mysql' ), 
				'distance' => $dp3_distance, 
				),
			array('email'=>$dp3_email,'type'=>'goal')
			);

		$html = 'UPDATE: ';
	}

        $html .= "<p>Your info was successfully recorded. Thanks!!</p>";
    }
    // if the form is submitted but the name is empty
    if ( isset( $_POST["submit_form"] ) && $_POST["distance"] == "" )
        $html .= "<p>You need to fill the required fields.</p>";


//outputs everything	
echo $html;

}


function mfwp_register_settings(){
	register_setting('mfwp_settings_group','mfwp_settings');
}

add_action('admin_init','mfwp_register_settings')

?>