<h1>Enter your new password below.</h1>
<?php simplistics_show_error_messages(); 
$user = wp_get_current_user()->data;
$activation = $user->user_activation_key;?>
<form id="simplistics_password_reset_form" class="simplistics_form" action="" method="POST">
	<div class="input-group fixed-width">
		<span class="input-group-addon">Password</span>
		<input name="simplistics_user_pass" id="password" class="required" type="password"/>
	</div>
	<div class="input-group fixed-width">
		<span class="input-group-addon">Password Again</span>
		<input name="simplistics_user_pass_confirm" id="password_again" class="required" type="password"/>
	</div>
	<input type="hidden" name="simplistics_user_pass_hash" value="<?php echo $activation; ?>"/>
	<input type="hidden" name="simplistics_reset_password_nonce" value="<?php echo wp_create_nonce('simplistics-reset-password-nonce'); ?>"/>
	<div class="ils-row" style="overflow:auto;padding-top:15px">
		<div class="input-group submit block ils-col-12" style="clear:both">
		    <input type="submit" value="Change Password" class="form-control button btn" />
		</div>
	</div>
</form>