<?php
function mfwp_options_page($content) 
	{
	global $mfwp_options;	
	ob_start(); 	
	?>	
	<div class="wrap">
	<h2>Mississauga - Plugin Options</h2>
	<form method="post" action="options.php">
		<?php settings_fields('mfwp_settings_group'); ?>
		<h4><?php _e('Twitter Information', 'mfwp_domain'); ?></h4>
		<p>
			<label class="description" for="mfwp_settings[twitter_url]">
			<?php _e('Enter your Twitter URL', 'mfwp_domain') ?></label>
			<input id="mfwp_settings[twitter_url]" name="mfwp_settings[twitter_url]" type="text" value="<?php echo $mfwp_options['twitter_url']; ?>" />
		</p>
		<p>
			<label class="description" for="mfwp_settings[title_url]">
			<?php _e('Enter your Title', 'mfwp_domain') ?>
			</label>
			<input id="mfwp_settings[twitter_url]" name="mfwp_settings[title_url]" type="text" value="<?php echo $mfwp_options['title_url']; ?>" />
		</p>
		<p class="submit">
			<input type="submit" class="button-primary" value="<?php _e('Save Options', 'mfwp_domain'); ?>" />
		</p>
	</form>
	</div>

	<?php
	echo ob_get_clean();
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