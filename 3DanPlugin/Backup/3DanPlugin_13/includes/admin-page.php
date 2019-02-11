<?php
function mfwp_options_page($content) 
	{
	global $mfwp_options;	
	global $wpdb;

	ob_start(); 	
	?>	
	<div class="wrap">
	<h2>Biking Entries</h2>
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
		echo $d3_distance.' hours';
		echo "</td>";
		echo "</tr>";
		$sumdistance = $sumdistance + $d3_distance;
	}

	echo "<tr>";
	echo "<td>TOTAL</td>";
	echo "<td style='text-align:right; font-weight:700;'>";
	echo $sumdistance.' hours';
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
	echo $dp3_goal.' hours';
	echo "</td>";
	echo "</tr>";


	?>
	
	</table>
	<?php
}

function mfwp_add_options_link() {
	add_menu_page('D3 Link Plugin Options', 'Cycle', 'read', 'cycle-options', 'mfwp_options_page');
	add_submenu_page('cycle-options', 'Goal', 'Goal', 'read', 'd3-goal', 'd3_goal');

}
add_action('admin_menu','mfwp_add_options_link');


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

	<div class="wrap">
	<h2>Goal Set</h2>
	<form action="#v_form" method="post" id="v_form">
		<?php settings_fields('mfwp_settings_group'); ?>
		<h4><?php _e('Cycling Goal', 'mfwp_domain'); ?></h4>
		<p>
			<label class="description" for="mfwp_settings[twitter_url]"><?php _e('Goal', 'mfwp_domain') ?></label>
			<input id="distance" name="distance" type="text" value="<?php echo $dp3_distance;?>" />
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