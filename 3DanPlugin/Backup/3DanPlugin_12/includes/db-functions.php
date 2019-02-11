<?php

global $dp3_db_version;
$dp3_db_version = '1.0';

function dp3_install() {
	global $wpdb;
	global $dp3_db_version;

	$table_name = $wpdb->prefix . 'dp3';
	
	$charset_collate = $wpdb->get_charset_collate();

	// sql to create your table
	// NOTICE that:
	// 1. each field MUST be in separate line
	// 2. There must be two spaces between PRIMARY KEY and its name	
	//    Like this: PRIMARY KEY[space][space](id)
	// otherwise dbDelta will not work
	$sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		email varchar(100),
		time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		text text NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

	// we do not execute sql directly
	// we are calling dbDelta which cant migrate database
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );

	// save current database version for later use (on upgrade)
	add_option( 'dp3_db_version', $dp3_db_version );


	/************ UPDATE TABLE ******/

    /**
     * [OPTIONAL] Example of updating to 1.1 version
     *
     * If you develop new version of plugin
     * just increment $custom_table_example_db_version variable
     * and add following block of code
     *
     * must be repeated for each new version
     * in version 1.1 we change email field
     * to contain 200 chars rather 100 in version 1.0
     * and again we are not executing sql
     * we are using dbDelta to migrate table changes
     */

	/*

    $installed_ver = get_option('dp3_db_version');
    if ($installed_ver != $dp3_db_version) {
        $sql = "CREATE TABLE " . $table_name . " (
          id int(11) NOT NULL AUTO_INCREMENT,
          name tinytext NOT NULL,
          email VARCHAR(200) NOT NULL,
          age int(11) NULL,
          PRIMARY KEY  (id)
        );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        // notice that we are updating option, rather than adding it
        update_option('dp3_db_version', $dp3_db_version);

	*/

}

//register_activation_hook( __FILE__, 'dp3_install' );


function dp3_install_data() {
	global $wpdb;
	
	$welcome_name = 'Mr. WordPress';
	$welcome_text = 'Congratulations, you just completed the installation!';
	
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
}

// register_activation_hook( __FILE__, 'dp3_install_data' );



/**
 * Trick to update plugin database, see docs
 */
function dp3_table_example_update_db_check()
{
    global $dp3_db_version;
    if (get_site_option('dp3_db_version') != $dp3_db_version) {
        dp3_install();
    }
}

//add_action('plugins_loaded', 'dp3_table_example_update_db_check');

