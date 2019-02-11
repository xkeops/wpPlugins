<?php

    // create your role(s)
    // for more details on this see: https://codex.wordpress.org/Function_Reference/add_role 
    // adding "read => true" enables the wp-admin area for the user with just dashboard and profile tabs
    add_role(
        'd3_user_role',
        'D3 User Role',
        array( 'read' => true, 'custom_menu_slug' => true )
    );



function D3_custom_menu_page() {
        global $current_user;
    // now admins and the custom_user_role will see the menu and can work with it
    if ( in_array( $current_user->roles[0], array( 'd3_user_role', 'administrator' ) ) ) {
        // use the dynamic role to register the menu. so as your different users log in, the menu will load for them
                //add_menu_page( 'Your Menu Page', 'Your Menu Page', $current_user->roles[0], 'cusotm_menu_slug', 'your_custom_menu_page_contents', '', -1 );
                add_menu_page('D3 Link Plugin Options', 'Cycle', $current_user->roles[0], 'd3-options', 'd3_entry');
                add_submenu_page('d3-options', 'Goal', 'Goal', $current_user->roles[0], 'd3-goal', 'd3_goal');

        }
}

add_action( 'admin_menu', 'D3_custom_menu_page' );

function d3_entry($content) 
	{
	global $mfwp_options;	
	global $wpdb;

	ob_start(); 	
	?>	
	<div class="wrap dtrack">
	<h2>Biking Entry</h2>
	<form action="#v_form" method="post" id="v_form">
		<?php settings_fields('mfwp_settings_group'); ?>
		<p>
<input id="distance" name="distance" type="hidden" value="1" /> 
<span class="description" id="spDistance">1</span>
<span class="description">KM</span>
<input type="range" name="distanceInputName" id="distanceInputId" value="1" min="1" max="300" oninput="distance.value = distanceInputId.value; spDistance.innerText = distanceInputId.value;" class="range-slider-handle">
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

	list_d3_entries();

}

function list_d3_entries(){
	global $wpdb;
    	
	$table_name = $wpdb->prefix . 'dp3';
	$current_user = wp_get_current_user();
	$dp3_email = $current_user->user_email;
	?>
	<table>
		<tr>
			<td>When</td>
			<td>Distance</td>
		</tr>
	<?php
	$sumdistance = 0;
	foreach( $wpdb->get_results("SELECT *, DATE_FORMAT(time,'%d %b %y') tmrun FROM $table_name where email = '$dp3_email' and type='entry' order by time desc") as $key => $row) {
		// each column in your row will be accessible like this

		$d3_time = $row->tmrun;
		$d3_distance = $row->distance;
		echo "<tr>";
		echo "<td>";
		echo $d3_time;
		echo "</td>";
		echo "<td style='text-align:right'>";
		echo $d3_distance.' KM';
		echo "</td>";
		echo "</tr>";
		$sumdistance = $sumdistance + $d3_distance;
	}

	echo "<tr>";
	echo "<td>TOTAL</td>";
	echo "<td style='text-align:right; font-weight:700;'>";
	echo $sumdistance.' KM';
	echo "</td>";
	echo "</tr>";

	$arrInfo = $wpdb->get_results( "SELECT * FROM $table_name where email = '$dp3_email' and type='goal'" );
	$nrows = $wpdb->num_rows;
	if($nrows>0) {
		$row_info = $arrInfo[0];
		$dp3_goal = $row_info->distance;
	}
	else
		$dp3_goal = 'Unset Goal';

	echo "<tr>";
	echo "<td style='font-weight:700;'>GOAL</td>";
	echo "<td style='text-align:right; font-weight:700;'>";
	echo $dp3_goal.' KM';
	echo "</td>";
	echo "</tr>";


	?>
	
	</table>
	<?php
}

function d3_goal() {
	global $mfwp_options;	
	global $wpdb;
    	
	$table_name = $wpdb->prefix . 'dp3';
	$current_user = wp_get_current_user();
	$dp3_email = $current_user->user_email;
	$dp3_distance = '';
	$nrows = 0;

	$arrInfo = $wpdb->get_results( "SELECT * FROM $table_name where email = '$dp3_email' and type='goal'" );
	$nrows = $wpdb->num_rows;

//echo 'Rows:'.$nrows;

	if($nrows>0) {
		$row_info = $arrInfo[0];
		$dp3_distance = $row_info->distance;
	}

//echo 'Distance:'.$dp3_distance;


    // does the inserting, in case the form is filled and submitted
    if ( isset( $_POST["submit_form"] ) && strip_tags($_POST["distance"], "") != "" ) {

	$dp3_distance = strip_tags($_POST["distance"], "");
	if($nrows==0) {
		$wpdb->insert( 
			$table_name, 
			array( 
				'time' => current_time( 'mysql' ), 
				'email' => $dp3_email, 
				'distance' => $dp3_distance, 
				'type' => 'goal', 
				) 
			);
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
	}

	$html = ob_get_clean();


        $html .= "<p>Your info was successfully recorded. Thanks!!</p>";
    }

    // if the form is submitted but the name is empty
    if ( isset( $_POST["submit_form"] ) && $_POST["distance"] == "" )
        $html .= "<p>You need to fill the required fields.</p>";

//outputs everything	
echo $html;
?>

	<div class="wrap dgoal">
	<h2 class="HeadGoal">Goal Set</h2>	
	<form action="#v_form" method="post" id="v_form">
		<?php settings_fields('mfwp_settings_group'); ?>
		<!--<h4><?php _e('Cycling Goal', 'mfwp_domain'); ?></h4>-->
		<p>
<input id="distance" name="distance" type="hidden" value="<?php echo $dp3_distance;?>" /> 
<span class="description" id="spDistance"><?php echo $dp3_distance;?></span>
<span class="description">KM</span>
		</p>
<p>
<input type="range" name="distanceInputName" id="distanceInputId" value="<?php echo $dp3_distance;?>" min="1" max="300" oninput="distance.value = distanceInputId.value; spDistance.innerText = distanceInputId.value;" class="range-slider-handle">
</p>
		<p class="submit">
			<input type="submit" name="submit_form" class="button-primary" value="<?php _e('Save Options', 'mfwp_domain'); ?>" />
		</p>
	</form>
	</div>

	<?php
}


function mfwp_register_settings(){
	register_setting('mfwp_settings_group','mfwp_settings');
}

add_action('admin_init','mfwp_register_settings')

?>