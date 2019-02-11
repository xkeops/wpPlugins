<?php

/* 
display functions for outputting information 
*/

function mfwp_add_content($content) {
	global $mfwp_options;

	if(is_single())	{
		$extra_content = '';
			//'<p class="twitter-message">Follow me on <a href="' . $mfwp_options['twitter_url'] . '">' . $mfwp_options['twitter_url'] . '</a></p>';
		$content .= $extra_content;
	}
	return $content;
}
add_filter('the_content', 'mfwp_add_content');

?>